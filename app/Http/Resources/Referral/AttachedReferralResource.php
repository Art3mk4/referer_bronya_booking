<?php

declare(strict_types=1);

namespace App\Http\Resources\Referral;

use App\Http\Resources\UnwrappedResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AttachedReferralResource extends UnwrappedResource
{

    public function toArray(Request $request): array
    {
        return [
            'referral_id' => $this->resource->id,
            'referrer_id' => $this->resource->referrerMaster->id,
            'referrer_name' => $this->resource->referrerMaster->name,
            'status' => $this->resource->status,
            'attached_at' => $this->resource->created_at
        ];
    }

    public function withResponse(Request $request, JsonResponse $response)
    {
        $response->setStatusCode(200);
    }
}