<?php

namespace App\Models;

use App\Models\gate;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Model;

class transaction extends Model
{
    protected $fillable = ['user_id', 'gate_id', 'wallet_id', 'amount', 'transaction_type', 'transaction_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gate()
    {
        return $this->belongsTo(gate::class);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
