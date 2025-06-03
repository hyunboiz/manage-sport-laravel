<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
class PaymentMethod extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'image', 'status'];

    public static function createPaymentMethod(array $data)
    {
        if (isset($data['image'])) {
            $iconPath = $data['image']->store('payment_methods', 'public');
            $data['image'] = '/storage/' . $iconPath;
        }
        return self::create($data);
    }

    public function updatePaymentMethod(array $data)
    {
        if (isset($data['image'])) {
            // Xoá icon cũ
            if ($this->image) {
                $oldPath = str_replace('/storage/', '', $this->image);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            $iconPath = $data['image']->store('payment_methods', 'public');
            $data['image'] = '/storage/' . $iconPath;
        }
        return $this->update($data);
    }

    public function getIconUrlAttribute()
    {
        return asset($this->icon);
    }
}
