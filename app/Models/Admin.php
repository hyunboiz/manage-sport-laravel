<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Hash;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'name',
    ];

    protected $hidden = [
        'password',
    ];

    public static function createAdmin(array $data): self
    {
        return self::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'name' => $data['fullname'],
        ]);
    }

    public function updateAdmin(array $data): bool
    {
        return $this->update([
            'username' => $data['username'] ?? $this->username,
            'email' => $data['email'] ?? $this->email,
            'name' => $data['name'] ?? $this->name,
        ]);
    }

    public function deleteAdmin(): bool
    {
        return $this->delete();
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}


