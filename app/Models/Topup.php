<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topup extends Model
{
    use HasFactory;

    // Kolom yang bisa diisi massal (mass assignment)
    protected $fillable = [
        'user_id',
        'amount',
        'payment_proof',
        'is_paid',
    ];

    // Relasi dengan model User (menghubungkan topup dengan user)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}