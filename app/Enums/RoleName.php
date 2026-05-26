<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static ADMIN()
 * @method static static MODERATOR()
 * @method static static USER()
 */
final class RoleName extends Enum
{
    const ADMIN = 'ADMIN';
    const MODERATOR = 'MODERATOR';
    const USER = 'USER';
    const GIAM_DOC = 'GIAM_DOC';
    const KE_TOAN = 'KE_TOAN';
    const NHAN_VIEN_SX = 'NHAN_VIEN_SX';
}
