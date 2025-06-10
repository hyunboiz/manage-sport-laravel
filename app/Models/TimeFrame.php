<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeFrame extends Model
{
    use HasFactory;
    protected $fillable = ['start', 'end', 'ex_rate'];
    public function bookingDetails()
    {
        return $this->hasMany(BookingDetail::class, 'time_id');
    }
}
