<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;



class Attendance extends Model
{
    use HasFactory;

    protected $guarded = [];

    // BioLocation Relationship
    public function biolocations(): BelongsTo
    {
        return $this->belongsTo(BioLocation::class, 'bio_location_id');
    }

    public function oms_employee(): BelongsTo
    {
        return $this->belongsTo(oms_employee::class, 'emp_id' ,'emp_id');
    }

    public function oms_users(): BelongsTo
    {
        return $this->belongsTo(oms_users::class, 'emp_id', 'emp_id');
    }

}
