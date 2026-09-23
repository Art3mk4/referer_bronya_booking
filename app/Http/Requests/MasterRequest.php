<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\ApiException;
use App\Models\Master;

class MasterRequest extends FormRequest
{
    public function currentMasterId(): Master
    {
        $master = $this->attributes->get('current_master');

        return $master instanceof Master ? $master : throw ApiException::masterNotResolved();
    }

}