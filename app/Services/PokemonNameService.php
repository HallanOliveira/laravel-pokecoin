<?php

namespace App\Services;

use App\Models\PokemonName;

class PokemonNameService
{
    public function create(array $payload)
    {
        return PokemonName::create($payload);
    }
}
