<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'section_id',
        'name',
        'uid',
        'chest_number',
        'class_year',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function registrations()
    {
        return $this->hasMany(ProgramRegistration::class);
    }
}
