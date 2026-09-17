<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LabTestNote extends Model
{
    use BelongsToCompany, HasFactory;

    protected $fillable = [
        'company_id',
        'lab_test_id',
        'note_text',
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
