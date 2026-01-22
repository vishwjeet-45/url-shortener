<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'company_id',
        'role_id',
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

    protected $appends = ['total_generated_urls','total_url_hits'];

    public function getTotalGeneratedUrlsAttribute() {
        return $this->shortUrls()->count();
    }

    public function getTotalUrlHitsAttribute() {
        return $this->shortUrls()->sum('click_count');
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
        ];
    }

    public function company() {
        return $this->belongsTo(Company::class);
    }

    public function role() {
        return $this->belongsTo(Role::class);
    }
    public function hasRole($role) {
        return $this->role()->where('name', $role)->exists();
    }

    public function shortUrls() {
        return $this->hasMany(ShortUrl::class);
    }
}
