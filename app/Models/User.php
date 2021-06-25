<?php

namespace App\Models;

use App\Scopes\BranchScope;
use App\Scopes\UsersScope;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes, HasRoles, LogsActivity;

    protected static $logAttributes = ['name', 'email','phone','username','status','super_admin' ,'theme'];

    protected static $logOnlyDirty = true;

    public static function boot() {
        parent::boot();
        self::deleting(function($user) {
            $loggedActivity = Activity::with('causer')->where('causer_id', $user->id);
            foreach ($loggedActivity->get() as $activity) {
                $activity->delete();
            }
            $user->activities()->each(function($activity) {
                $activity->delete();
            });
        });
    }

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
        'name', 'email', 'password','phone','username','status','super_admin','branch_id', 'image', 'is_admin_branch'
    ];

    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopeBranch($query)
    {
        if(!authIsSuperAdmin()){
            return $query->where('branch_id', auth()->user()->branch_id);
        }
    }

    public function getActiveAttribute(){
        return $this->status == 1? __('Active'): __('inActive');
    }

    public function branch(){
        return $this->belongsTo(Branch::class, 'branch_id')->withTrashed();
    }

    public function shifts(){
        return $this->belongsToMany(Shift::class, 'shifts_users');
    }
}
