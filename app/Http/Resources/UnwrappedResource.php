<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

abstract class UnwrappedResource extends JsonResource
{
    public static $wrap = null;
}
