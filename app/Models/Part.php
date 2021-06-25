<?php

namespace App\Models;

use App\OpeningStockBalance\Models\OpeningBalanceItems;
use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\LogsActivity;

class Part extends Model
{
    use SoftDeletes, LogsActivity;

    protected static $logAttributes = ['name_ar', 'name_en', 'quantity'];

    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    protected $dates = ['deleted_at'];

    protected $fillable =
        [
            'name_en',
            'name_ar',
            'spare_part_unit_id',
            'suppliers_ids',
            'description',
            'img',
            'quantity',
            'status',
            'library_path',
            'branch_id',
            'is_service',
            'part_in_store',
            'reviewable',
            'taxable'
        ];

    protected $table = 'parts';

    public function getActiveAttribute()
    {
        return $this->status == 1 ? __('Active') : __('inActive');
    }

    public function sparePartsType()
    {
        return $this->belongsTo(SparePart::class, 'spare_part_type_id')->withTrashed();
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id')->withTrashed();
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new BranchScope());
    }


    public function sparePartsUnit()
    {
        return $this->belongsTo(SparePartUnit::class, 'spare_part_unit_id')->withTrashed();
    }

    public function getNameAttribute()
    {
        return app()->getLocale() == 'ar' ? $this['name_ar'] : $this['name_en'];
    }

    public function alternative()
    {
        return $this->belongsToMany(Part::class, 'alternative_parts', 'part_id', 'alternative_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id')->withTrashed();
    }

    public function items()
    {
        return $this->hasMany(PurchaseInvoiceItem::class, 'part_id');
    }

    public function files()
    {
        return $this->hasMany(PartLibrary::class, 'part_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id')->withTrashed();
    }

    public function stores()
    {
        return $this->belongsToMany(Store::class, 'part_store')->withTimestamps()->withPivot('quantity');
    }

    public function spareParts()
    {
        return $this->belongsToMany(SparePart::class, 'part_spare_part', 'part_id', 'spare_part_type_id');
    }

    public function prices()
    {
        return $this->hasMany(PartPrice::class, 'part_id');
    }

    public function getImageAttribute()
    {
        return $this->img ? url('storage/images/parts/' . $this->img) : url('default-images/defualt.png');
    }

    public function taxes()
    {
        return $this->belongsToMany(TaxesFees::class, 'part_taxes_fees');
    }

    public function setSuppliersIdsAttribute($value)
    {
        if (!is_null($value)) {
            $this->attributes['suppliers_ids'] = serialize($value);
        } else {
            $this->attributes['suppliers_ids'] = $value;
        }
    }

    public function getSuppliersIdsAttribute($value)
    {
        $ids = !is_null($value) ? unserialize($value) : [];
        return Supplier::whereIn('id', $ids)->get();
    }

    public static function getSuppliers()
    {
        $suppliersIds = DB::table('parts')->get('employees_ids');
        $array = [];
        if (count($suppliersIds) > 0) {
            foreach ($suppliersIds as $ids) {
                if (!is_null($ids->suppliers_ids)) {
                    foreach (unserialize($ids->suppliers_ids) as $id) {
                        array_push($array, $id);
                    }
                }
            }
        }
        return Supplier::whereIn('id', array_unique($array))->get();
    }

    function openingBalanceItems()
    {
        return $this->hasMany(OpeningBalanceItems::class, 'part_id');
    }

    public function concessionItems()
    {
        return $this->hasMany(ConcessionItem::class, 'part_id');
    }

    public function damagedStockItems()
    {
        return $this->hasMany(DamagedStockItem::class, 'part_id');
    }

    public function settlementItems()
    {
        return $this->hasMany(SettlementItem::class, 'part_id');
    }

    public function getFirstPriceQuantityAttribute()
    {
        $firstPrice = $this->prices->first();

        return $firstPrice ? $firstPrice->quantity : 1;
    }

    public function getFirstPriceDamagedPriceAttribute()
    {
        $firstPrice = $this->prices->first();

        return $firstPrice ? $firstPrice->damage_price : 0;
    }

    public function getFirstPriceSegmentsAttribute()
    {
        $firstPrice = $this->prices->first();
        return $firstPrice ? $firstPrice->partPriceSegments : [];
    }

    public function getFirstStoreQuantityAttribute()
    {
        $firstStore = $this->stores->first();

        if ($firstStore) {
            return $firstStore->pivot ? $firstStore->pivot->quantity : 0;
        }

        return 0;
    }

    public function getDefaultPurchasePriceAttribute()
    {
        $firstPrice = $this->prices->first();
        return $firstPrice ? $firstPrice->purchase_price : 0;
    }

    public function getDefaultDamagePriceAttribute()
    {
        $firstPrice = $this->prices->first();

        if (!$firstPrice) {
            return 0;
        }

        if ($firstPrice->partPriceSegments->count()) {
            return $firstPrice->partPriceSegments->first() ? $firstPrice->partPriceSegments->first()->purchase_price : 0;
        }

        return $firstPrice->damage_price;
    }
}
