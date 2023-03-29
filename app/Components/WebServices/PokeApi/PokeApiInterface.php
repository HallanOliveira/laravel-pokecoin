<?php

namespace App\Components\WebServices\PokeApi;

use Illuminate\Http\Client\PendingRequest;

interface PokeApiInterface
{
    public function getPokemonNames();
}
