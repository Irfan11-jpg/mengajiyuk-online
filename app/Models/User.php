<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang boleh diisi secara massal melalui create() atau update().
     * Kolom yang tidak ada di sini tidak bisa diisi dari luar (keamanan).
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'kelas',
    ];

    /**
     * Kolom yang TIDAK boleh ditampilkan saat data diubah ke JSON atau array.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting otomatis tipe data kolom saat dibaca dari database.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // =========================================================
    // HELPER ROLE
    // =========================================================

    /**
     * Kembalikan true jika user ini adalah guru.
     */
    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    /**
     * Kembalikan true jika user ini adalah santri.
     */
    public function isSantri(): bool
    {
        return $this->role === 'santri';
    }

    // =========================================================
    // RELASI KE TABEL HAFALANS
    // =========================================================

    /**
     * Semua setoran hafalan yang dimiliki santri ini.
     */
    public function hafalans(): HasMany
    {
        return $this->hasMany(Hafalan::class, 'user_id');
    }

    /**
     * Semua setoran hafalan yang sudah dinilai guru.
     */
    public function hafalanDinilai(): HasMany
    {
        return $this->hasMany(Hafalan::class, 'dinilai_oleh');
    }

    // =========================================================
    // RELASI JURNAL IBADAH
    // =========================================================

    /**
     * Semua jurnal ibadah milik user.
     */
    public function journals(): HasMany
    {
        return $this->hasMany(IbadahJournal::class, 'user_id');
    }

    // =========================================================
    // RELASI BADGE
    // =========================================================

    /**
     * Semua badge yang telah diperoleh user.
     */
    public function userBadges(): HasMany
    {
        return $this->hasMany(UserBadge::class, 'user_id');
    }

    // =========================================================
    // SCOPE QUERY
    // =========================================================

    /**
     * Filter query hanya user santri.
     */
    public function scopeSantri($query)
    {
        return $query->where('role', 'santri');
    }

    /**
     * Filter query hanya user guru.
     */
    public function scopeGuru($query)
    {
        return $query->where('role', 'guru');
    }

    // =========================================================
    // HELPER STREAK
    // =========================================================

    /**
     * Menghitung streak berdasarkan jurnal ibadah.
     */
    public function currentStreak(): int
    {
        $dates = $this->journals()
            ->orderByDesc('tanggal')
            ->pluck('tanggal')
            ->map(fn ($d) => \Carbon\Carbon::parse($d)->toDateString())
            ->toArray();

        if (empty($dates)) {
            return 0;
        }

        $streak = 0;
        $current = now()->toDateString();

        foreach ($dates as $date) {

            if ($date === $current) {
                $streak++;
                $current = now()->parse($current)->subDay()->toDateString();
            } elseif ($date === now()->parse($current)->subDay()->toDateString()) {
                $streak++;
                $current = now()->parse($current)->subDay()->toDateString();
            } else {
                break;
            }
        }

        return $streak;
    }
}