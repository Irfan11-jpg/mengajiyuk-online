<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IbadahJournal extends Model
{
    protected $fillable = [
        'user_id',
        'tanggal',
        'subuh',
        'dzuhur',
        'ashar',
        'maghrib',
        'isya',
        'tilawah',
        'murajaah',
        'tahajud',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'subuh' => 'boolean',
        'dzuhur' => 'boolean',
        'ashar' => 'boolean',
        'maghrib' => 'boolean',
        'isya' => 'boolean',
        'tilawah' => 'boolean',
        'murajaah' => 'boolean',
        'tahajud' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}