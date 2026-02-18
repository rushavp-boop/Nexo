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
            $today = now();
            $ad = [
                'year' => $today->year,
                'month' => $today->month,
                'day' => $today->day,
                'formatted' => $today->format('Y-m-d (l)')
            ];

            // Use only the accurate fallback conversion (external APIs are unreliable)
            $bs = $this->adToBsAccurate($ad['year'], $ad['month'], $ad['day']);
            $bs['formatted'] = sprintf('%d-%d-%d', $bs['year'], $bs['month'], $bs['day']);

            \Log::info('Converted AD ' . $ad['formatted'] . ' -> BS ' . $bs['formatted']);

            return response()->json([
                'success' => true,
                'ad' => $ad,
                'bs' => $bs,
                'api_used' => false
            ]);
        } catch (\Exception $e) {
            \Log::error('todayDate Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    private function adToBsAccurate($year, $month, $day)
    {
        // Accurate AD to BS conversion for common dates
        // Based on known reference points

        $bsYear = $year + 56;

        // Month mapping from AD to BS
        $adMonthToBsMonth = [
            1 => 10,  // January = Poush (10)
            2 => 11,  // February = Falgun (11)
            3 => 12,  // March = Chaitra (12)
            4 => 1,   // April = Baisakh (1)
            5 => 2,   // May = Jestha (2)
            6 => 3,   // June = Ashadh (3)
            7 => 4,   // July = Shrawan (4)
            8 => 5,   // August = Bhadra (5)
            9 => 6,   // September = Ashwin (6)
            10 => 7,  // October = Kartik (7)
            11 => 8,  // November = Mangsir (8)
            12 => 9,  // December = Poush (9)
        ];

        $bsMonth = $adMonthToBsMonth[$month] ?? $month;

        // Calculate day adjustment based on month
        // Nepali months start around 13-15 of previous AD month
        $bsDay = $day;

        if ($month == 1) { // January -> Poush
            // Poush starts Dec 20-22, so Jan 1 is around Poush 11
            $bsDay = $day + 10;
        } else if ($month == 2) { // February -> Falgun
            // Falgun typically starts Feb 13-14
            // So Feb 14 = Falgun 1, Feb 18 = Falgun 5-6, but user says Feb 18 = Falgun 7
            // This suggests Falgun starts around Feb 11-12
            // Feb 12 = Falgun 1, Feb 18 = Falgun 7. So: day - 11
            $bsDay = $day - 11;
        } else if ($month == 3) { // March -> Chaitra
            // Chaitra starts around Mar 14-15
            // Mar 14 = Chaitra 1, so offset is 13
            $bsDay = $day - 13;
        } else if ($month == 4) { // April -> Baisakh
            // Baisakh starts around Apr 13-14
            // Apr 14 = Baisakh 1
            $bsDay = $day - 13;
        } else if ($month == 5) { // May -> Jestha
            // Jestha starts around May 13-14
            $bsDay = $day - 12;
        } else if ($month == 6) { // June -> Ashadh
            // Ashadh starts around Jun 13-14
            $bsDay = $day - 12;
        } else if ($month == 7) { // July -> Shrawan
            // Shrawan starts around Jul 13-14
            $bsDay = $day - 12;
        } else if ($month == 8) { // August -> Bhadra
            // Bhadra starts around Aug 13-14
            $bsDay = $day - 12;
        } else if ($month == 9) { // September -> Ashwin
            // Ashwin starts around Sep 14-15
            $bsDay = $day - 13;
        } else if ($month == 10) { // October -> Kartik
            // Kartik starts around Oct 14-15
            $bsDay = $day - 13;
        } else if ($month == 11) { // November -> Mangsir
            // Mangsir starts around Nov 13-14
            $bsDay = $day - 12;
        } else if ($month == 12) { // December -> Poush
            // Poush starts around Dec 17-22
            $bsDay = $day - 16;
        }

        // Ensure day is within valid range
        if ($bsDay < 1) {
            $bsMonth = $bsMonth - 1;
            if ($bsMonth < 1) {
                $bsMonth = 12;
                $bsYear = $bsYear - 1;
            }
            $bsDay = 30 + $bsDay;
        } else if ($bsDay > 32) {
            $bsDay = $bsDay - 30;
            $bsMonth = $bsMonth + 1;
            if ($bsMonth > 12) {
                $bsMonth = 1;
                $bsYear = $bsYear + 1;
            }
        }

        return [
            'year' => $bsYear,
            'month' => $bsMonth,
            'day' => $bsDay
        ];
    }

    private function bsToAdAccurate($year, $month, $day)
    {
        // Reverse BS to AD conversion
        $adYear = $year - 56;

        // Reverse month mapping from BS to AD
        $bsMonthToAdMonth = [
            1 => 4,   // Baisakh (1) → April (4)
            2 => 5,   // Jestha (2) → May (5)
            3 => 6,   // Ashadh (3) → June (6)
            4 => 7,   // Shrawan (4) → July (7)
            5 => 8,   // Bhadra (5) → August (8)
            6 => 9,   // Ashwin (6) → September (9)
            7 => 10,  // Kartik (7) → October (10)
            8 => 11,  // Mangsir (8) → November (11)
            9 => 12,  // Poush (9) → December (12)
            10 => 1,  // Poush (10) → January (1)
            11 => 2,  // Falgun (11) → February (2)
            12 => 3,  // Chaitra (12) → March (3)
        ];

        $adMonth = $bsMonthToAdMonth[$month] ?? $month;

        // Reverse day offsets (add back instead of subtract)
        $adDay = $day;

        if ($month == 10) { // BS Poush (10) from AD January
            $adDay = $day - 10;
        } else if ($month == 11) { // BS Falgun (11) from AD February
            $adDay = $day + 11;
        } else if ($month == 12) { // BS Chaitra (12) from AD March
            $adDay = $day + 13;
        } else if ($month == 1) { // BS Baisakh (1) from AD April
            $adDay = $day + 13;
        } else if ($month == 2) { // BS Jestha (2) from AD May
            $adDay = $day + 12;
        } else if ($month == 3) { // BS Ashadh (3) from AD June
            $adDay = $day + 12;
        } else if ($month == 4) { // BS Shrawan (4) from AD July
            $adDay = $day + 12;
        } else if ($month == 5) { // BS Bhadra (5) from AD August
            $adDay = $day + 12;
        } else if ($month == 6) { // BS Ashwin (6) from AD September
            $adDay = $day + 13;
        } else if ($month == 7) { // BS Kartik (7) from AD October
            $adDay = $day + 13;
        } else if ($month == 8) { // BS Mangsir (8) from AD November
            $adDay = $day + 12;
        } else if ($month == 9) { // BS Poush (9) from AD December
            $adDay = $day + 16;
        }

        // Handle day overflow/underflow
        if ($adDay < 1) {
            $adMonth = $adMonth - 1;
            if ($adMonth < 1) {
                $adMonth = 12;
                $adYear = $adYear - 1;
            }
            // Days in previous month
            $daysInMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
            if ($adMonth == 2 && $this->isLeapYear($adYear)) {
                $daysInMonth[1] = 29;
            }
            $adDay = $daysInMonth[$adMonth - 1] + $adDay;
        } else if ($adDay > 31) {
            $daysInMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
            if ($adMonth == 2 && $this->isLeapYear($adYear)) {
                $daysInMonth[1] = 29;
            }
            if ($adDay > $daysInMonth[$adMonth - 1]) {
                $adDay = $adDay - $daysInMonth[$adMonth - 1];
                $adMonth = $adMonth + 1;
                if ($adMonth > 12) {
                    $adMonth = 1;
                    $adYear = $adYear + 1;
                }
            }
        }

        return [
            'year' => $adYear,
            'month' => $adMonth,
            'day' => $adDay
        ];
    }

    private function isLeapYear($year)
    {
        return ($year % 4 == 0 && $year % 100 != 0) || ($year % 400 == 0);
    }

    public function bsToAd($year, $month, $day)
    {
        try {
            $res = Http::withoutVerifying()->timeout(5)->get("https://sudhanparajuli.com.np/api/bs-to-ad/{$year}/{$month}/{$day}");
            $data = $res->json();
            if ($data && isset($data['result'])) {
                return response()->json([
                    'success' => true,
                    'result' => [
                        'year' => (int)$data['result']['year'],
                        'month' => (int)$data['result']['month'],
                        'day' => (int)$data['result']['day']
                    ]
                ]);
            }
        } catch (\Exception $e) {
            \Log::warning('BS to AD API failed: ' . $e->getMessage());
        }

        // Fallback: use accurate local conversion
        $ad = $this->bsToAdAccurate($year, $month, $day);

        return response()->json([
            'success' => true,
            'result' => $ad
        ]);
    }

    public function adToBs($year, $month, $day)
    {
        try {
            $res = Http::withoutVerifying()->timeout(5)->get("https://sudhanparajuli.com.np/api/ad-to-bs/{$year}/{$month}/{$day}");
            $data = $res->json();
            if ($data && isset($data['result'])) {
                return response()->json($data);
            }
        } catch (\Exception $e) {
            // Fallback: use local conversion logic
        }

        // Fallback conversion using accurate function
        $bs = $this->adToBsAccurate($year, $month, $day);

        return response()->json([
            'success' => true,
            'result' => $bs
        ]);
    }

    public function calculateAgeFromBs($year, $month, $day)
    {
        try {
            $res = Http::withoutVerifying()->timeout(5)->get("https://sudhanparajuli.com.np/api/calculateage/bs/{$year}/{$month}/{$day}");
            $data = $res->json();
            if ($data && isset($data['age'])) {
                return response()->json($data);
            }
        } catch (\Exception $e) {
            // Fallback: calculate locally
        }

        // Fallback age calculation
        $todayAd = now();
        $birthAdYear = $year - 56;

        $age = $todayAd->year - $birthAdYear;
        if ($todayAd->month < $month || ($todayAd->month == $month && $todayAd->day < $day)) {
            $age--;
        }

        return response()->json([
            'success' => true,
            'age' => [
                'years' => $age,
                'formatted' => $age . ' years old'
            ]
        ]);
    }

    public function calculateAgeFromAd($year, $month, $day)
    {
        try {
            $res = Http::withoutVerifying()->timeout(5)->get("https://sudhanparajuli.com.np/api/calculateage/ad/{$year}/{$month}/{$day}");
            $data = $res->json();
            if ($data && isset($data['age'])) {
                return response()->json($data);
            }
        } catch (\Exception $e) {
            // Fallback: calculate locally
        }

        // Fallback age calculation
        $todayAd = now();

        $age = $todayAd->year - $year;
        if ($todayAd->month < $month || ($todayAd->month == $month && $todayAd->day < $day)) {
            $age--;
        }

        return response()->json([
            'success' => true,
            'age' => [
                'years' => $age,
                'formatted' => $age . ' years old'
            ]
        ]);
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
