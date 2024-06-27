<?php

namespace App\Models;

use App\Models\Lokasi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gudang extends Model
{
    use HasFactory;
    protected $fillable = ['nama_gudang', 'kode_gudang', 'lokasi_id'];

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(Lokasi::class);
    }
}
