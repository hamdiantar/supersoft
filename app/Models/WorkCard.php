<?php

namespace App\Models;

use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Spatie\Activitylog\Traits\LogsActivity;

class WorkCard extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = ['card_number','branch_id','customer_id','created_by','receive_car_status','status',
        'receive_car_date','receive_car_time','delivery_car_status','delivery_car_date','delivery_car_time',
        'car_id','note'];

    protected $table = 'work_cards';

    protected static $logAttributes = ['card_number', 'receive_car_status','status', 'delivery_car_status','receive_car_date',
        'delivery_car_date'];

    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new BranchScope());
    }

    public function branch(){
        return $this->belongsTo(Branch::class, 'branch_id')->withTrashed();
    }

    public function user(){
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id')->withTrashed();
    }

    public function car(){
        return $this->belongsTo(Car::class, 'car_id')->withTrashed();
    }

    public function cardInvoice(){
        return $this->hasOne(CardInvoice::class,'work_card_id');
    }

    public function followUps(){
        return $this->hasMany(FollowUp::class,'work_card_id');
    }

    public function delete()
    {
        DB::transaction(function()
        {
            $card_invoice = $this->cardInvoice;

            if($card_invoice){

                foreach($card_invoice->types as $type){

                    if($type->items){

                        if($type->type == 'Part'){
                            $this->resetPurchaseInvoiceQty($type);
                        }

                        foreach($type->items as $item){
                            $item->delete();
                        }
                    }

                    if ($type->cardInvoiceWinchRequest) {

                        $type->cardInvoiceWinchRequest->delete();
                    }

                    $type->delete();
                }

                $this->deleteImages($card_invoice);

                $this->removeBulkBalance($card_invoice->RevenueReceipts()->get());

                if($card_invoice->RevenueReceipts){

                    $card_invoice->RevenueReceipts()->delete();
                }

                $card_invoice->delete();
            }

            parent::delete();
        });
    }

    public function deleteImages($card_invoice){

        $maintenance_detections = $card_invoice->maintenanceDetections;

        if ($maintenance_detections) {

            foreach($maintenance_detections as $maintenance_detection){

                $images = json_decode($maintenance_detection->pivot->images);

                if ($images) {

                    foreach($images as $image){

                        $path = public_path('storage/images/maintenance_parts/'.$image);

                        if(File::exists($path)){
                            File::delete($path);
                        }
                    }
                }
            }

            $card_invoice->maintenanceDetections()->detach();
            $card_invoice->maintenanceDetectionTypes()->detach();
        }
    }

    public function resetPurchaseInvoiceQty($type)
    {
        foreach($type->items as $item){

            $part = $item->part;

            if($part){
                $part->quantity += $item->qty;
                $part->save();
            }

            $purchaseInvoice = $item->purchaseInvoice;

            if($purchaseInvoice){

                $purchase_item = $purchaseInvoice->items()->where('part_id', $item->model_id)->first();

                if($purchase_item){
                    $purchase_item->purchase_qty += $item->qty;
                    $purchase_item->save();
                }
            }
        }
    }

    public function removeBulkBalance(Collection $collection)
    {
        foreach ($collection as $col) {
            if ($col->locker_id !== null) {
                $this->removeBalanceFromLocker($col);
            }
            if ($col->account_id !== null) {
                $this->removeBalanceFromAccount($col);
            }
        }
    }

    public function removeBalanceFromLocker(RevenueReceipt $receipt)
    {
        $locker = Locker::findOrFail($receipt->locker_id);
        $locker->update([
            'balance' => ($locker->balance  -  $receipt->cost)
        ]);
    }

    public function removeBalanceFromAccount(RevenueReceipt $receipt)
    {
        $account = Account::findOrFail($receipt->account_id);
        $account->update([
            'balance' => ($account->balance  - $receipt->cost)
        ]);
    }

    function scopeGlobalCheck($query ,$request) {
        if ($request->has('global_check')) {
            $key = $request->global_check;

            $customers = Customer::where(function ($q) use ($key) {
                $q->orWhere('name_en' ,'like' ,"%$key%");
                $q->orWhere('name_ar' ,'like' ,"%$key%");
                $q->orWhere('phone1' ,'like' ,"%$key%");
                $q->orWhere('phone2' ,'like' ,"%$key%");
            })->pluck('id')->toArray();

            $cars_customers = Car::where('plate_number' ,'like' ,"%$key%")->pluck('customer_id')->toArray();
            $card_invoices = CardInvoice::where('invoice_number' ,'like' ,"%$key%")->pluck('work_card_id')->toArray();

            $query->orWhere('card_number' ,'like' ,"%$key%");

            if (!empty($card_invoices)) $query->orWhereIn('id' ,$card_invoices);
            if (!empty($cars_customers)) $query->orWhereIn('customer_id' ,$cars_customers);
            if (!empty($customers)) $query->orWhereIn('customer_id' ,$customers);
        }
        return $query;
    }
}
