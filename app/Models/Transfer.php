<?php
// app/Models/Transfer.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_code',
        'sender_name',
        'receiver_name',
        'amount',
        'ville_provenance',
        'ville_destination',
        'guichetier_provenance',
        'guichetier_destination',
        'date_transfer',
        'telephone',
        'status',
        'created_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date_transfer' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}