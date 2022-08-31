<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PokemonController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/pokemons/index', [PokemonController::class, 'index']);

Route::post('/pokemons/create/{json_data}', [PokemonController::class, 'create']);

Route::post('/pokemons/sellPokemon/{json_data}', [PokemonController::class, 'sellPokemon']);

Route::get('/pokemons/history', [PokemonController::class, 'historyOperations']);

Route::get('/pokemons/names', [PokemonController::class, 'getNames']);


Route::get('/', function () {
    return view('welcome');
});


