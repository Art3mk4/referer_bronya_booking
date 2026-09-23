<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\Referral\ReferralProgramService;
use App\Http\Requests\MasterRequest;
use App\Http\Requests\AttachReferralRequest;
use App\Services\Referral\ReferralService;
use App\Http\Resources\Referral\AttachedReferralResource;
use App\Http\Resources\Referral\MyReferralsCollection;
use App\Http\Resources\Referral\EarningsSummaryResource;

class ReferralController extends Controller
{
    
    public function attach(
        AttachReferralRequest $request,
        ReferralProgramService $service,
        ReferralService $referrals
      ): AttachedReferralResource
    {
        return new AttachedReferralResource(
            $service->attach(
                $request->currentMasterId(),
                $request->validated('code'),
                $referrals
            )
        );
    }

    public function my(MasterRequest $request, ReferralProgramService $service): MyReferralsCollection
    {
        return new MyReferralsCollection($service->my($request->currentMasterId()));
    }

    public function earnings(MasterRequest $request, ReferralProgramService $service): EarningsSummaryResource
    {
        return new EarningsSummaryResource($service->earnings($request->currentMasterId()));
    }
}
