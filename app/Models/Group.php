<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'code',
        'description',
        'color',
        'allow_member_registration',
        'status',
    ];

    protected $casts = [
        'allow_member_registration' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function members()
    {
        return $this->hasMany(Member::class);
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

    public function getTotalMarksAttribute(): int
    {
        return (int) $this->publishedResults()->sum('marks');
    }

    public function getFirstPrizesCountAttribute(): int
    {
        return $this->publishedResults()->where('position', 'first')->count();
    }

    public function getSecondPrizesCountAttribute(): int
    {
        return $this->publishedResults()->where('position', 'second')->count();
    }

    public function getThirdPrizesCountAttribute(): int
    {
        return $this->publishedResults()->where('position', 'third')->count();
    }
}
