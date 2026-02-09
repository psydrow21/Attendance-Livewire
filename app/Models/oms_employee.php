<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class oms_employee extends Model
{
    use HasFactory;

    protected $connection = 'oms_mysql';

    protected $table = 'employee_list';

    public function attendance(): HasMany
    {
        return $this->HasMany(Attendance::class, 'emp_id', 'emp_id');
    }

}
