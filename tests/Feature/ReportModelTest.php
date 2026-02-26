<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class ReportModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_report_can_be_resolved_with_valid_decision(): void
    {
        $user = User::factory()->create();

        $game = Game::create([
            'title' => 'Test Game',
            'description' => 'Test Description',
            'release_date' => '2026-01-01',
            'cover_image' => 'cover.jpg',
            'genre' => 'ACTION',
            'platform' => 'PC',
        ]);

        $report = Report::create([
            'user_id' => $user->id,
            'game_id' => $game->id,
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

        $game = Game::create([
            'title' => 'Test Game',
            'description' => 'Test Description',
            'release_date' => '2026-01-01',
            'cover_image' => 'cover.jpg',
            'genre' => 'ACTION',
            'platform' => 'PC',
        ]);

        $report = Report::create([
            'user_id' => $user->id,
            'game_id' => $game->id,
            'reason' => 'Spam report',
            'status' => Report::STATUS_OPEN,
            'resolution_notes' => null,
        ]);

        $this->expectException(InvalidArgumentException::class);

        $report->resolve(Report::STATUS_OPEN);
    }
}
