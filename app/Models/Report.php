<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class Report extends Model
{
    use HasFactory;

    public const STATUS_OPEN = 'OPEN';
    public const STATUS_RESOLVED = 'RESOLVED';
    public const STATUS_DISMISSED = 'DISMISSED';

    public const RESOLUTION_DECISIONS = [
        self::STATUS_RESOLVED,
        self::STATUS_DISMISSED,
    ];

    protected $fillable = [
        'user_id',
        'game_ad_id',
        'reason',
        'status',
        'resolution_notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gameAd()
    {
        return $this->belongsTo('App\\Models\\GameAd', 'game_ad_id');
    }

    public function resolve(string $decision, ?string $resolutionNotes = null): void
    {
        if (! in_array($decision, self::RESOLUTION_DECISIONS, true)) {
            throw new InvalidArgumentException('Decision must be RESOLVED or DISMISSED.');
        }

        $this->status = $decision;
        $this->resolution_notes = $resolutionNotes;
        $this->save();
    }
}
