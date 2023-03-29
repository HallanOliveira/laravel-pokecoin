<?php

namespace App\Components\WebServices\PokeApi;

use App\Components\WebServices\PokeApi\PokeApiInterface;
use App\Components\WebServices\BaseWebService;
use Illuminate\Http\Client\PendingRequest;

class PokeApiWebService extends BaseWebService implements PokeApiInterface
{
    const BASE_URL = 'https://pokeapi.co/api/v2/pokemon';

    public function getPokemonNames()
    {
        $this->_url    = self::BASE_URL . '/?limit=9999';
        $this->_method = 'get';
        return $this->sendRequest();
    }
}
