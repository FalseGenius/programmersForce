<?php

namespace App\Models;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyUserSession extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'date', 'total_stay_duration'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
