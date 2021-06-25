<?php
namespace App\AccountingModule\Models;

use App\Scopes\OldBranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountsTree extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'accounts_tree_id' ,'name_ar' ,'name_en' ,'tree_level' ,
        'account_nature' ,'account_type_name_ar',
        'account_type_name_en' ,'code' ,'branch_id' ,'custom_type'
        // custom_type => 1 : Budget ,2 : Income List, 3 : Trading Account
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new OldBranchScope());
    }

    function account_tree_branches() {
        return $this->hasMany(AccountsTree::class ,'accounts_tree_id');
    }

    function account_tree_parent() {
        return $this->belongsTo(AccountsTree::class ,'accounts_tree_id');
    }

    function is_deleteable() {
        if ($this->account_tree_branches->first()) return false;
        return $this->tree_level != 0;
    }

    function is_editable() {
        return $this->tree_level != 0;
    }

    function daily_restiction_records() {
        return $this->hasMany(DailyRestrictionTable::class ,'accounts_tree_id');
    }

    function relations() {
        return $this->hasMany(AccountRelation::class ,'accounts_tree_id');
    }

    static function update_tree_codes() {
        $count = 1;
        
        static::where('accounts_tree_id' ,0)->where('tree_level' ,0)->orderBy('id' ,'asc')
        ->chunk(10 ,function ($rows) use (&$count) {
            foreach($rows as $row) {
                $row->update(['code' => $count]);
                self::update_account_code($row ,$count);
                $count++;
            }
        });
    }

    private static function update_account_code($row ,$depth) {
        $count = 1;
        foreach ($row->account_tree_branches as $child) {
            $child->update(['code' => $depth.'.'.$count]);
            if ($child->account_tree_branches) {
                self::update_account_code($child ,$depth.'.'.$count);
            }
            $count++;
        }
    }

    function is_type_editable() {
        return $this->daily_restiction_records()->first() ? false : true;
    }
}
