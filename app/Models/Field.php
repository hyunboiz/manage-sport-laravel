<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;
     protected $fillable = [
        'image',
        'price',
        'status',
        'sport_id',
        'type_id',
    ];

    public static function createField(array $data): self
    {
        if (isset($data['image'])) {
            $path = $data['image']->store('fields', 'public');
            $data['image'] = '/storage/' . $path;
        }

        return self::create($data);
    }

    /**
     * Update field and image
     */
    public function updateField(array $data): bool
    {
        if (isset($data['image'])) {
            // Delete old image
            if ($this->image) {
                $oldPath = str_replace('/storage/', '', $this->image);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $path = $data['image']->store('fields', 'public');
            $data['image'] = '/storage/' . $path;
        }

        return $this->update($data);
    }

    /**
     * Get full URL for image
     */
    public function getImageUrlAttribute(): string
    {
        return asset($this->image);
    }

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function bookingDetails()
    {
        return $this->hasMany(BookingDetail::class);
    }
}
