<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelaporan extends Model
{
    use HasFactory;

    protected $table = 'input_aspirasi';
    protected $primaryKey = 'id_pelaporan';
    public $incrementing = true;

    public $timestamps = true;

    protected $fillable = ['nama', 'kelas', 'nis', 'id_kategori', 'lokasi', 'ket', 'lampiran', 'status'];
    
    // Tambahkan method ini di dalam class Pelaporan
    public function kategori()
    {
        return $this->belongsTo(\App\Models\Kategori::class, 'id_kategori', 'id_kategori');
    }
}
