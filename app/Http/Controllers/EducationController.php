<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EduService;

class EducationController extends Controller
{
    public function index()
    {
        $subjects = [
            ['name' => 'Science', 'icon' => 'fa-atom', 'color' => 'blue'],
            ['name' => 'Mathematics', 'icon' => 'fa-calculator', 'color' => 'emerald'],
            ['name' => 'English', 'icon' => 'fa-language', 'color' => 'purple'],
            ['name' => 'Social Studies', 'icon' => 'fa-earth-asia', 'color' => 'orange'],
            ['name' => 'Nepali', 'icon' => 'fa-pennant', 'color' => 'red'],
            ['name' => 'HPE', 'icon' => 'fa-heart-pulse', 'color' => 'rose']
        ];

        $topicMatrix = [
            "Quantum Mechanics",
            "Organic Chemistry",
            "Genetic Engineering",
            "Global Warming",
            "Trigonometry",
            "Calculus",
            "World War II",
            "Nepali Heritage",
            "Poetry Analysis",
            "Hydropower",
            "Tourism in Nepal",
            "Human Anatomy"
        ];

        return view('user.education', compact('subjects', 'topicMatrix'));
    }

    public function generateLesson(Request $request)
    {
        $request->validate([
            'grade' => 'required|integer|min:3|max:10',
            'subject' => 'required|string',
            'topic' => 'required|string|min:1'
        ]);

        try {
            $lesson = EduService::generateEduResponse(
                $request->grade,
                $request->subject,
                $request->topic
            );

            return response()->json([
                'success' => true,
                'lesson' => $lesson
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate lesson. Please try again.'
            ], 500);
        }
    }

    public function followUp(Request $request)
    {
        $request->validate([
            'context' => 'required|string',
            'question' => 'required|string|min:1'
        ]);

        try {
            $answer = EduService::eduFollowUp(
                $request->context,
                $request->question
            );

            return response()->json([
                'success' => true,
                'answer' => $answer
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get answer. Please try again.'
            ], 500);
        }
    }
    public function quiz(Request $request)
    {
        return response()->json([
            'quiz' => EduService::generateQuiz($request->grade, $request->topic)
        ]);
    }

    public function flash(Request $request)
    {
        return response()->json([
            'cards' => EduService::generateFlashCards($request->grade, $request->topic)
        ]);
    }
}
