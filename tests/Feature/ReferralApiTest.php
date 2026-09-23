<?php

namespace Tests\Feature;

use App\Models\Master;
use App\Models\Referral;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Override;
use Tests\TestCase;

class ReferralApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    private function as(int $masterId): static
    {
        return $this->withHeaders([
            'X-Master-Id' => (string) $masterId,
            'Accept' => 'application/json']
        );
    }

    public function test_attach_validates_code()
    {
        $this->as(2)->postJson('/api/referrals/attach', [])->assertStatus(422);
        $this->as(2)
        ->postJson('/api/referrals/attach', [
            'code' => '',
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['code']);

        $this->as(2)
        ->postJson('/api/referrals/attach', [
            'code' => 12345,
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['code']);

        $this->as(2)
            ->postJson('/api/referrals/attach', [
                'code' => 'UNKNOWN999',
            ])
            ->assertStatus(404);
    }

    public function test_attach_is_idempotent()
    {
        $lena = Master::where('referral_code', 'LENA77')->firstOrFail();
        $new = Master::create(['name' => 'New', 'referral_code' => 'NEW01']);

        $first = $this->as($new->id)->postJson('/api/referrals/attach', [
            'code' => 'MASHA10'
        ])->assertOk();

        $second = $this->as($new->id)->postJson('/api/referrals/attach', [
            'code' => 'MASHA10'
        ])->assertStatus(200);

        $this->assertSame($first->json('referral_id'), $second->json('referral_id'));

        $this->assertSame(1, Referral::where('referred_master_id', $new->id)->count());

        $second = $this->as($lena->id)->postJson('/api/referrals/attach', [
            'code' => $new->referral_code
        ])->assertStatus(200);
    }

    public function test_my_reports_counted_flag_and_earned_amount()
    {
        $response = $this->as(1)->getJson('/api/referrals/my')
        ->assertOk();

        $response->assertJsonCount(4, 'referrals');
    }

    public function test_earnings_summary_matches_stored()
    {
        $reward = 3000 * config('referral.percent');
        $this->as(1)->getJson('/api/referrals/earnings')
        ->assertOk()
        ->assertExactJson([
            'total' => $reward,
            'pending' => $reward,
            'paid' => 0,
            'counted_referrals' => 1,
        ]);
    }
}