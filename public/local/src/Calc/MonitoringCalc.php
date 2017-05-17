<?php

namespace App\Calc;

use Respect\Validation\Validator as v;

class MonitoringCalc extends AbstractCalc {
    function validateState($state) {
        $validator = v::keySet(
            v::key('DESCRIPTION', v::stringType()->notEmpty()),
            v::key('BUILDING_COUNT', v::intType()->min(1)),
            // TODO validate reference?
            v::key('LOCATION_ID', v::intType()),
            v::key('ADDRESS', v::stringType()->notEmpty())
        );
        return $validator->validate($state);
    }

    protected function multipliers($state) {
        // TODO stub
        return [
            'BUILDING_COUNT' => 2,
            'LOCATION_ID' => 1.5
        ];
    }
}