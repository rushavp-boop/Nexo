<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\OpenAIService;
use App\Models\Car;
use App\Models\CarBooking;
use App\Models\HotelBooking;
use App\Models\NexoPaisaTransaction;
use App\Models\TravelItinerary;
use App\Models\Event;
use App\Mail\CarBookingReceipt;
use App\Mail\HotelBookingReceipt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class TravelController extends Controller
{
    public function index()
    {
        return view('user.travel');
    }

    public function plan(Request $request)
    {
        $validated = $request->validate([
            'destination' => 'required|string|min:2',
            'budget' => 'required|string|in:Affordable,Standard,Premium',
            'days' => 'required|integer|min:1|max:365',
        ]);

        try {
            $data = OpenAIService::generateItinerary(
                $validated['destination'],
                $validated['budget'],
                $validated['days']
            );

            try {
                TravelItinerary::create([
                    'user_id' => Auth::id(),
                    'destination' => $validated['destination'],
                    'budget' => $validated['budget'],
                    'days' => $validated['days'],
                    'itinerary_data' => $data,
                ]);
            } catch (\Throwable $e) {
                Log::error('Failed to save itinerary: ' . $e->getMessage());
            }

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function itineraries(Request $request)
    {
        $itineraries = TravelItinerary::where('user_id', Auth::id())
            ->latest()
            ->take(20)
            ->get()
            ->map(function ($itinerary) {
                return [
                    'id' => $itinerary->id,
                    'destination' => $itinerary->destination,
                    'budget' => $itinerary->budget,
                    'days' => $itinerary->days,
                    'itinerary' => $itinerary->itinerary_data,
                    'createdAt' => $itinerary->created_at?->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => $itineraries,
        ]);
    }

    public function deleteItinerary(Request $request, TravelItinerary $itinerary)
    {
        if ((int) $itinerary->user_id !== (int) Auth::id()) {
            abort(403);
        }

        $itinerary->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Itinerary deleted successfully.',
            ]);
        }

        return redirect()->route('user.profile')->with('success', 'Itinerary deleted successfully.');
    }

    public function hotels(Request $request)
    {
        $validated = $request->validate([
            'destination' => 'required|string|min:2',
        ]);

        try {
            $data = OpenAIService::searchHotels($validated['destination']);
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function cars(Request $request)
    {
        try {
            // Return all available cars from database
            $cars = Car::all()->map(function ($car) {
                return [
                    'id' => $car->id,
                    'name' => $car->name,
                    'type' => $car->type,
                    'pricePerDay' => $car->price_per_day,
                    'transmission' => $car->transmission,
                    'seatingCapacity' => $car->seating_capacity,
                    'fuelType' => $car->fuel_type,
                    'image_url' => $car->image_url,

                ];
            })->toArray();

            return response()->json($cars);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function carBooking()
    {
        return view('user.car-booking');
    }

    public function carPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'carId' => 'required|exists:cars,id',
            'days' => 'required|integer|min:1',
            'rentalDate' => 'required|date',
            'total' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->nexo_paisa < $validated['total']) {
            return response()->json([
                'status' => 'error',
                'message' => 'Insufficient Nexo Paisa. Please load more paisa to proceed.'
            ], 400);
        }

        $car = Car::findOrFail($validated['carId']);
        try {
            $result = DB::transaction(function () use ($user, $validated, $car) {
                $carBooking = CarBooking::create([
                    'user_id' => $user->id,
                    'car_id' => $validated['carId'],
                    'days' => $validated['days'],
                    'price_per_day' => $car->price_per_day,
                    'total_amount' => $validated['total'],
                    'car_details' => [
                        'name' => $car->name,
                        'type' => $car->type,
                        'transmission' => $car->transmission,
                        'seating_capacity' => $car->seating_capacity,
                        'fuel_type' => $car->fuel_type,
                        'image_url' => $car->image_url,
                        'rental_date' => $validated['rentalDate'],
                    ],
                ]);

                $user->decrement('nexo_paisa', $validated['total']);

                NexoPaisaTransaction::create([
                    'user_id' => $user->id,
                    'type' => 'spend',
                    'amount' => $validated['total'],
                    'description' => "Car booking payment - {$car->name}",
                    'metadata' => [
                        'booking_type' => 'car',
                        'car_id' => $car->id,
                        'days' => $validated['days'],
                        'car_name' => $car->name,
                        'rental_date' => $validated['rentalDate'],
                    ]
                ]);

                $event = Event::create([
                    'user_id' => $user->id,
                    'title' => "Car Rental: {$car->name}",
                    'description' => "{$car->name} ({$car->type}) rental for {$validated['days']} day(s)",
                    'location' => null,
                    'event_date' => $validated['rentalDate'],
                    'event_type' => 'car',
                    'category' => 'travel',
                    'priority' => 'medium',
                    'related_id' => $carBooking->id,
                ]);

                return [$carBooking, $event];
            });

            [$carBooking, $event] = $result;

            $emailSent = true;
            $emailError = null;
            try {
                Mail::to($user->email)->send(new CarBookingReceipt($user, $carBooking));
            } catch (\Throwable $e) {
                $emailSent = false;
                $emailError = $e->getMessage();
                Log::error("Failed to send car booking email: " . $e->getMessage());
            }

            return response()->json([
                'status' => 'success',
                'message' => $emailSent
                    ? 'Payment recorded, calendar synced, and receipt emailed successfully.'
                    : 'Payment recorded and calendar synced, but email sending failed.',
                'bookingId' => $carBooking->id,
                'eventId' => $event->id,
                'emailSent' => $emailSent,
                'emailError' => $emailError,
            ]);
        } catch (\Throwable $e) {
            Log::error('Car payment failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Payment failed. Please try again.',
            ], 500);
        }
    }

    public function hotelBooking()
    {
        return view('user.hotel-booking');
    }

    public function hotelPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hotelName' => 'required|string',
            'location' => 'required|string',
            'nights' => 'required|integer|min:1',
            'checkInDate' => 'required|date',
            'total' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->nexo_paisa < $validated['total']) {
            return response()->json([
                'status' => 'error',
                'message' => 'Insufficient Nexo Paisa. Please load more paisa to proceed.'
            ], 400);
        }

        // Get hotel data from request body
        $hotelData = $request->input('hotelData', []);

        // Calculate price per night
        $pricePerNight = isset($hotelData['pricePerNight'])
            ? (float) $hotelData['pricePerNight']
            : ((float) $validated['total'] / (int) $validated['nights']);

        // Ensure amenities is an array
        $amenities = isset($hotelData['amenities']) && is_array($hotelData['amenities'])
            ? $hotelData['amenities']
            : (isset($hotelData['amenities']) ? [$hotelData['amenities']] : []);

        try {
            $result = DB::transaction(function () use ($user, $validated, $pricePerNight, $hotelData, $amenities) {
                $hotelBooking = HotelBooking::create([
                    'user_id' => $user->id,
                    'hotel_name' => $validated['hotelName'],
                    'location' => $validated['location'],
                    'nights' => $validated['nights'],
                    'price_per_night' => $pricePerNight,
                    'total_amount' => (float) $validated['total'],
                    'rating' => $hotelData['rating'] ?? null,
                    'amenities' => $amenities,
                    'image_url' => $hotelData['image'] ?? null,
                    'hotel_details' => array_merge($hotelData, [
                        'check_in_date' => $validated['checkInDate'],
                    ]),
                ]);

                $user->decrement('nexo_paisa', $validated['total']);

                NexoPaisaTransaction::create([
                    'user_id' => $user->id,
                    'type' => 'spend',
                    'amount' => (float) $validated['total'],
                    'description' => "Hotel booking payment - {$validated['hotelName']}",
                    'metadata' => [
                        'booking_type' => 'hotel',
                        'hotel_name' => $validated['hotelName'],
                        'location' => $validated['location'],
                        'nights' => $validated['nights'],
                        'check_in_date' => $validated['checkInDate'],
                    ]
                ]);

                $event = Event::create([
                    'user_id' => $user->id,
                    'title' => "Hotel: {$validated['hotelName']}",
                    'description' => "Check-in at {$validated['hotelName']}, {$validated['location']} for {$validated['nights']} night(s)",
                    'location' => $validated['location'],
                    'event_date' => $validated['checkInDate'],
                    'event_type' => 'hotel',
                    'category' => 'travel',
                    'priority' => 'medium',
                    'related_id' => $hotelBooking->id,
                ]);

                return [$hotelBooking, $event];
            });

            [$hotelBooking, $event] = $result;

            $emailSent = true;
            $emailError = null;
            try {
                Mail::to($user->email)->send(new HotelBookingReceipt($user, $hotelBooking));
            } catch (\Throwable $e) {
                $emailSent = false;
                $emailError = $e->getMessage();
                Log::error("Failed to send hotel booking email: " . $e->getMessage());
            }

            return response()->json([
                'status' => 'success',
                'message' => $emailSent
                    ? 'Payment recorded, calendar synced, and receipt emailed successfully.'
                    : 'Payment recorded and calendar synced, but email sending failed.',
                'bookingId' => $hotelBooking->id,
                'eventId' => $event->id,
                'emailSent' => $emailSent,
                'emailError' => $emailError,
            ]);
        } catch (\Throwable $e) {
            Log::error('Hotel payment failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Payment failed. Please try again.'
            ], 500);
        }
    }
}
