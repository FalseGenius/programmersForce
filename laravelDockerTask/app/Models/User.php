<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements Authenticatable, JWTSubject
{
    use HasRoles;
    use HasFactory;
    use AuthenticatableTrait;

    protected $fillable = ['username', 'email', 'password', 'role'];

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
