<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\Lesson; 
use Illuminate\Http\Request;

class StudentsController extends Controller
{
 public function index()
    {

        $user = auth()->user();

        $students = $user->students()->orderBy("created_at", "desc")->paginate(10);

        // get lessons for this user with student relation
        $lessons = Lesson::where('user_id', $user->id)->with('student')->get();

        return view('dashboard.students', ['students' => $students, 'lessons' => $lessons]);
    }
}
