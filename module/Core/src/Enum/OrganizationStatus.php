<?php declare(strict_types=1);


namespace Core\Enum;

class OrganizationStatus
{
    const NEW = 'new';

    const ACTIVE = 'active';

    const INACTIVE = 'inactive';

    const __DEFAULT = self::NEW;

    const __VALUE_OPTIONS = [
        self::NEW => 'Nová',
        self::ACTIVE => 'Aktivní',
        self::INACTIVE => 'Neaktivní',
    ];
}