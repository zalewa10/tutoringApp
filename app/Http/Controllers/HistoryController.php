<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\Lesson;
use Carbon\Carbon;

class HistoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (method_exists($user, 'lessons')) {
            $lessons = $user->lessons()->with(['payment', 'student'])->get();
        } else {
            // Build base query and only add teacher_id condition if the column exists
            $query = Lesson::with(['payment', 'student'])->where('user_id', $user->id);

            if (Schema::hasColumn('lessons', 'teacher_id')) {
                $query->orWhere('teacher_id', $user->id);
            }

            $lessons = $query->get();
        }

        $lessons = $lessons->sortByDesc('start')->values();

        return view('dashboard.history', compact('lessons'));
    }
}
