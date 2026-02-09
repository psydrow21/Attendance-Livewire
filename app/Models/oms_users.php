<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class oms_users extends Model
{
    use HasFactory;

    protected $connection = 'oms_mysql';

    protected $table = 'users';

    public function users_attendance(): HasMany
    {
        return $this->HasMany(Attendance::class, 'emp_id', 'emp_id');
    }

}
