<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; // added

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'surname', 'tel', 'rate', 'active', 'user_id'];

    // Relacja: uczeń należy do użytkownika
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
