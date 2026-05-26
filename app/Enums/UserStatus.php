<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static ACTIVE()
 * @method static static INACTIVE()
 * @method static static BLOCKED()
 * @method static static DELETED()
 */
final class UserStatus extends Enum
{
    const ACTIVE = 'HOẠT ĐỘNG';
    const INACTIVE = 'KHÔNG HOẠT ĐỘNG';
    const BLOCKED = 'ĐÃ KHOÁ';
    const DELETED = 'ĐÃ XOÁ';
}
