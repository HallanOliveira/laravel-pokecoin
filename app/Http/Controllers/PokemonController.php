<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Pokemon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class PokemonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pokemon = Pokemon::all()->where('status', Pokemon::IN_STOCK);
        return $pokemon->toJson();

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($json_data)
    {
        $array_data = json_decode($json_data,true);

        $pokemonModel                  = new Pokemon;
        $pokemonModel->name            = $array_data['name'];
        $pokemonModel->buy_price       = $array_data['buyPrice'];
        $pokemonModel->buy_date        = $array_data['buyDate'];
        $pokemonModel->imagem          = $array_data['imagem'];
        $pokemonModel->base_experience = $array_data['baseExp'];
        $pokemonModel->status          = $pokemonModel::IN_STOCK;
        if($pokemonModel->save()) {
            return http_response_code(200);
        }
        return http_response_code(500);
    }

    /**
     * Sell the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pokemon  $pokemon
     * @return \Illuminate\Http\Response
     */
    public function sellPokemon($json_data)
    {
        $array_data   = json_decode($json_data,true);
        $pokemonModel = Pokemon::find($array_data['id']);

        if($pokemonModel) {
            $pokemonModel->sell_price      = $array_data['sellPrice'];
            $pokemonModel->sell_date       = $array_data['sellDate'];
            $pokemonModel->status          = $pokemonModel::SOLD;
            if($pokemonModel->save()) {
                return http_response_code(200);
            }
        }
        return http_response_code(500);
    }

    public function historyOperations()
    {
        //$pokemonsTransactions = DB::table('pokemons')->where();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pokemon  $pokemon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pokemon $pokemon)
    {
        //
    }
}
