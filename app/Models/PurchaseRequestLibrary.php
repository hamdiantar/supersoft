<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseRequestLibrary extends Model
{
    protected $fillable = ['name','purchase_request_id','file_name','extension'];

    protected $table = 'purchase_request_libraries';

    public function purchaseRequest () {

        return $this->belongsTo(PurchaseRequest::class, 'purchase_request_id');
    }
}
