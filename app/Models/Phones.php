<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Phones extends Model {
    protected $table = 'Phones';
    protected $fillable = [
        'phone',
        'user_id'
    ];

    public $timestamps = false;

    public function users() {
        return $this->belongsTo(Users::class, 'user_id');
    }
}