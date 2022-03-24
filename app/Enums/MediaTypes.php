<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static ICONS()
 * @method static static IMAGES()
 * @method static static DOCUMENTS()
 * @method static static AUDIOS()
 * @method static static VIDEOS()
 * @see https://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types
 */
final class MediaTypes extends Enum
{
    public const ICONS = [
        'image/png',
        'image/jpeg',
        'image/svg+xml',
    ];
    public const IMAGES = [
        'image/png',
        'image/jpeg',
    ];
    public const DOCUMENTS = [
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/pdf',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/msword',
    ];
    public const AUDIOS = [];
    public const VIDEOS = [];
}
