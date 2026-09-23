<?php

declare(strict_types=1);

namespace App\Services\Referral;

use App\Exceptions\ApiException;
use App\Models\Master;
use App\Models\Referral;
use App\Models\ReferralEarning;
use App\Services\Referral\ReferralService;
use Illuminate\Database\Eloquent\Collection;
use App\Http\ValueObjects\Referral\EarningsSummary;


class ReferralProgramService
{
    
    public function attach(Master $referred, string $code, ReferralService $referrals): Referral
    {
        if ($referred->referral_code === $code) {
            throw ApiException::selfReferal();
        }

        $referral = $referrals->registerReferral($referred, $code) ?? throw ApiException::codeNotFound();
        if ($referral->referredMaster->referral_code !== $code) {
            throw ApiException::alreadyAttached();
        }

        return $referral;
    }

    public function my(Master $referrer): Collection
    {
        return Referral::query()->where('referrer_master_id', $referrer->id)
            ->with('referredMaster')
            ->withSum('earnings', 'amount')
            ->orderBy('id')
            ->get();
    }

    public function earnings(Master $referrer): EarningsSummary
    {
        $byStatus = ReferralEarning::query()
            ->where('referrer_master_id', $referrer->id)
            ->selectRaw('status, SUM(amount) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return new EarningsSummary(
            total: (int) $byStatus->sum(),
            pending: (int) ($byStatus[ReferralEarning::STATUS_PENDING] ?? 0),
            paid: (int) ($byStatus[ReferralEarning::STATUS_PAID] ?? 0),
            countedReferrals: Referral::query()->where('referrer_master_id', $referrer->id)
            ->active()->count(),
        );
    }
}