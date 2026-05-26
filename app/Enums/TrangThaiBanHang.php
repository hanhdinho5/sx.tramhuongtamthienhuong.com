<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static ACTIVE()
 * @method static static PENDING()
 * @method static static DELETED()
 */
final class TrangThaiBanHang extends Enum
{
    const ACTIVE = 'ĐÃ BÁN';
    const PENDING = 'CHỜ LẤY HÀNG';
    const DELETED = 'ĐÃ XOÁ';
}
