<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Regis extends Model
{
    protected $table = 'reg_periksa'; // Use existing table
    protected $primaryKey = 'no_rawat';

    protected $fillable = [
        'id_user', // Use your actual username/email column
        'password',
    ];
}
