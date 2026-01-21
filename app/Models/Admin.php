<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory;

    protected $table = 'admin';
    protected $fillable = ['username', 'password'];
    protected $hidden = ['password'];

    // Nonaktifkan timestamps jika tidak pakai
    // public $timestamps = false;
}