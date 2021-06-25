<?php

namespace App\Models;

use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Builder;

class
Branch extends Model
{
    use ColumnTranslation, LogsActivity, SoftDeletes;

    /**
     * table name in db
     *
     * @var String
     */
    protected $table = "branches";

    protected static $logAttributes = [
        'name_ar',
        'name_en',
        'email',
        'address',
        'mailbox_number',
        'postal_code',
        'phone1',
        'status'
    ];

    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name_ar',
        'name_en',
        'country_id',
        'city_id',
        'area_id',
        'currency_id',
        'email',
        'address_ar',
        'address_en',
        'mailbox_number',
        'postal_code',
        'phone1',
        'phone2',
        'fax',
        'logo',
        'map',
        'vat_active',
        'vat_percent',
        'status',
        'shift_is_active',
        'tax_card',
    ];

    protected $casts = [
        'shift_is_active' => 'boolean'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function getLogoImgAttribute () {

        $filePath = storage_path('app/public/images/branches/' . $this->logo);

        if (File::exists($filePath)) {
            return asset('storage/images/branches/' . $this->logo);
        }

        return  asset('images/default.png');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'branches_roles');
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(function (Builder $builder) {
            if (auth()->check() && !authIsSuperAdmin()){
                $builder->where('id', auth()->user()->branch_id);
            }
        });

        static::created(
            function ($model) {
                $expenseTypePurchaseInvoice = ExpensesType::create(
                    [
                        "type_en" => 'purchase invoice',
                        "type_ar" => 'فاتوره مشتريات',
                        "status" => 1,
                        'branch_id' => $model->id,
                        'is_seeder' => 1,
                        "created_at" => now(),
                        "updated_at" => now(),
                    ]
                );
                $expenseTypeSalesInvoiceReturn = ExpensesType::create(
                    [
                        "type_en" => 'sales invoice return',
                        "type_ar" => 'مرتجعات فاتوره المبيعات',
                        "status" => 1,
                        'branch_id' => $model->id,
                        'is_seeder' => 1,
                        "created_at" => now(),
                        "updated_at" => now(),
                    ]
                );

                $expenseTypeAdvance = ExpensesType::create(
                    [
                        "type_en" => 'advances',
                        "type_ar" => 'السلف',
                        "status" => 1,
                        'branch_id' => $model->id,
                        'is_seeder' => 1,
                        "created_at" => now(),
                        "updated_at" => now(),
                    ]
                );

                $expenseTypeSalaries = ExpensesType::create(
                    [
                        "type_en" => 'salaries',
                        "type_ar" => 'مرتبات',
                        "status" => 1,
                        'branch_id' => $model->id,
                        'is_seeder' => 1,
                        "created_at" => now(),
                        "updated_at" => now(),
                    ]
                );

                ExpensesItem::create(
                    [
                        "item_ar" => 'مدفوعات فاتوره مشتريات',
                        "item_en" => 'purchase invoice Payments',
                        "status" => 1,
                        'branch_id' => $model->id,
                        'is_seeder' => 1,
                        "expense_id" => $expenseTypePurchaseInvoice->id,
                        "created_at" => now(),
                        "updated_at" => now(),
                    ]
                );

                ExpensesItem::create(
                    [
                        "item_ar" => 'مرتجعات فاتوره مبيعات',
                        "item_en" => 'sales invoice return Payments',
                        "status" => 1,
                        'branch_id' => $model->id,
                        'is_seeder' => 1,
                        "expense_id" => $expenseTypeSalesInvoiceReturn->id,
                        "created_at" => now(),
                        "updated_at" => now(),
                    ]
                );

                ExpensesItem::create(
                    [
                        "item_ar" => 'مدفوعات سلف',
                        "item_en" => 'advances Payments',
                        "status" => 1,
                        'is_seeder' => 1,
                        'branch_id' => $model->id,
                        "expense_id" => $expenseTypeAdvance->id,
                        "created_at" => now(),
                        "updated_at" => now(),
                    ]
                );

                ExpensesItem::create(
                    [
                        "item_ar" => 'مدفوعات مرتبات',
                        "item_en" => 'salaries Payments',
                        "status" => 1,
                        'branch_id' => $model->id,
                        'is_seeder' => 1,
                        "expense_id" => $expenseTypeSalaries->id,
                        "created_at" => now(),
                        "updated_at" => now(),
                    ]
                );
//
                $revenueTypePurchaseInvoice = RevenueType::create(
                    [
                        "type_en" => 'purchase invoice return',
                        "type_ar" => 'مرتجعات فاتوره شراء',
                        "status" => 1,
                        'branch_id' => $model->id,
                        'is_seeder' => 1,
                        "created_at" => now(),
                        "updated_at" => now(),
                    ]
                );

                $revenueTypeSalesInvoice = RevenueType::create(
                    [
                        "type_en" => 'sales invoice',
                        "type_ar" => 'فاتوره مبيعات',
                        "status" => 1,
                        'branch_id' => $model->id,
                        'is_seeder' => 1,
                        "created_at" => now(),
                        "updated_at" => now(),
                    ]
                );

                $revnueTypeAdvance = RevenueType::create(
                    [
                        "type_en" => 'advances',
                        "type_ar" => 'سلف',
                        "status" => 1,
                        'branch_id' => $model->id,
                        'is_seeder' => 1,
                        "created_at" => now(),
                        "updated_at" => now(),
                    ]
                );

                DB::table('revenue_items')->insert(
                    [
                        "item_ar" => 'مدفوعات مرتجعات فاتوره شراء',
                        "item_en" => 'purchase invoice return Payments',
                        "status" => 1,
                        'branch_id' => $model->id,
                        'is_seeder' => 1,
                        "revenue_id" => $revenueTypePurchaseInvoice->id,
                        "created_at" => now(),
                        "updated_at" => now(),
                    ]
                );

                DB::table('revenue_items')->insert(
                    [
                        "item_ar" => 'مدفوعات فاتوره بيع',
                        "item_en" => 'sales invoice Payments',
                        "status" => 1,
                        'branch_id' => $model->id,
                        'is_seeder' => 1,
                        "revenue_id" => $revenueTypeSalesInvoice->id,
                        "created_at" => now(),
                        "updated_at" => now(),
                    ]
                );

                DB::table('revenue_items')->insert(
                    [
                        "item_ar" => 'مدفوعات سلف',
                        "item_en" => 'advances Payments',
                        "status" => 1,
                        'branch_id' => $model->id,
                        'is_seeder' => 1,
                        "revenue_id" => $revnueTypeAdvance->id,
                        "created_at" => now(),
                        "updated_at" => now(),
                    ]
                );

                $revenueTypeCardInvoice = RevenueType::create(
                    [
                        "type_en" => 'card invoice',
                        "type_ar" => 'فاتوره صيانة',
                        "status" => 1,
                        'branch_id' => $model->id,
                        'is_seeder' => 1,
                        "created_at" => now(),
                        "updated_at" => now(),
                    ]
                );

                DB::table('revenue_items')->insert(
                    [
                        "item_ar" => 'مدفوعات فاتوره صيانة',
                        "item_en" => 'card invoice Payments',
                        "status" => 1,
                        'branch_id' => $model->id,
                        'is_seeder' => 1,
                        "revenue_id" => $revenueTypeCardInvoice->id,
                        "created_at" => now(),
                        "updated_at" => now(),
                    ]
                );

            }
        );
    }

    public function users(){
        return $this->hasMany(User::class)->withTrashed();
    }
}
