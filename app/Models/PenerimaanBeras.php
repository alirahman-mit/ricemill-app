<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenerimaanBeras extends Model
{
    use HasFactory;

    protected $table = 'penerimaan_beras';

    protected $fillable = [
        'user_id', 'pengiriman_beras_id', 'asal_penggilingan',
        'jenis_beras', 'jumlah_beras', 'tanggal',
        'status', 'bukti_foto', 'catatan',
    ];

    protected $casts = [
        'tanggal'     => 'date',
        'jumlah_beras'=> 'decimal:2',
    ];

    public function packager()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pengirimanBeras()
    {
        return $this->belongsTo(PengirimanBeras::class, 'pengiriman_beras_id');
    }

    public function hasilPengemasan()
    {
        return $this->hasMany(HasilPengemasan::class, 'penerimaan_beras_id');
    }
}
