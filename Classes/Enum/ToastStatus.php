<?php

namespace Petitglacon\CategoryTreebuilder\Enum;

use TYPO3\CMS\Core\Type\Enumeration;

class ToastStatus extends Enumeration
{

    public const SUCCESS = 'success';
    public const INFO = 'info';
    public const WARNING = 'warning';
    public const ERROR = 'error';

}
