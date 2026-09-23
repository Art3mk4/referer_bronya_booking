<?php

declare(strict_types=1);

namespace App\Http\Resources\Referral;

use App\Http\Resources\UnwrappedResource;
use Illuminate\Http\Request;

class EarningsSummaryResource extends UnwrappedResource
{
    public function toArray(Request $request)
    {
        return [
            'total' => $this->resource->total,
            'pending' => $this->resource->pending,
            'paid' => $this->resource->paid,
            'counted_referrals' => $this->resource->countedReferrals
        ];
    }
}
