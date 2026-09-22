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

    /**
     * Determine whether the result is Normal, Low, or High based on the reference range.
     */
    public function getFlagAttribute(): ?string
    {
        if ($this->result_value === null || trim($this->result_value) === '') {
            return null;
        }

        $val = trim($this->result_value);
        $range = trim($this->normal_range_text ?? '');

        if ($range === '') {
            return 'Normal';
        }

        // Clean value (e.g. remove commas like "5,135" -> "5135")
        $cleanVal = str_replace([',', ' '], '', $val);

        if (is_numeric($cleanVal)) {
            $numVal = (float) $cleanVal;

            // Pattern 1: Min - Max (e.g. "4.0 - 11.0", "4.0-10.0", "20.0 - 40.0", "150-450")
            if (preg_match('/^([0-9.]+)\s*[-–—to]\s*([0-9.]+)$/iu', $range, $m)) {
                $min = (float) $m[1];
                $max = (float) $m[2];

                if ($numVal < $min) {
                    return 'Low';
                }
                if ($numVal > $max) {
                    return 'High';
                }

                return 'Normal';
            }

            // Pattern 2: Upper bound only (e.g. "< 200", "<= 150", "Up to 150", "<150")
            if (preg_match('/^(?:<|<=|≤|up\s+to)\s*([0-9.]+)$/iu', $range, $m)) {
                $max = (float) $m[1];
                if ($numVal > $max) {
                    return 'High';
                }

                return 'Normal';
            }

            // Pattern 3: Lower bound only (e.g. "> 60", ">= 60", ">60")
            if (preg_match('/^(?:>|>=|≥)\s*([0-9.]+)$/iu', $range, $m)) {
                $min = (float) $m[1];
                if ($numVal < $min) {
                    return 'Low';
                }

                return 'Normal';
            }
        }

        // Qualitative results (e.g. Negative, Non-Reactive, Normal)
        $lowerVal = strtolower($val);
        $lowerRange = strtolower($range);

        $negativeTerms = ['negative', 'non-reactive', 'non reactive', 'nil', 'not detected', 'normal'];
        $positiveTerms = ['positive', 'reactive', 'detected', 'abnormal'];

        if (in_array($lowerRange, $negativeTerms)) {
            if (in_array($lowerVal, $positiveTerms)) {
                return 'High';
            }
            if (in_array($lowerVal, $negativeTerms)) {
                return 'Normal';
            }
        }

        return 'Normal';
    }

    /**
     * Get CSS class for styling the flag.
     */
    public function getFlagClassAttribute(): string
    {
        return match (strtolower($this->flag ?? 'normal')) {
            'low' => 'flag-low',
            'high' => 'flag-high',
            default => 'flag-normal',
        };
    }
}
