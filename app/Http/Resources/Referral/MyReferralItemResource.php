<?php

declare(strict_types=1);

namespace App\Http\Resources\Referral;

use App\Models\Referral;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use LogicException;

class MyReferralItemResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        $referred = $this->resource->referredMaster ?? throw new LogicException("Referral { $this->resource->id } has no referred master");

        return [
            'referral_id' => $this->resource->id,
            'master_id' => $referred->id,
            'master_name' => $referred->name,
            'attached_at' => $this->resource->created_at,
            'counted' => $this->resource->status === Referral::STATUS_REWARDED,
            'earned' => (int) $this->resource->earnings_sum_amount,
        ];
    }
}