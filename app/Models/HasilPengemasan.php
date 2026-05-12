<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HasilPengemasan extends Model
{
    use HasFactory;

    protected $table = 'hasil_pengemasan';

    protected $fillable = [
        'user_id', 'penerimaan_beras_id', 'tanggal',
        'jenis_beras', 'jenis_kemasan', 'jumlah_kemasan',
        'kualitas', 'catatan',
    ];

    protected $casts = [
        'tanggal'        => 'date',
        'jumlah_kemasan' => 'integer',
    ];

    public function packager()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function penerimaanBeras()
    {
        return $this->belongsTo(PenerimaanBeras::class, 'penerimaan_beras_id');
    }
}
