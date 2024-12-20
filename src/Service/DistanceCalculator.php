<?php

namespace App\Service;

class DistanceCalculator
{
    public function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
{
    $lat1 = deg2rad($lat1);
    $lon1 = deg2rad($lon1);
    $lat2 = deg2rad($lat2);
    $lon2 = deg2rad($lon2);
    $dLat = $lat2 - $lat1;
    $dLon = $lon2 - $lon1;
    $distance = sqrt(($dLat ** 2) + ($dLon ** 2));

    return $distance;
}

}
