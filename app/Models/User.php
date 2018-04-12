<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Gravatar;

class User extends Authenticatable /* implements JWTSubject */
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getAvatarAttribute()
    {
        return $this->avatar();
    }

    public function avatar($size = 80)
    {
        return Gravatar::src($this->email, $size);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }
}
