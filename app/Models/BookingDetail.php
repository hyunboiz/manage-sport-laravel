<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingDetail extends Model
{
    use HasFactory;

    public function booking()
{
    return $this->belongsTo(Booking::class);
}

public function field()
{
    return $this->belongsTo(Field::class);
}

public function timeFrame()
{
    return $this->belongsTo(TimeFrame::class, 'time_id');
}
}
