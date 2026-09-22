<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestBooking extends Model
{
    use BelongsToCompany, HasFactory;

    protected $fillable = [
        'company_id',
        'patient_id',
        'invoice_number',
        'lab_number',
        'referred_by',
        'collection_type',
        'fasting',
        'clinical_info',
        'total_amount',
        'discount',
        'paid_amount',
        'payment_status',
        'status',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function items()
    {
        return $this->hasMany(TestBookingItem::class);
    }
}
