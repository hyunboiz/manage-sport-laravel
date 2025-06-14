<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingDetail extends Model
{
    use HasFactory;
protected $fillable = [
    'booking_id',
    'field_id',
    'time_id',
    'date_book',
    'price'
];
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
