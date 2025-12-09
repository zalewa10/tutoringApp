<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\Lesson; // added
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $user = auth()->user();

        $students = $user->students()->orderBy("created_at", "desc")->paginate(10);

        // get lessons for this user with student relation
        $lessons = Lesson::where('user_id', $user->id)->with('student')->get();

        // pass full list of students for modal selector (not paginated)
        $allStudents = $user->students()->orderBy('name')->get();

        return view('dashboard.index', ['students' => $students, 'lessons' => $lessons, 'allStudents' => $allStudents]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('dashboard.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'tel' => 'nullable|string|max:50',
            'rate' => 'required|numeric|min:0',
        ]);

        auth()->user()->students()->create($validated);

        return redirect()->route('dashboard.index')->with('success', 'Utworzono nowego ucznia!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = auth()->user();
        $student = $user->students()->with('lessons')->findOrFail($id);
        $lessons = $student->lessons()->orderBy('start', 'desc')->get();
        return view('dashboard.student', ['student' => $student, 'lessons' => $lessons]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = auth()->user();
        $student = $user->students()->findOrFail($id);
        return view('dashboard.edit', ['student' => $student]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'tel' => 'nullable|string|max:50',
            'rate' => 'required|numeric|min:0',
            'color' => 'nullable|string|regex:/^#[0-9a-fA-F]{6}$/',
            'active' => 'nullable|boolean',
        ]);

        $user = auth()->user();
        $student = $user->students()->findOrFail($id);
        $student->update($validated);

        return redirect()->route('dashboard.index')->with('success', 'Student updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        //
    }
    public function delete($id)
    {
        $user = auth()->user();
        $student = $user->students()->findOrFail($id);
        $student->delete();

        return redirect()->route('dashboard.index')->with('success', 'Student deleted successfully');
    }
}
