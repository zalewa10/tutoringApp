<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Lesson;

class FinanceController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $payments = Payment::with(['lesson.student'])
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->get();

        $totalPaid = $payments->where('status', 'paid')->sum(fn($p) => (float) $p->amount);
        $totalAwaiting = $payments->where('status', 'awaiting')->sum(fn($p) => (float) $p->amount);
        $totalOverdue = $payments->where('status', 'overdue')->sum(fn($p) => (float) $p->amount);
        $totalLessons = Lesson::where('user_id', $userId)->count();
        $unpaidCount = $payments->where('status', '!=', 'paid')->count();

        return view('dashboard.finance', compact(
            'payments',
            'totalPaid',
            'totalAwaiting',
            'totalOverdue',
            'totalLessons',
            'unpaidCount'
        ));
    }
}
