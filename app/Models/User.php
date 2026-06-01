<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Concerns\HasInternationalPhoneDisplay;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasApiTokens, HasInternationalPhoneDisplay;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'gender',
        'invitation_code',
        'invited_by',
        'is_active',
        'is_verified',
        'image',
        'verification_code',
        'verification_code_expire',
        'invited_count',
        'has_invitation_discount',
        'role',
        'points',
        'fcm_token',
        'branch_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'is_verified' => 'boolean',
            'has_invitation_discount' => 'boolean',
            'points' => 'decimal:2',
        ];
    }

    protected $appends = ['image_url'];

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function inviter()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }
    public function invitees()
    {
        return $this->hasMany(User::class, 'invited_by');
    }
    public function favoriteProducts()
    {
        return $this->belongsToMany(Product::class, 'favorites')->withTimestamps();
    }
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
    public function coupons()
    {
        return $this->belongsToMany(Coupon::class, 'coupon_user')->withPivot('usage_count')->withTimestamps();
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function delivery()
    {
        return $this->hasOne(Delivery::class);
    }
}
