<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    use HasFactory;

    protected $table = 'stok';

    protected $fillable = [
        'idbahan',
        'jumlah_stok',
    ];

    public function masterBahan()
    {
        return $this->belongsTo(MasterBahan::class, 'idbahan');
    }
}
