<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    use HasFactory;
    public $table = 'pokemons';

    const IN_STOCK = 1;
    const SOLD = 2;
}
