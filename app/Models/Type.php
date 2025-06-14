<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'sport_id'];

    public function fields()
    {
        return $this->hasMany(Field::class);
    }
    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }
}
