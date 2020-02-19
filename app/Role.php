<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /* One Role assign to many users */
    public function Users() {
        return $this->belongsToMany('App\User','user_role','role_id','user_id');
    }
}
