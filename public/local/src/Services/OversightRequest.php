<?php

namespace App\Services;

use App\Services;
use Core\Underscore as _;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class OversightRequest {
    static function validateParams($params) {
        $contactValidator = v::allOf(
            v::key('ORGANIZATION', v::stringType()),
            v::key('PERSON', v::stringType()->notEmpty()),
            v::key('PHONE_1', v::stringType()),
            v::key('PHONE_2', v::stringType()),
            v::key('EMAIL', v::stringType()->notEmpty())
        );
        $validator = v::allOf(
            v::key('NAME', v::stringType()->notEmpty()),
            v::key('LOCATION', v::stringType()),
            v::key('DESCRIPTION', v::stringType()->notEmpty())
        );
        $errors = [];
        try {
            $validator->assert($params);
        } catch (NestedValidationException $exception) {
            $errors = Services::getMessages($exception);
        }
        try {
            $contactValidator->assert($params['CONTACT']);
        } catch (NestedValidationException $exception) {
            foreach (Services::getMessages($exception) as $name => $msg) {
                $errors["CONTACT[${name}]"] = $msg;
            }
        }
        return $errors;
    }

    static function initialState($data) {
        return [
            'data_set' => $data['SINGLE_BUILDING'],
            'params' => [],
            'errors' => [],
        ];
    }

    static function state($params, $data) {
        $errors = self::validateParams($params);
        $state = array_merge(self::initialState($data), [
            'params' => $params,
            'errors' => $errors
        ]);
        if (_::isEmpty($errors)) {
            $state['model'] = Services::dereferenceParams($params, $state['data_set'], [Services::class, 'findEntity']);
        }
        return $state;
    }

    static function context($state, $service) {
        return array_merge($service, [
            'state' => $state,
            'options' => [
                'DOCUMENTS' => Services::entities2options($state['data_set']['MULTIPLIERS']['DOCUMENTS'])
            ]
        ]);
    }
}