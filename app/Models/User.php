<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'role',
        'customer_id',
        'partner_share_percentage',
        'admin_share_percentage',
    ];

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
        'partner_share_percentage' => 'decimal:2',
        'admin_share_percentage' => 'decimal:2',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isDashboardAdmin(): bool
    {
        return in_array($this->role, ['admin', 'super_admin'], true);
    }

    public function canManageData(): bool
    {
        return $this->isSuperAdmin();
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function isCustomerPortal(): bool
    {
        return $this->role === 'customer_portal';
    }

    public function isPartner(): bool
    {
        return $this->role === 'partner';
    }

    public function canAccess(string $permission): bool
    {
        return RolePermission::allowed($this->role, $permission);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
