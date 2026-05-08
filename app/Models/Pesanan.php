<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';

    protected $fillable = [
        'user_id', 'nama_pelanggan', 'tanggal',
        'jenis_produk', 'jumlah',
        'harga_satuan', 'total_harga',
        'status', 'catatan',
    ];

    protected $casts = [
        'tanggal'      => 'date',
        'harga_satuan' => 'decimal:2',
        'total_harga'  => 'decimal:2',
        'jumlah'       => 'integer',
    ];

    public function packager()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
