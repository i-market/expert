<?php

namespace Core;

class FileUpload extends \FileUpload\FileUpload {
    protected function trimFilename($name, $type, $index, $content_range) {
        // don't trim say cyrillic characters
        $name = Util::basename(stripslashes($name));
        if (!$name) {
            $name = str_replace('.', '-', microtime(true));
        }
        return $name;
    }
}