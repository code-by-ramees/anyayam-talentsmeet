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
}
