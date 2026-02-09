<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BioLocation extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Attendance Relationship
    public function attendance(): HasMany
    {
        return $this->HasMany(Attendance::class, 'id');
    }

    public function attendance_specific_day() : HasMany
    {
        return $this->HasMany(
            Attendance::class,
            'location_name',
            'location');
    }

    // Company Relationship
    public function company(): BelongsTo
    {
        return $this->BelongsTo(Company::class, 'id');
    }
}
