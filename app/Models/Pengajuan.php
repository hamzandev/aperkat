<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Pengajuan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_pengaju',
        'nama_kegiatan',
        'bukti_kegiatan',
        'nominal_rab',
        'bukti_kegiatan',
        'rincian_kebutuhan',
    ];

    protected $casts = [
        'rincian_kebutuhan' => 'array',
    ];

    protected static function booted()
    {
        static::deleting(function ($document) {
            if ($document->bukti_kegiatan) {
                Storage::disk('public')->delete($document->bukti_kegiatan);
            }
        });
    }
}
