<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LabTestParameter extends Model
{
    use BelongsToCompany, HasFactory;

    protected $fillable = [
        'company_id',
        'lab_test_id',
        'name',
        'unit',
        'min_range',
        'max_range',
        'normal_range_text',
        'male_range',
        'female_range',
        'method',
        'default_value',
        'sort_order',
    ];

    /**
     * Get the parent lab test.
     */
    public function labTest(): BelongsTo
    {
        return $this->belongsTo(LabTest::class);
    }
}
