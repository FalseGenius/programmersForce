<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class addressUsers extends Model
{
    use HasFactory;
    protected $fillable = ['ip_address', "checkIn_time", "checkout_time", "stay_duration", "workday_status"];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
