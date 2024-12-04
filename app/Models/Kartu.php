<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kartu extends Model
{
    // Menambahkan kolom yang boleh diisi secara mass-assignment
    protected $fillable = [
        'name', 'slug', 'photo', 'about', 'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}