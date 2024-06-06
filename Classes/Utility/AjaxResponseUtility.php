<?php

namespace Petitglacon\CategoryTreebuilder\Utility;

use Petitglacon\CategoryTreebuilder\Enum\ToastStatus;

class AjaxResponseUtility
{

    public static function getJsonResponse(string $status, string $message = '', $data = null, $reloadTree = true) {
        return json_encode([
            'status' => $status,
            'message' => $message,
            'data' => $data,
            'reloadTree' => $reloadTree,
        ]);
    }

}