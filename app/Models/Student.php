<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; // added
use App\Models\Lesson; // added

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'surname', 'tel', 'rate', 'active', 'user_id'];

    protected $casts = [
        'active' => 'boolean',
        'rate' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relacja: uczeń należy do użytkownika
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relacja: uczeń ma wiele lekcji
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
}
