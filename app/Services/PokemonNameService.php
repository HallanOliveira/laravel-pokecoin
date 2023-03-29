<?php

namespace App\Services;

use App\Models\PokemonName;

class PokemonNameService
{
    public function __construct(
        private PokemonName $pokemonNameModel
    ){}

    public function create(array $payload)
    {
        return $this->pokemonNameModel->create($payload);
    }

    public function getAll()
    {
        return $this->pokemonNameModel->all();
    }

    public function getNames()
    {
        return $this->pokemonNameModel
            ->select(['name as label','external_id as value'])
            ->orderBy('name', 'asc')
            ->get();
    }
}
