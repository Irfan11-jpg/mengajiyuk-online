<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hafalan extends Model
{
    use HasFactory;

    /**
     * CATATAN PENTING:
     * Kita TIDAK perlu menulis $table = 'hafalans' secara manual.
     * Laravel secara otomatis mencari tabel bernama 'hafalans'
     * karena nama model 'Hafalan' + huruf 's' = 'hafalans'.
     * Ini adalah konvensi plural otomatis Laravel (Eloquent ORM).
     */

    /**
     * Kolom yang boleh diisi secara massal.
     * Semua kolom tabel hafalans didaftarkan di sini.
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
     * Casting tipe data agar kolom angka tidak dikembalikan sebagai string.
     */
    protected function casts(): array
    {
        return [
            'nomor_surah' => 'integer',
            'ayat_awal'   => 'integer',
            'ayat_akhir'  => 'integer',
        ];
    }

    // =========================================================
    // RELASI KE MODEL USER
    // =========================================================

    /**
     * Santri pemilik setoran hafalan ini.
     * Setiap hafalan dimiliki oleh satu santri.
     *
     * Cara pakai:
     *   $hafalan->santri        → objek User santrinya
     *   $hafalan->santri->name  → nama santrinya
     */
    public function santri(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Guru yang memberikan penilaian pada setoran ini.
     * Nullable karena awalnya belum ada guru yang menilai.
     *
     * Cara pakai:
     *   $hafalan->guru        → objek User gurunya (null jika pending)
     *   $hafalan->guru->name  → nama gurunya
     */
    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dinilai_oleh');
    }

    // =========================================================
    // SCOPE QUERY
    // Fungsi untuk memfilter hafalan berdasarkan kondisi tertentu.
    //
    // Cara pakai:
    //   Hafalan::pending()->get()
    //   Hafalan::approved()->count()
    //   $santri->hafalans()->ziyadah()->get()
    //   $santri->hafalans()->murojaah()->approved()->count()
    // =========================================================

    /**
     * Filter hafalan yang statusnya masih pending (belum dinilai guru).
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Filter hafalan yang sudah disetujui dan dinilai guru.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Filter hafalan yang jenisnya ziyadah (hafalan baru).
     */
    public function scopeZiyadah($query)
    {
        return $query->where('jenis', 'ziyadah');
    }

    /**
     * Filter hafalan yang jenisnya murojaah (pengulangan).
     */
    public function scopeMurojaah($query)
    {
        return $query->where('jenis', 'murojaah');
    }
}