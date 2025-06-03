<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Hash;
class Admin extends Model
{
    use Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'name',
    ];

    // Ẩn các thuộc tính khi trả về JSON
    protected $hidden = [
        'password',
    ];

     /**
     * Tạo admin mới
     */
    public static function createAdmin(array $data): self
    {
        return self::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'name' => $data['fullname'],
        ]);
    }

    /**
     * Cập nhật admin
     */
    public function updateAdmin(array $data): bool
    {
        return $this->update([
            'username' => $data['username'] ?? $this->username,
            'email' => $data['email'] ?? $this->email,
            'name' => $data['name'] ?? $this->name,
        ]);
    }

    /**
     * Xóa admin
     */
    public function deleteAdmin(): bool
    {
        return $this->delete();
    }
}
