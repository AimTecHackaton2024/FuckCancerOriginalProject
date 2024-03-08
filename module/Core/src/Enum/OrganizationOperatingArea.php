<?php declare(strict_types=1);


namespace Core\Enum;

class OrganizationOperatingArea
{
    const CELOREPUBLIKOVE = 'celorepublikove';

    const LOKALNE = 'lokalne';

    const __VALUE_OPTIONS = [
        self::CELOREPUBLIKOVE => 'Celorepublikově',
        self::LOKALNE => 'Lokálně',
    ];
}