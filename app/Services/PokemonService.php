<?php

namespace App\Services;

use App\Models\Pokemon;
use Illuminate\Database\Eloquent\Collection;

class PokemonService
{
    public function __construct(
        private Pokemon $pokemonModel
    ){}

    /**
     * Create a new pokemon
     *
     * @param array $payload
     * @return void
     */
    public function create(array $payload)
    {
        return $this->pokemonModel->create($payload);
    }

    /**
     * Get all model itens
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->pokemonModel->all();
    }

    /**
     * Get a especific pokemon
     *
     * @param integer|null $id
     * @return void
     */
    public function show(int|null $id)
    {
        return $this->pokemonModel->find($id);
    }

}
