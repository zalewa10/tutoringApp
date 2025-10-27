<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Tell static analysis that auth()->user() is an App\Models\User
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $students = $user->students()->orderBy("created_at", "desc")->paginate(10);
         return view('dashboard.index', ['students' => $students]);
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
             'name'=>'required|string|max:255',
             'surname'=>'required|string|max:255',
             'tel'=>'nullable|string|max:50',
             'rate'=>'required|numeric|min:0',
         ]);

         auth()->user()->students()->create($validated);

         return redirect()->route('dashboard.index')->with('success', 'Utworzono nowego ucznia!');

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
         $student = Student::findOrFail($id);
         
         return view('dashboard.student', ['student' => $student]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        //
    }
    public function delete($id){
        $student = Student::findOrFail($id);
        $student->delete();

        return redirect()->route('dashboard.index')->with('success', 'Student deleted successfully');
    }
}





// <?php

// namespace App\Http\Controllers;

// use App\Models\Students;
// use App\Models\Subjects;
// use Illuminate\Http\Request;

// class StudentsController extends Controller
// {
//     public function index()
//     {
//         $students = Students::with('Subjects')->orderBy("created_at", "desc")->paginate(10);
//         return view('dashboard.index', ['students' => $students]);
//     }

//     public function show($id)
//     {
//         $student = Students::with('Subjects')->findOrFail($id);
//         return view('dashboard.student', ['student' => $student]);
//     }

//     public function create()
//     {
//         $subjects = Subjects::all();
//         return view('dashboard.create', ['subjects' => $subjects]);
//     }

//     public function store(Request $request){
//         $validated = $request->validate([
//             'Name'=>'required|string|max:255',
//             'Age'=>'required|integer|min:0|max:100',
//             'bio'=>'required|string|min:20|max:1000',
//             'subjects_id'=>'required|exists:subjects,id',
//         ]);

//         Students::create($validated);
//         return redirect()->route('dashboard.index')->with('success', 'Student created successfully');

//     }

//     
// }
