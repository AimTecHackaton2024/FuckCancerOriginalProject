<?php declare(strict_types=1);


namespace Frontend\Constants;

class OrganizationFormConstants
{
    /**
     * Perex maximum text length
     */
    const PEREX_MAX_LENGTH = 250;

    /**
     * Allowed file formats for photos
     */
    const PHOTO_FILE_FORMATS = ['jpg', 'jpeg'];

    /**
     * Maximum file size for photos
     *
     * Current value: 10 MB
     */
    const PHOTO_FILE_SIZE = 1024 * 1024 * 10;
}