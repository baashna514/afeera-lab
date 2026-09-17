<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use BelongsToCompany, HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'phone',
        'age',
        'gender',
        'address',
    ];

    public function testBookings()
    {
        return $this->hasMany(TestBooking::class);
    }
}
