<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerRequest extends Model
{
    protected $fillable = ['name','phone','address','username','password', 'branch_id', 'status', 'reject_reason', 'email', 'provider'];

    protected $table = 'customer_requests';
}
