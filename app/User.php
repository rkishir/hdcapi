<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable {

    use Notifiable,
        HasApiTokens,
        SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $appends = ['avatar_url'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'active', 'activation_token', 'avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'activation_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getAvatarUrlAttribute() {
        return Storage::url('avatars/' . $this->id . '/' . $this->avatar);
    }

    /* users can have multiple roles */

    public function roles() {
        return $this->belongsToMany('App\Role', 'user_role', 'user_id', 'role_id');
    }
    
    /* 
     * Check user has any role or not 
     */
    public function hasAnyRole($roles) {
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }
        } else {
            if ($this->hasRole($roles)) {
                return true;
            }
        }
        return false;
    }
    
    /* 
     * check user has specific role or not 
     */
    
    public function hasRole($role) {
        if ($this->roles()->where('name', $role)->first()) {
            return true;
        } else {
            return false;
        }
    }

}
