<?php

namespace App\Services;

class OversightCalculator {
    function pricePerSquareMeter($sqMeters) {
        // monitoring and oversight price functions are identical for now
        return (new MonitoringCalculator)->pricePerSquareMeter($sqMeters);
    }
 }