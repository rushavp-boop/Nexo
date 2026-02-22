<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\MedicalRecord;
use App\Services\OpenAIService;

class HealthController extends Controller
{
    public function index()
    {
        $medicalRecordsCount = MedicalRecord::where('user_id', Auth::id())->count();
        return view('user.health', compact('medicalRecordsCount'));
    }

    public function medicalRecordsIndex()
    {
        $records = MedicalRecord::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('user.medical-records-index', compact('records'));
    }

    public function medicalRecordsCreate()
    {
        return view('user.medical-records-create');
    }

    public function medicalRecordsStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'record_date' => 'nullable|date',
            'notes' => 'nullable|string|max:2000',
            'prescription' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $file = $request->file('prescription');
        $storedPath = $file->store('medical-records', 'public');

        MedicalRecord::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'record_date' => $validated['record_date'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'prescription_file_path' => $storedPath,
            'original_file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
        ]);

        return redirect()
            ->route('user.medical-records.index')
            ->with('success', 'Medical record uploaded successfully.');
    }

    public function medicalRecordsDestroy(Request $request, MedicalRecord $record)
    {
        if ((int) $record->user_id !== (int) Auth::id()) {
            abort(403);
        }

        if (!empty($record->prescription_file_path) && Storage::disk('public')->exists($record->prescription_file_path)) {
            Storage::disk('public')->delete($record->prescription_file_path);
        }

        $record->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Medical record deleted successfully.',
            ]);
        }

        return redirect()->route('user.profile')->with('success', 'Medical record deleted successfully.');
    }

    public function check(Request $request)
    {
        $validated = $request->validate([
            'symptoms' => 'required|string|min:5',
        ]);

        try {
            $data = OpenAIService::analyzeSymptoms($validated['symptoms']);
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getTravelTips(Request $request)
    {
        $destination = $request->query('destination');

        if (!$destination) {
            return response()->json(['error' => 'Destination required'], 400);
        }

        try {
            $tips = OpenAIService::generateTravelHealthTips($destination);
            return response()->json($tips);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getDoctors(Request $request)
    {
        $diagnosis = $request->query('diagnosis');
        $urgency = $request->query('urgency', 'Medium');

        if (!$diagnosis) {
            return response()->json(['error' => 'Diagnosis required'], 400);
        }

        try {
            $doctors = OpenAIService::generateDoctorRecommendations($diagnosis, $urgency);
            return response()->json($doctors);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
