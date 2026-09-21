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
        'show_header_logo',
        'show_header_company_name',
        'show_header_address',
        'show_header_phone',
        'show_header_email',
        'show_header_lab_no',
        'show_header_report_date',
        'show_header_sample_date',
        'show_header_report_status',
        'show_patient_info',
        'show_patient_name',
        'show_patient_age_gender',
        'show_patient_id',
        'show_patient_referred_by',
        'show_patient_contact',
        'show_patient_collection_type',
        'show_patient_fasting',
        'show_patient_clinical_info',
        'show_patient_barcode',
        'show_footer',
        'show_footer_qr',
        'show_footer_signature',
        'show_footer_doctor_name',
        'show_footer_doctor_degree',
        'show_footer_doctor_reg',
        'show_footer_disclaimer',
        'doctor_name',
        'doctor_degree',
        'doctor_reg_no',
        'default_lab_prefix',
        'default_referred_by',
        'default_collection_type',
        'default_fasting',
        'default_clinical_info',
    ];

    protected $casts = [
        'show_header' => 'boolean',
        'show_header_logo' => 'boolean',
        'show_header_company_name' => 'boolean',
        'show_header_address' => 'boolean',
        'show_header_phone' => 'boolean',
        'show_header_email' => 'boolean',
        'show_header_lab_no' => 'boolean',
        'show_header_report_date' => 'boolean',
        'show_header_sample_date' => 'boolean',
        'show_header_report_status' => 'boolean',
        'show_patient_info' => 'boolean',
        'show_patient_name' => 'boolean',
        'show_patient_age_gender' => 'boolean',
        'show_patient_id' => 'boolean',
        'show_patient_referred_by' => 'boolean',
        'show_patient_contact' => 'boolean',
        'show_patient_collection_type' => 'boolean',
        'show_patient_fasting' => 'boolean',
        'show_patient_clinical_info' => 'boolean',
        'show_patient_barcode' => 'boolean',
        'show_footer' => 'boolean',
        'show_footer_qr' => 'boolean',
        'show_footer_signature' => 'boolean',
        'show_footer_doctor_name' => 'boolean',
        'show_footer_doctor_degree' => 'boolean',
        'show_footer_doctor_reg' => 'boolean',
        'show_footer_disclaimer' => 'boolean',
    ];
}
