<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PokemonController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/pokemons/index', [PokemonController::class, 'index']);

Route::post('/pokemons/create/{json_data}', [PokemonController::class, 'create']);

Route::post('/pokemons/sellPokemon/{json_data}', [PokemonController::class, 'sellPokemon']);

Route::get('/pokemons/history', [PokemonController::class, 'historyOperations']);

Route::get('/pokemons/names', [PokemonController::class, 'getNames']);

Route::get('/pokemons/amountApplied', [PokemonController::class, 'getAmountApplied']);
