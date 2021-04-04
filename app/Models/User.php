<?php

namespace App\Models;

use App\Models\User\Role;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    protected $table = "users";
    protected $fillable = array('name', 'last_name', 'phone', 'address', 'email', 'password', 'active', 'role_id');
    protected $dates = array("deleted_at");
    protected $hidden = array('password', 'updated_at', 'deleted_at');
    protected $appends = array('full_name');

    public function getFullNameAttribute()
    {
        return empty($this->last_name) ? $this->name : "{$this->name} {$this->last_name}";
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
