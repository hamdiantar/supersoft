<?php

namespace App\Models;

use App\OpeningStockBalance\Models\OpeningBalanceItems;
use App\Scopes\BranchScope;
use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Store extends Model
{
    use ColumnTranslation, SoftDeletes;

    protected $table = 'stores';

    protected $fillable = [
        'name_ar',
        'name_en',
        'employees_ids',
        'store_phone',
        'store_address',
        'note',
        'branch_id',
    ];

    protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new BranchScope());
    }

    public function setEmployeesIdsAttribute($value)
    {
        if (!is_null($value)) {
            $this->attributes['employees_ids'] = serialize($value);
        } else {
            $this->attributes['employees_ids'] = $value;
        }
    }

    public function getEmployeesIdsAttribute($value)
    {
        $ids = (!is_null($value) && !is_null(unserialize($value))) ? unserialize($value) : [];
        return EmployeeData::whereIn('id', $ids)->get();
    }

    public static function getEmployees()
    {
        $employeesIds = DB::table('stores')->get('employees_ids');
        $array = [];
        if (count($employeesIds) > 0) {
            foreach ($employeesIds as $ids) {
                if (!is_null($ids->employees_ids)) {
                    foreach (unserialize($ids->employees_ids) as $id) {
                        array_push($array, $id);
                    }
                }
            }
        }
        return EmployeeData::whereIn('id', array_unique($array))->get();
    }

    public function parts () {

        return $this->belongsToMany(Part::class, 'part_store')->withTimestamps()->withPivot('quantity');
    }

    function openingBalanceItem()
    {
        return $this->hasMany(OpeningBalanceItems::class);
    }

    public function damagedStockItem()
    {
        return $this->hasMany(DamagedStockItem::class);
    }

    public function settlementItem()
    {
        return $this->hasMany(SettlementItem::class);
    }

    public function concessionItem()
    {
        return $this->hasMany(ConcessionItem::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function storeEmployeeHistories(): HasMany
    {
        return $this->hasMany(StoreEmployeeHistory::class, 'store_id');
    }
}
