<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PermissionType extends Enum
{
    const VIEW = 'is_view';
    const CREATE = 'is_error';
    const MODIFY = 'is_modify';
}
