<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name','user_id'];

    protected $with = ['permissions'];


    public function permissions() {
        return $this->hasMany(PermissionRole::class);
    }

    public function users() : BelongsToMany {
        return $this->belongsToMany(User::class);
    }
}
