<?php

namespace App\Model;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;

class CustomerReservation extends Model
{
    protected $fillable = [
        'customer_id' ,'status' ,'event_date' ,'event_time' ,'customer_comment' ,'event_title' ,'admin_comment'
    ];

    function customer() {
        return $this->belongsTo(Customer::class ,'customer_id');
    }

    function get_color() {
        switch($this['status']) {
            case 'approved':
                return '#00bf4f';
            case 'rejected':
                return '#ea4335';
            default:
                return '#fbbc05';
        }
    }
}
