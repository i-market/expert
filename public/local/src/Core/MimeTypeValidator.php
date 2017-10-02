<?php

namespace Core;

use FileUpload\File;
use Core\Underscore as _;

/** supports wildcard mime patterns */
class MimeTypeValidator extends \FileUpload\Validator\MimeTypeValidator {
    public $callback;

    public function __construct(array $validMimeTypes, callable $callback = null) {
        parent::__construct($validMimeTypes);
        $this->callback = $callback;
    }

    static function matches($mime, $pattern) {
        if ($pattern === $mime) {
            return true;
        }
        list($mtype, $_)        = explode('/', $mime);
        list($ptype, $psubtype) = explode('/', $pattern);
        return $psubtype === '*' && $ptype === $mtype;
    }

    public function validate(File $file, $currentSize = null) {
        $mime = $file->getMimeType();
        $isValid = _::matchesAny($this->mimeTypes, function($pattern) use ($mime) {
            return self::matches($mime, $pattern);
        });
        if (!$isValid) {
            $this->isValid = false;
            $file->error = $this->errorMessages[self::INVALID_MIMETYPE];
        }
        if (is_callable($this->callback)) {
            call_user_func($this->callback, $this->isValid, $mime);
        }
        return $this->isValid;
    }
}