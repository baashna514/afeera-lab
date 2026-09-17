<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestResult extends Model
{
    use BelongsToCompany, HasFactory;

    protected $fillable = [
        'company_id',
        'test_booking_item_id',
        'status',
        'verified_by',
        'verified_at',
        'remarks',
    ];

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function bookingItem()
    {
        return $this->belongsTo(TestBookingItem::class, 'test_booking_item_id');
    }

    public function parameters()
    {
        return $this->hasMany(TestResultParameter::class)->orderBy('sort_order');
    }
}
