<?php declare(strict_types=1);


namespace Core\Enum;

class BlogPostStatus
{
    const NEED_REVIEW = 'need-review';

    const APPROVED = 'approved';

    const DECLINED = 'declined';

    const __DEFAULT = self::NEED_REVIEW;

    const __VALUE_OPTIONS = [
        self::NEED_REVIEW => 'Odesláno ke schválení',
        self::APPROVED => 'Schváleno',
        self::DECLINED => 'Zamítnuto',
    ];
}