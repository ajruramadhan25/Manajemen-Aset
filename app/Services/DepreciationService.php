<?php

namespace App\Services;

use App\Models\Asset;
use Carbon\Carbon;

class DepreciationService
{
    /**
     * Calculate Straight-Line Depreciation Schedule.
     * Formula: (Cost - Residual Value) / Useful Life
     *
     * @param Asset $asset
     * @return array
     */
    public function calculateStraightLine(Asset $asset): array
    {
        $cost = $asset->price;
        $residual = $asset->residual_value;
        $life = $asset->useful_life;

        // Ensure purchase_date is a Carbon instance
        $purchaseDate = Carbon::parse($asset->purchase_date);

        if ($life <= 0)
            return [];

        $depreciableAmount = $cost - $residual;
        $annualDepreciation = $depreciableAmount / $life;

        $schedule = [];
        $currentValue = $cost;

        for ($year = 1; $year <= $life; $year++) {
            $yearDate = $purchaseDate->copy()->addYears($year);
            $currentValue -= $annualDepreciation;

            // Ensure we don't go below residual value (floating point safety)
            if ($currentValue < $residual) {
                $currentValue = $residual;
            }

            $schedule[] = [
                'year' => $year,
                'date' => $yearDate->format('Y-m-d'),
                'depreciation_amount' => round($annualDepreciation, 2),
                'book_value' => round($currentValue, 2),
            ];
        }

        return $schedule;
    }
}
