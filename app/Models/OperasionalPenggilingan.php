<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OperasionalPenggilingan extends Model
{
    use HasFactory;

    protected $table = 'operasional_penggilingan';

    protected $fillable = [
        'user_id', 'penerimaan_gabah_id', 'batch_id',
        'tanggal_proses', 'jumlah_gabah_masuk', 'status', 'catatan',
    ];

    protected $casts = [
        'tanggal_proses'     => 'date',
        'jumlah_gabah_masuk' => 'decimal:2',
    ];

    public function operator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function penerimaanGabah()
    {
        return $this->belongsTo(PenerimaanGabah::class, 'penerimaan_gabah_id');
    }

    public function produksi()
    {
        return $this->hasOne(RiwayatProduksi::class, 'operasional_id');
    }
}
