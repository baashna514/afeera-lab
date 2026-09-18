<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    use BelongsToCompany;

    protected $fillable = [
        'company_id',
        'company_name',
        'company_email',
        'company_phone',
        'company_address',
        'company_logo',
        'show_header',
        'show_patient_info',
        'show_footer',
        'doctor_name',
        'doctor_degree',
        'doctor_reg_no',
    ];

    protected $casts = [
        'show_header' => 'boolean',
        'show_patient_info' => 'boolean',
        'show_footer' => 'boolean',
    ];
}
