<?php

namespace Petitglacon\CategoryTreebuilder\Enum;

class FileType
{
    const PASSTHRGOUH =    -1;
    const TEXT =            0;
    const CSV =             1;
    const JSON =            2;

    public static function getExtensionByMimeType($mimeType) {
        switch ($mimeType) {
            case 'text/csv':
                return self::CSV;
            case 'application/json':
                return self::JSON;
            case 'text/plain':
                return self::TEXT;
        }
    }
}