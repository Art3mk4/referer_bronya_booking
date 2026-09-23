<?php

namespace App\Http\ValueObjects\Referral;

class EarningsSummary {
    public function __construct(
        public int $total,
        public int $pending,
        public int $paid, 
        public int $countedReferrals,
    )
    {
    }
}