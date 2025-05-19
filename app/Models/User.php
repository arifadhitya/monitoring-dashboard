<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    protected $table = 'user'; // Use existing table
    protected $primaryKey = 'id_user';  // Example: 'user_id' (default is 'id')
    public $timestamps = false; // Disable if your table doesn't have created_at & updated_at

    protected $fillable = [
        'id_user', // Use your actual username/email column
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    protected $guarded = ['*']; // Blocks mass assignment for other fields

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->id_user)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    // If your table is not using auto-incrementing primary key
    public $incrementing = false;
}
