<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;
use App\Models\Student;
use Carbon\Carbon;

class LessonController extends Controller
{
    // Store from the student form (existing flow)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'title' => 'nullable|string|max:255',
            'start' => 'required|date',
            'end' => 'nullable|date|after_or_equal:start',
            'notes' => 'nullable|string',
        ]);

        // ensure student belongs to user
        $student = Student::where('id', $validated['student_id'])->where('user_id', auth()->id())->firstOrFail();

        $lesson = $student->lessons()->create([
            'title' => $validated['title'] ?? 'Lekcja',
            'user_id' => auth()->id(),
            'start' => $validated['start'],
            'end' => $validated['end'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        // Automatically create payment with student's rate
        $lesson->payment()->create([
            'user_id' => auth()->id(),
            'amount' => $student->rate,
            'status' => 'awaiting',
            'notes' => null,
        ]);

        return redirect()->back()->with('success', 'Lekcja dodana.');
    }

    // helper: normalize incoming date string to app timezone Carbon instance
    protected function normalizeToAppTimezone(?string $value): ?Carbon
    {
        if (empty($value)) {
            return null;
        }

        // If client sent an ISO with Z / offset, Carbon::parse will respect it.
        // If client sent a local string like "2025-11-22 12:30:00" parse as app timezone.
        try {
            if (str_ends_with($value, 'Z') || str_contains($value, 'T')) {
                $dt = Carbon::parse($value); // will have timezone info if present
            } else {
                $dt = Carbon::createFromFormat('Y-m-d H:i:s', $value, config('app.timezone'));
                if (! $dt) {
                    // try date-only formats
                    $dt = Carbon::parse($value);
                }
            }
        } catch (\Exception $e) {
            // fallback parse
            $dt = Carbon::parse($value);
        }

        // convert to application timezone so saved value is consistent
        return $dt->setTimezone(config('app.timezone'));
    }

    // AJAX: create lesson (calendar dateClick)
    public function ajaxStore(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'title' => 'nullable|string|max:255',
            'start' => 'required|date',
            'end' => 'nullable|date|after_or_equal:start',
            'notes' => 'nullable|string',
        ]);

        $student = Student::where('id', $validated['student_id'])->where('user_id', auth()->id())->firstOrFail();

        $start = $this->normalizeToAppTimezone($validated['start']);
        $end = $this->normalizeToAppTimezone($validated['end'] ?? null);

        $lesson = $student->lessons()->create([
            'title' => $validated['title'] ?? 'Lekcja',
            'user_id' => auth()->id(),
            'start' => $start,
            'end' => $end,
            'notes' => $validated['notes'] ?? null,
        ]);

        // Automatically create payment with student's rate
        $lesson->payment()->create([
            'user_id' => auth()->id(),
            'amount' => $student->rate,
            'status' => 'awaiting',
            'notes' => null,
        ]);

        return response()->json([
            'success' => true,
            'lesson' => [
                'id' => $lesson->id,
                'title' => $lesson->title,
                // return local-formatted strings (no trailing Z)
                'start' => $lesson->start ? $lesson->start->format('Y-m-d H:i:s') : null,
                'end' => $lesson->end ? $lesson->end->format('Y-m-d H:i:s') : null,
            ],
        ], 201);
    }

    // AJAX: update lesson (drag/resize or title edit)
    public function ajaxUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'start' => 'required|date',
            'end' => 'nullable|date|after_or_equal:start',
            'notes' => 'nullable|string',
        ]);

        $lesson = Lesson::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        $start = $this->normalizeToAppTimezone($validated['start']);
        $end = $this->normalizeToAppTimezone($validated['end'] ?? null);

        $lesson->update([
            'title' => $validated['title'] ?? $lesson->title,
            'start' => $start,
            'end' => $end,
            'notes' => $validated['notes'] ?? $lesson->notes,
        ]);

        return response()->json([
            'success' => true,
            'lesson' => [
                'id' => $lesson->id,
                'title' => $lesson->title,
                'start' => $lesson->start ? $lesson->start->format('Y-m-d H:i:s') : null,
                'end' => $lesson->end ? $lesson->end->format('Y-m-d H:i:s') : null,
            ],
        ]);
    }

    // AJAX: delete lesson
    public function ajaxDestroy($id)
    {
        $lesson = Lesson::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $lesson->delete();

        return response()->json(['success' => true]);
    }

    // Show lesson details (for modal preview or dedicated page)
    public function show($id)
    {
        $lesson = Lesson::where('id', $id)->where('user_id', auth()->id())->with('student', 'payment')->firstOrFail();

        return view('lessons.show', ['lesson' => $lesson]);
    }

    // Show edit form for lesson
    public function edit($id)
    {
        $lesson = Lesson::where('id', $id)->where('user_id', auth()->id())->with('student')->firstOrFail();
        $allStudents = Student::where('user_id', auth()->id())->orderBy('name')->get();

        return view('lessons.edit', ['lesson' => $lesson, 'allStudents' => $allStudents]);
    }

    // Update lesson
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'title' => 'nullable|string|max:255',
            'start' => 'required|date',
            'end' => 'nullable|date|after_or_equal:start',
            'notes' => 'nullable|string',
        ]);

        $lesson = Lesson::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        // Ensure student belongs to user
        $student = Student::where('id', $validated['student_id'])->where('user_id', auth()->id())->firstOrFail();

        $start = $this->normalizeToAppTimezone($validated['start']);
        $end = $this->normalizeToAppTimezone($validated['end'] ?? null);

        $lesson->update([
            'student_id' => $validated['student_id'],
            'title' => $validated['title'] ?? 'Lekcja',
            'start' => $start,
            'end' => $end,
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('dashboard.show', $lesson->student_id)->with('success', 'Lekcja zaktualizowana.');
    }

    // Delete lesson
    public function destroy($id)
    {
        $lesson = Lesson::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $student_id = $lesson->student_id;
        $lesson->delete();

        return redirect()->route('dashboard.show', $student_id)->with('success', 'Lekcja usunięta.');
    }
}
