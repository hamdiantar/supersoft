<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FollowUp extends Model
{
    protected $fillable = ['work_card_id','created_by','name','kilo_number','date','notes','status'];
    protected $table = 'follow_ups';
}
