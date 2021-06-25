<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StoreEmployeeHistory extends Model
{
    /**
     * @var string
     */
    protected $table = 'store_employee_histories';

    /**
     * @var string[]
     */
    protected $fillable = [
        'store_id',
        'employee_id',
        'start',
        'end',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(EmployeeData::class, 'employee_id');
    }
}
