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

        $totalPaid = $payments->where('status', 'zapłacone')->sum(fn($p) => (float) $p->amount);
        $totalAwaiting = $payments->where('status', 'oczekuje')->sum(fn($p) => (float) $p->amount);
        $totalLessons = Lesson::where('user_id', $userId)->count();
        $unpaidCount = $payments->where('status', '!=', 'zapłacone')->count();

        return view('dashboard.finance', compact(
            'payments',
            'totalPaid',
            'totalAwaiting',
            
            'totalLessons',
            'unpaidCount'
        ));
    }
}
