<?php

namespace App\Models;

use App\Scopes\CarsScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;
use Spatie\Activitylog\Traits\LogsActivity;

class Car extends Model
{
    use LogsActivity, SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'cars';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'type_id',
        'plate_number',
        'Chassis_number',
        'speedometer',
        'barcode',
        'color',
        'image',
        'customer_id',
        'model_id',
        'motor_number',
        'company_id',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class)->withTrashed();
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CarsScope());
    }

    public function carType(): BelongsTo
    {
        return $this->belongsTo(CarType::class, 'type_id')->withTrashed();
    }

    public function carModel(): BelongsTo
    {
        return $this->belongsTo(CarModel::class)->withTrashed();
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class)->withTrashed();
    }

    public function getImgAttribute() {

        $path = public_path('storage/images/cars/');

        if ($this->image && File::exists($path.$this->image)){

            return  asset('storage/images/cars/'.$this->image);

        }else {

            return  asset('images/default.png');
        }
    }
}
