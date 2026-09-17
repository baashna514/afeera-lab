<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestResultParameter extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_result_id',
        'parameter_name',
        'unit',
        'normal_range_text',
        'male_range',
        'female_range',
        'result_value',
        'sort_order',
    ];

    public function testResult()
    {
        return $this->belongsTo(TestResult::class);
    }
}
