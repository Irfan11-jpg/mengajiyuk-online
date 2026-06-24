<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hafalan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'nomor_surah',
        'nama_surah',
        'ayat_awal',
        'ayat_akhir',
        'jenis',
        'kelancaran',
        'nilai',
        'status',
        'catatan_guru',
        'dinilai_oleh',
    ];

    /**
     * Relasi ke santri pemilik hafalan ini.
     */
    public function santri(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke guru yang menilai hafalan ini.
     */
    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dinilai_oleh');
    }

    /**
     * Scope: hafalan yang masih menunggu penilaian guru.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: hafalan yang sudah disetujui guru.
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope: hafalan jenis ziyadah (hafalan baru).
     */
    public function scopeZiyadah(Builder $query): Builder
    {
        return $query->where('jenis', 'ziyadah');
    }

    /**
     * Scope: hafalan jenis murojaah (mengulang hafalan lama).
     */
    public function scopeMurojaah(Builder $query): Builder
    {
        return $query->where('jenis', 'murojaah');
    }
}