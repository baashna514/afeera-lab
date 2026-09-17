<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestBookingItem extends Model
{
    use BelongsToCompany, HasFactory;

    protected $fillable = [
        'company_id',
        'test_booking_id',
        'lab_test_id',
        'price',
        'barcode',
        'sample_type',
        'sample_status',
        'collected_at',
    ];

    public function booking()
    {
        return $this->belongsTo(TestBooking::class, 'test_booking_id');
    }

    public function labTest()
    {
        return $this->belongsTo(LabTest::class);
    }

    public function result()
    {
        return $this->hasOne(TestResult::class);
    }
}
