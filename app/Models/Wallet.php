<?php

namespace App\Models;

use App\Models\User;
use App\Models\transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = ['balance', 'user_id'];

    // العلاقة مع المستخدم
    public function user()
    {
        return $this->belongsToMany(User::class);
    }
    public function transactions()
    {
        return $this->hasMany(transaction::class);
    }
}
