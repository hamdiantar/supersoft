<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class Module extends Model
{
    protected $fillable = ['name','display_name'];

    protected $table = 'modules';

    public function permissions(){
        return $this->belongsToMany(Permission::class ,'module_permissions');
    }
}
