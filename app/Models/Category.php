<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'first_marks',
        'second_marks',
        'third_marks',
    ];

    protected $casts = [
        'first_marks' => 'integer',
        'second_marks' => 'integer',
        'third_marks' => 'integer',
    ];

    public function programs()
    {
        return $this->hasMany(Program::class);
    }

    public function getMarksForPosition(string $position): int
    {
        return match (strtolower($position)) {
            'first' => $this->first_marks,
            'second' => $this->second_marks,
            'third' => $this->third_marks,
            default => 0,
        };
    }
}
