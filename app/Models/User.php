<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Core\UserRole;
use App\Models\Chantiers\Chantiers;
use App\Models\Commerce\Avoir;
use App\Models\Commerce\Commande;
use App\Models\Commerce\Devis;
use App\Models\Commerce\Facture;
use App\Models\RH\Employe;
use Creativeorange\Gravatar\Facades\Gravatar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Zap\Models\Concerns\HasSchedules;

final class User extends Authenticatable implements HasMedia
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasSchedules, InteractsWithMedia, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'blocked',
        'role',
        'bridge_uuid_token',
        'token',
        'tiers_id',
        'phone_number',
        'notif_phone',
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
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function routeNotificationForWhatsApp()
    {
        return $this->phone_number;
    }

    public function chantiers(): BelongsToMany
    {
        return $this->belongsToMany(Chantiers::class);
    }

    public function devis()
    {
        return $this->hasMany(Devis::class);
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    public function factures()
    {
        return $this->hasMany(Facture::class);
    }

    public function avoirs()
    {
        return $this->hasMany(Avoir::class);
    }

    public function employe()
    {
        return $this->hasOne(Employe::class);
    }

    public function getAvatarAttribute()
    {
        return Gravatar::get($this->email);
    }

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
            'role' => UserRole::class,
            'notif_phone' => 'boolean',
        ];
    }
}
