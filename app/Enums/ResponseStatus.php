<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ResponseStatus extends Enum
{
    const STATUS_SUCCESS = 'Success';
    const STATUS_ERROR = 'Error';
    const STATUS_FAILED = 'Failed';
}