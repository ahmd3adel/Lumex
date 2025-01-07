<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
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
        'store_id'
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
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


    protected static function booted()
    {



//             static::addGlobalScope('storeUsers', function (Builder $builder) {
//                $user = Auth::user();
//                $builder->where('id' , "=" , $user->store_id);
//            });


//        try {
//            static::addGlobalScope('storeUsers', function (Builder $builder) {
//                if (Auth::check() && !is_null(Auth::user()->store_id)) {
//                    $builder->where('store_id', Auth::user()->store_id);
//                }
//            });
//        }
//        catch (\Exception $e) {
//            \Log::error('Error in User Global Scope: ' . $e->getMessage(), [
//                'trace' => $e->getTraceAsString(),
//            ]);
//        }
    }





}
