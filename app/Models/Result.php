<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id',
        'group_id',
        'member_id',
        'participant_name',
        'position',
        'marks',
        'remarks',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'marks' => 'integer',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function getPositionBadgeClass(): string
    {
        return match ($this->position) {
            'first' => 'badge bg-warning text-dark border border-warning',
            'second' => 'badge bg-secondary text-white',
            'third' => 'badge bg-danger-subtle text-danger border border-danger-subtle',
            default => 'badge bg-light text-dark',
        };
    }

    public function getPositionLabel(): string
    {
        return match ($this->position) {
            'first' => '1st Prize',
            'second' => '2nd Prize',
            'third' => '3rd Prize',
            default => ucfirst($this->position),
        };
    }

    /**
     * Calculate Individual Score Leaderboard from published individual-event results.
     */
    public static function getIndividualLeaderboard(?string $sectionName = null, int $limit = 50)
    {
        $query = static::with(['member.section', 'member.group', 'group', 'program.section'])
            ->where('is_published', true)
            ->whereNotNull('member_id')
            ->whereHas('program', fn($q) => $q->where('type', 'individual'));

        if ($sectionName) {
            $query->whereHas('member.section', fn($q) => $q->where('name', $sectionName));
        }

        $results = $query->get();

        $grouped = $results->groupBy('member_id')->map(function ($memberResults) {
            $firstRes = $memberResults->first();
            $member = $firstRes->member;
            $group = $member?->group ?? $firstRes->group;

            $totalMarks = (int) $memberResults->sum('marks');
            $firstCount = $memberResults->where('position', 'first')->count();
            $secondCount = $memberResults->where('position', 'second')->count();
            $thirdCount = $memberResults->where('position', 'third')->count();

            return [
                'member' => $member,
                'group' => $group,
                'total_marks' => $totalMarks,
                'first_prizes' => $firstCount,
                'second_prizes' => $secondCount,
                'third_prizes' => $thirdCount,
                'total_prizes' => $firstCount + $secondCount + $thirdCount,
            ];
        })->filter(fn($item) => $item['member'] !== null);

        $sorted = $grouped->sort(function ($a, $b) {
            if ($a['total_marks'] !== $b['total_marks']) {
                return $b['total_marks'] <=> $a['total_marks'];
            }
            if ($a['first_prizes'] !== $b['first_prizes']) {
                return $b['first_prizes'] <=> $a['first_prizes'];
            }
            if ($a['second_prizes'] !== $b['second_prizes']) {
                return $b['second_prizes'] <=> $a['second_prizes'];
            }
            if ($a['third_prizes'] !== $b['third_prizes']) {
                return $b['third_prizes'] <=> $a['third_prizes'];
            }
            return strcmp($a['member']->name ?? '', $b['member']->name ?? '');
        })->values();

        if ($limit > 0) {
            $sorted = $sorted->take($limit);
        }

        return $sorted->map(function ($item, $index) {
            $item['rank'] = $index + 1;
            return $item;
        });
    }
}
