<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenerimaanGabah extends Model
{
    use HasFactory;

    protected $table = 'penerimaan_gabah';

    protected $fillable = [
        'user_id', 'nama_petani', 'asal_lahan', 'tanggal',
        'jumlah_gabah', 'kualitas_gabah', 'status',
        'bukti_foto', 'catatan',
    ];

    protected $casts = [
        'tanggal'     => 'date',
        'jumlah_gabah'=> 'decimal:2',
    ];

    public function operator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function operasional()
    {
        return $this->hasMany(OperasionalPenggilingan::class, 'penerimaan_gabah_id');
    }
}
