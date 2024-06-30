<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    use HasFactory;
    protected $fillable = ['gudang_id', 'lokasi_id', 'bahan_id', 'jumlah', 'tgl_update'];

    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function bahan()
    {
        return $this->belongsTo(Bahan::class);
    }
}
