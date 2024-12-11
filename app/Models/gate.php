<?php

namespace App\Models;

use App\Models\transaction;
use Illuminate\Database\Eloquent\Model;

class gate extends Model
{
    protected $fillable = ['gate_number', 'location', 'ticket_price'];

    public function transactions()
    {
        return $this->hasMany(transaction::class);
    }
}
