<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
protected $fillable = [
    'customer_id',
    'admin_id',
    'status',
    'total',
    'payment_id',
    'payment_txnref',
    'payment_transaction_no',
    'paid_at',
    'payment_note',
];
    public function customer()
{
    return $this->belongsTo(Customer::class);
}

public function admin()
{
    return $this->belongsTo(Admin::class);
}

public function paymentMethod()
{
    return $this->belongsTo(PaymentMethod::class, 'payment_id');
}

public function bookingDetails()
{
    return $this->hasMany(BookingDetail::class);
}
}
