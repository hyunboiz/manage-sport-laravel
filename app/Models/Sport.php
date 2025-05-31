<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sport extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'icon'];

    public static function createSport(array $data)
    {
        // Xử lý upload icon
        if (isset($data['icon'])) {
            $iconPath = $data['icon']->store('sports', 'public');
            $data['icon'] = $iconPath;
        }
        return self::create($data);
    }
}
