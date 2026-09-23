<?php

declare(strict_types=1);

namespace App\Http\Resources\Referral;

use App\Http\Resources\UnwrappedResourceCollection;
use Illuminate\Http\Request;

class MyReferralsCollection extends UnwrappedResourceCollection
{

    public $collects = MyReferralItemResource::class;

    public function toArray(Request $request) {
        return [
            'referrals' => parent::toArray($request)
        ];
    }
}
