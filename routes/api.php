<?php

use Illuminate\Http\Request;
use App\Api\Controllers\SpotifyController;
use App\Dao\Controllers\SpotifyDaoController;
use App\Artist;
use App\Genre;
use App\ArtistsGenres;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/similar_to', function(Request $request, SpotifyController $artist, SpotifyDaoController $dao){
	// get genres of this artist (id)
	$id = $request->input('spotify_id');
	$results = $dao->get_genres_by_spotify_id($id);	

	// search spotify by genre
	$response = json_decode($artist->search_by_genres($results), true);

	// store results in db
	$dao->save_search_results($response);

	// return responses
	return $response;

});

Route::get('/artists', function(Request $request, SpotifyController $artist, SpotifyDaoController $dao){
	// search spotify
	$query = $request->input('q');
	$response = json_decode($artist->search_by_artist($query), true);
	//$response = $artist->search_by_artist($query);

	// store results in db
	$dao->save_search_results($response);

	// return response
	return $response;

});
