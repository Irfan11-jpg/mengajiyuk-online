<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
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
        'role',
        'kelas',
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
        ];
    }

    /**
     * Cek apakah user ini berperan sebagai guru.
     */
    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    /**
     * Cek apakah user ini berperan sebagai santri.
     */
    public function isSantri(): bool
    {
        return $this->role === 'santri';
    }

    /**
     * Scope query untuk hanya mengambil user dengan role santri.
     */
    public function scopeSantri(Builder $query): Builder
    {
        return $query->where('role', 'santri');
    }

    /**
     * Scope query untuk hanya mengambil user dengan role guru.
     */
    public function scopeGuru(Builder $query): Builder
    {
        return $query->where('role', 'guru');
    }

    /**
     * Relasi ke semua hafalan milik user (jika dia santri).
     */
    public function hafalans(): HasMany
    {
        return $this->hasMany(Hafalan::class, 'user_id');
    }

    /**
     * Relasi ke hafalan-hafalan yang sudah dinilai oleh user ini (jika dia guru).
     */
    public function hafalanDinilai(): HasMany
    {
        return $this->hasMany(Hafalan::class, 'dinilai_oleh');
    }
}