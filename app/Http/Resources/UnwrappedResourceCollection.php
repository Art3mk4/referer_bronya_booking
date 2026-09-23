<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

abstract class UnwrappedResourceCollection extends ResourceCollection
{
    public static $wrap = null;
}