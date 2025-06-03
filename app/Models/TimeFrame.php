<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeFrame extends Model
{
    use HasFactory;
    protected $fillable = ['start', 'end', 'ex_rate'];

    // Accessors để trả về H:i (giờ:phút)
    public function getStartAttribute($value)
    {
        return date('H:i', strtotime($value));
    }

    public function getEndAttribute($value)
    {
        return date('H:i', strtotime($value));
    }
}
