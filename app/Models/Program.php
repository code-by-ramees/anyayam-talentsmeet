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
        'type',
        'max_participants',
        'status',
        'venue',
        'scheduled_at',
    ];

    protected $casts = [
        'max_participants' => 'integer',
        'scheduled_at' => 'datetime',
    ];

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
