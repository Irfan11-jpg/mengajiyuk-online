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
     * Melindungi password agar tidak bocor ke response API atau view.
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
    // Fungsi sederhana untuk mengecek role user yang sedang login.
    //
    // Cara pakai di controller:
    //   Auth::user()->isGuru()
    //   Auth::user()->isSantri()
    //
    // Cara pakai di blade view:
    //   @if(Auth::user()->isGuru())
    //   @if(Auth::user()->isSantri())
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
     * Satu santri bisa punya banyak setoran hafalan.
     *
     * Cara pakai di controller:
     *   $santri->hafalans()->get()
     *   $santri->hafalans()->approved()->count()
     *   $santri->hafalans()->pending()->get()
     */
    public function hafalans(): HasMany
    {
        return $this->hasMany(Hafalan::class, 'user_id');
    }

    /**
     * Semua setoran hafalan yang sudah dinilai oleh guru ini.
     * Satu guru bisa menilai banyak setoran dari berbagai santri.
     *
     * Cara pakai di controller:
     *   $guru->hafalanDinilai()->count()
     *   $guru->hafalanDinilai()->latest()->get()
     */
    public function hafalanDinilai(): HasMany
    {
        return $this->hasMany(Hafalan::class, 'dinilai_oleh');
    }

    // =========================================================
    // SCOPE QUERY
    // Fungsi untuk memfilter query langsung dari model.
    //
    // Cara pakai:
    //   User::santri()->get()              → semua santri
    //   User::guru()->get()                → semua guru
    //   User::santri()->where('kelas','10A')->get() → santri kelas 10A
    // =========================================================

    /**
     * Filter query hanya mengembalikan user dengan role santri.
     */
    public function scopeSantri($query)
    {
        return $query->where('role', 'santri');
    }

    /**
     * Filter query hanya mengembalikan user dengan role guru.
     */
    public function scopeGuru($query)
    {
        return $query->where('role', 'guru');
    }
}