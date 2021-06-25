<?php

namespace App\Models;

use App\Scopes\BranchScope;
use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Service extends Model
{
    use SoftDeletes, ColumnTranslation, LogsActivity;

    protected static $logAttributes = ['name_ar','name_en','price','hours','minutes'];

    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    protected $dates = ['deleted_at'];

    protected $fillable = ['type_id','branch_id','name_en','name_ar','description_en','description_ar','status','price',
        'hours','minutes'];

    protected $table = 'services';

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new BranchScope());
    }

    public function branch(){
        return $this->belongsTo(Branch::class, 'branch_id')->withTrashed();
    }

    public function ServiceType(){
        return $this->belongsTo(ServiceType::class, 'type_id')->withTrashed();
    }

    public function getActiveAttribute(){
        return $this->status == 1? __('Active'): __('inActive');
    }
}
