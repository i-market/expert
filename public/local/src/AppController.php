<?php

namespace App;

class AppController {
    static function layoutContext() {
        return [
            'copyrightYear' => date('Y')
        ];
    }
}