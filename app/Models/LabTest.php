<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LabTest extends Model
{
    use BelongsToCompany, HasFactory;

    protected $fillable = [
        'company_id',
        'category',
        'name',
        'code',
        'price',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    /**
     * Get the sub-tests / parameters for this test.
     */
    public function parameters(): HasMany
    {
        return $this->hasMany(LabTestParameter::class)->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Get the report notes / descriptions for this test.
     */
    public function notes(): HasMany
    {
        return $this->hasMany(LabTestNote::class)->orderBy('sort_order')->orderBy('id');
    }
}
