<?php

declare(strict_types=1);

namespace App\Http\Requests;

class AttachReferralRequest extends MasterRequest
{

    public function rules(): array
    {
        return [
            'code' => ['required', 'string']
        ];
    }
}