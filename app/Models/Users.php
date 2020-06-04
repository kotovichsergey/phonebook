<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model {
    protected $table = 'users';
    protected $fillable = [
        'login',
        'password',
        'email',
        'name',
        'surname'
    ];

    public $timestamps = false;

    public function phones() {
        return $this->hasMany(Phones::class, 'user_id');
    }
}