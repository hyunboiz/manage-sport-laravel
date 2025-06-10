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
    public function updateSport(array $data)
{
    if (isset($data['icon'])) {
        $iconPath = $data['icon']->store('sports', 'public');
        $iconFullPath = '/storage/' . $iconPath;  // Hoặc chỉ lưu $iconPath nếu muốn dùng asset()
        $data['icon'] = $iconFullPath;
    }

    return $this->update($data);
}

public function getIconUrlAttribute()
{
    return asset($this->icon);  // Nếu lưu full path (/storage/...), thì chỉ cần asset($this->icon)
}

public function fields()
{
    return $this->hasMany(Field::class);
}
}
