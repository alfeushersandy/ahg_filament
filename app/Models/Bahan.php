<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bahan extends Model
{
    use HasFactory;
    protected $fillable = ['kode_bahan', 'nama_bahan', 'sat', 'harga_master', 'kategori_id', 'user_id_input', 'tgl_input', 'user_id_revisi', 'tgl_revisi', 'harga_terbaru', 'tgl_terbaru', 'active'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'kategori_id');
    }

    public function userInp()
    {
        return $this->belongsTo(User::class, 'user_id_input');
    }

    public function userRev()
    {
        return $this->belongsTo(User::class, 'user_id_revisi');
    }

    // public function stok()
    // {
    //     return $this->hasMany(Stok::class, 'barang_id');
    // }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->timezone('Asia/Jakarta')->format('d-m-Y H:i:s'),
        );
    }

    protected function tglRevisi(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Carbon::parse($value)->timezone('Asia/Jakarta')->format('d-m-Y H:i:s') : '',
        );
    }
}
