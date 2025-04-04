<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Observers\GlobalModelObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable ,HasRoles , softDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'username',
        'store_id',
        'last_login',
        'user_id'
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }


    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class , 'created_by' , 'id');
    }

    public function createdUsers()
    {
        return $this->hasMany(User::class , 'created_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class , 'updated_by');
    }

    public function updatedUsers()
    {
        return $this->hasMany(User::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

public static function boot()
{
    parent::boot();
    static::observe(GlobalModelObserver::class);
}

}
