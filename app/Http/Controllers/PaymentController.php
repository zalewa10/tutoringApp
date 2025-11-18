<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:awaiting,paid,overdue',
            'notes' => 'nullable|string',
        ]);

        $lesson = Lesson::where('id', $validated['lesson_id'])
                        ->where('user_id', auth()->id())
                        ->firstOrFail();

        $payment = Payment::updateOrCreate(
            ['lesson_id' => $lesson->id, 'user_id' => auth()->id()],
            [
                'amount' => $validated['amount'],
                'status' => $validated['status'],
                'paid_at' => $validated['status'] === 'paid' ? now() : null,
                'notes' => $validated['notes'] ?? null,
            ]
        );

        return redirect()->back()->with('success', 'Płatność zapisana.');
    }

    public function markPaid($id)
    {
        $payment = Payment::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $payment->update(['status' => 'paid', 'paid_at' => now()]);
        return redirect()->back()->with('success', 'Oznaczono jako zapłacone.');
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:awaiting,paid,overdue',
        ]);

        $payment = Payment::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $data = ['status' => $validated['status']];
        if ($validated['status'] === 'paid') {
            $data['paid_at'] = now();
        } else {
            $data['paid_at'] = null;
        }
        $payment->update($data);

        return redirect()->back()->with('success', 'Status płatności zaktualizowany.');
    }
}
