<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RiwayatProduksi extends Model
{
    use HasFactory;

    protected $table = 'riwayat_produksi';

    protected $fillable = [
        'user_id', 'operasional_id', 'batch_id',
        'tanggal_proses', 'jumlah_gabah', 'jumlah_beras',
        'jenis_beras', 'notifikasi_rendemen_rendah', 'catatan',
    ];

    protected $casts = [
        'tanggal_proses'             => 'date',
        'jumlah_gabah'               => 'decimal:2',
        'jumlah_beras'               => 'decimal:2',
        'notifikasi_rendemen_rendah' => 'boolean',
    ];

    /**
     * Hitung rendemen otomatis (persentase beras dari gabah).
     */
    public function getRendemenAttribute(): float
    {
        if (!$this->jumlah_gabah || $this->jumlah_gabah == 0) return 0;
        return round(($this->jumlah_beras / $this->jumlah_gabah) * 100, 1);
    }

    public function getJenisBerasLabelAttribute(): string
    {
        return match($this->jenis_beras) {
            'premium'      => 'Premium',
            'medium'       => 'Medium',
            'setra_ramos'  => 'Setra Ramos',
            'pandan_wangi' => 'Pandan Wangi',
            'biasa'        => 'Biasa',
            default        => $this->jenis_beras ?? '-',
        };
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function operasional()
    {
        return $this->belongsTo(OperasionalPenggilingan::class, 'operasional_id');
    }
}
