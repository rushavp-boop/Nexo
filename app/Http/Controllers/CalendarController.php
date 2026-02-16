<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Event;

class CalendarController extends Controller
{
    public function index($year = 2082)
    {
        $path = public_path("json/{$year}.json");

        if (!file_exists($path)) {
            abort(404, "Nepali calendar JSON for year {$year} not found.");
        }

        $json = file_get_contents($path);
        $calendar = json_decode($json, true);

        return view('user.calendar', compact('calendar', 'year'));
    }

    public function todayDate()
    {
        try {
            $res = Http::withoutVerifying()->get("https://sudhanparajuli.com.np/api/todaydate");
            return response()->json($res->json());
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function bsToAd($year, $month, $day)
    {
        try {
            $res = Http::withoutVerifying()->get("https://sudhanparajuli.com.np/api/bs-to-ad/{$year}/{$month}/{$day}");
            return response()->json($res->json());
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function adToBs($year, $month, $day)
    {
        try {
            $res = Http::withoutVerifying()->get("https://sudhanparajuli.com.np/api/ad-to-bs/{$year}/{$month}/{$day}");
            return response()->json($res->json());
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function calculateAgeFromBs($year, $month, $day)
    {
        try {
            $res = Http::withoutVerifying()->get("https://sudhanparajuli.com.np/api/calculateage/bs/{$year}/{$month}/{$day}");
            return response()->json($res->json());
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function calculateAgeFromAd($year, $month, $day)
    {
        try {
            $res = Http::withoutVerifying()->get("https://sudhanparajuli.com.np/api/calculateage/ad/{$year}/{$month}/{$day}");
            return response()->json($res->json());
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    // Event Management Methods
    public function getEvents(Request $request)
    {
        try {
            if (!auth()->check()) {
                return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
            }

            $events = Event::where('user_id', auth()->id())
                ->orderBy('event_date', 'asc')
                ->get();

            return response()->json(['success' => true, 'events' => $events]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function getEventsByMonth(Request $request, $year, $month)
    {
        try {
            if (!auth()->check()) {
                return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
            }

            $startDate = sprintf('%04d-%02d-01', $year, $month);
            $endDate = date('Y-m-t', strtotime($startDate));

            $events = Event::where('user_id', auth()->id())
                ->whereBetween('event_date', [$startDate, $endDate])
                ->orderBy('event_date', 'asc')
                ->get();

            return response()->json(['success' => true, 'events' => $events]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function createEvent(Request $request)
    {
        try {
            if (!auth()->check()) {
                return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
            }

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'location' => 'nullable|string|max:255',
                'event_date' => 'required|date',
                'start_time' => 'nullable|date_format:H:i',
                'end_time' => 'nullable|date_format:H:i',
                'event_type' => 'nullable|in:manual,hotel,car',
                'category' => 'nullable|in:work,personal,health,travel,education',
                'priority' => 'nullable|in:low,medium,high',
                'related_id' => 'nullable|integer'
            ]);

            $validated['user_id'] = auth()->id();
            $validated['event_type'] = $validated['event_type'] ?? 'manual';
            $validated['category'] = $validated['category'] ?? 'personal';
            $validated['priority'] = $validated['priority'] ?? 'medium';

            $event = Event::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Event created successfully!',
                'event' => $event
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteEvent($id)
    {
        try {
            if (!auth()->check()) {
                return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
            }

            $event = Event::where('id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            $event->delete();

            return response()->json([
                'success' => true,
                'message' => 'Event deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function searchEvents(Request $request)
    {
        try {
            if (!auth()->check()) {
                return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
            }

            $query = Event::where('user_id', auth()->id());

            // Search by title
            if ($request->has('search') && $request->search) {
                $query->where('title', 'like', '%' . $request->search . '%');
            }

            // Filter by category
            if ($request->has('category') && $request->category) {
                $query->where('category', $request->category);
            }

            // Filter by priority
            if ($request->has('priority') && $request->priority) {
                $query->where('priority', $request->priority);
            }

            $events = $query->orderBy('event_date', 'asc')->get();

            return response()->json(['success' => true, 'events' => $events]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function getEventStats(Request $request)
    {
        try {
            if (!auth()->check()) {
                return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
            }

            $userId = auth()->id();
            $today = now()->toDateString();

            $stats = [
                'total' => Event::where('user_id', $userId)->count(),
                'upcoming' => Event::where('user_id', $userId)
                    ->where('event_date', '>=', $today)
                    ->count(),
                'past' => Event::where('user_id', $userId)
                    ->where('event_date', '<', $today)
                    ->count(),
                'by_category' => Event::where('user_id', $userId)
                    ->selectRaw('category, count(*) as count')
                    ->groupBy('category')
                    ->pluck('count', 'category'),
                'by_priority' => Event::where('user_id', $userId)
                    ->selectRaw('priority, count(*) as count')
                    ->groupBy('priority')
                    ->pluck('count', 'priority'),
            ];

            return response()->json(['success' => true, 'stats' => $stats]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
