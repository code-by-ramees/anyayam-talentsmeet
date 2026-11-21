<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'section_id',
        'category_id',
        'stage_type',
        'type',
        'max_participants',
        'status',
    ];

    protected $casts = [
        'max_participants' => 'integer',
    ];

    public function isStage(): bool
    {
        return $this->stage_type === 'stage';
    }

    public function getStageLabelAttribute(): string
    {
        return $this->stage_type === 'non_stage' ? 'Non-Stage' : 'Stage';
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function registrations()
    {
        return $this->hasMany(ProgramRegistration::class);
    }

    public function publishedResults()
    {
        return $this->hasMany(Result::class)->where('is_published', true);
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isResultsPublished(): bool
    {
        return $this->results()->where('is_published', true)->exists();
    }
}
