<?php

namespace App\Models;

use App\Traits\UUID;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseModel;

/**
 * @property mixed model
 */
class Media extends BaseModel
{
    use UUID;
}
