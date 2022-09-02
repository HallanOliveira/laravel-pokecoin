<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    public $table   = 'transactions';
    const TYPE_BUY  = 1;
    const TYPE_SELL = 2;
}
