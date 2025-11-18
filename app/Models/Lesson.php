<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'student_id',
        'user_id',
        'start',
        'end',
        'notes',
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // one payment per lesson (adjust to hasMany if you prefer installments)
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // Formatted accessors (day.month.year and with time)
    public function getStartFormattedAttribute()
    {
        return $this->start ? $this->start->format('d.m.Y H:i') : null;
    }

    public function getEndFormattedAttribute()
    {
        return $this->end ? $this->end->format('d.m.Y H:i') : null;
    }
}
