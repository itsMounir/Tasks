<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\HasImage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasImage;

    protected static function booted()
    {
        parent::boot();

        if (request()->route()->getName() === 'users.show' || request()->route()->getName() === 'users.index') {
            static::retrieved(function ($user) {
                $user->created_from = $user->created_at->diffForHumans();
            });
        }
    }
        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function products() {
        return $this->hasMany(Product::class);
    }

}
