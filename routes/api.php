<?php

use Illuminate\Http\Request;
use App\Http\Controllers\ArtistController;
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


function insert($table, $query, $params){
	DB::table($table)->insertGetId();
}

//Route::get('/artists', 'ArtistController@search');
Route::get('/artists', function(Request $request, ArtistController $artist){
	$query = $request->input('q');
	$response = json_decode($artist->do_search($query), true);

	// store results in db
	//print_r($response['artists']['items']);

	foreach($response['artists']['items'] as $item){
		// insert artist
		$artist = new Artist;
		$artist->name = $item['name'];
		$artist->spotify_id = $item['id'];
		$artist->popularity = $item['popularity'];

		if(!empty($item['images'])){
			$artist->image_url = $item['images'][0]['url'];
		}
		$artist->save();

		// insert genres
		foreach($item['genres'] as $value){
			// insert into genre
			var_dump($value);
			$genre = new Genre;
			$genre->name = $value;
			$genre->save();

			// insert into relation table
			$ag = new ArtistsGenres;
			$ag->artist_id = $artist->id;
			$ag->genre_id = $genre->id;
			$ag->save();
		}
	}

	//$results = DB::select('select * from artists');

	// return response
	return $response;

});
