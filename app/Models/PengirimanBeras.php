<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PengirimanBeras extends Model
{
    use HasFactory;

    protected $table = 'pengiriman_beras';

    protected $fillable = [
        'user_id', 'nama_packager', 'jenis_beras',
        'jumlah_karung', 'berat_per_karung', 'tanggal_kirim',
        'biaya_logistik', 'status', 'bukti_kirim', 'catatan',
    ];

    protected $casts = [
        'tanggal_kirim'   => 'date',
        'biaya_logistik'  => 'decimal:2',
        'berat_per_karung'=> 'decimal:2',
    ];

    public function operator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function penerimaanBeras()
    {
        return $this->hasMany(PenerimaanBeras::class, 'pengiriman_beras_id');
    }
}
