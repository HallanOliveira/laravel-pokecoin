<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    use HasFactory;
    protected $table    = 'pokemons';
    protected $fillable = ['name','buy_price','sell_price','imagem','base_experience'];
    const API_POKEMONS  = 'https://pokeapi.co/api/v2/pokemon/';
}
