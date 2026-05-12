<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KeuanganRicemill extends Model
{
    use HasFactory;

    protected $table = 'keuangan_ricemill';

    protected $fillable = [
        'user_id', 'tipe', 'keterangan',
        'jumlah', 'kategori', 'tanggal', 'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah'  => 'decimal:2',
    ];

    public function operator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
