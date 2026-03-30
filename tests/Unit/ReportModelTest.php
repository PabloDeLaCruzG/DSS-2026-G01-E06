<?php

namespace Tests\Unit;

use App\Models\GameAd;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class ReportModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_report_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $gameAd = GameAd::factory()->create();

        $report = Report::create([
            'user_id' => $user->id,
            'game_ad_id' => $gameAd->id,
            'reason' => 'Inappropriate content',
            'status' => Report::STATUS_OPEN,
            'resolution_notes' => null,
        ]);

        $this->assertInstanceOf(User::class, $report->user);
        $this->assertEquals($user->id, $report->user->id);
    }

    public function test_report_resolve_method_changes_status_to_resolved(): void
    {
        $user = User::factory()->create();
        $gameAd = GameAd::factory()->create();

        $report = Report::create([
            'user_id' => $user->id,
            'game_ad_id' => $gameAd->id,
            'reason' => 'Inappropriate content',
            'status' => Report::STATUS_OPEN,
            'resolution_notes' => null,
        ]);

        $report->resolve(Report::STATUS_RESOLVED, 'Checked and resolved by moderator.');
        $report->refresh();

        $this->assertSame(Report::STATUS_RESOLVED, $report->status);
        $this->assertSame('Checked and resolved by moderator.', $report->resolution_notes);
    }

    public function test_report_resolution_rejects_invalid_decision(): void
    {
        $user = User::factory()->create();
        $gameAd = GameAd::factory()->create();

        $report = Report::create([
            'user_id' => $user->id,
            'game_ad_id' => $gameAd->id,
            'reason' => 'Spam report',
            'status' => Report::STATUS_OPEN,
            'resolution_notes' => null,
        ]);

        $this->expectException(InvalidArgumentException::class);

        $report->resolve(Report::STATUS_OPEN);
    }
}
