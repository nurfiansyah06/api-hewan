<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Animal extends Model
{
    protected $fillable = [
        'user_id','nama','jenis_hewan','jenis_makanan','warna_bulu',
        'berat_badan','jumlah_hewan','is_approval'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    // public function user()
    // {
    //     return $this->belongsTo('App\User', 'foreign_key', 'other_key');
    // }
}
