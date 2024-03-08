<?php declare(strict_types=1);


namespace Frontend\View\Helper;

class FileSizeHelper
{
    public function __invoke($bytes, int $decimals = 2)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, $decimals) . ' GB';
        } else if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, $decimals) . ' MB';
        } else if ($bytes >= 1024) {
            return number_format($bytes / 1024, $decimals) . ' kB';
        } else {
            return $bytes . ' B';
        }
    }
}