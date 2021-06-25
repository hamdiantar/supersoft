<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerContact extends Model
{
    protected $fillable = ['customer_id','phone_1','phone_2','address','name'];

    protected $table = 'customer_contacts';

    public function customer() {

        return $this->belongsTo(Customer::class, 'customer_id')->withTrashed();
    }
}
