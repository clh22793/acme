<?php

namespace App\Dao\Controllers;

use Illuminate\Support\Facades\DB;
use App\Artist;
use App\Genre;
use App\ArtistsGenres;

class SpotifyDaoController extends Controller
{
	const MAX_GENRE_MATCH = 2;


	private function save_artist($item)
	{
		// first, check if artist exists
		$artist = Artist::where('spotify_id', $item['id'])->first();

		if(empty($artist)){
			// insert artist
			$artist = new Artist;
			$artist->name = $item['name'];
			$artist->spotify_id = $item['id'];
			$artist->popularity = $item['popularity'];

			if(!empty($item['images'])){
				$artist->image_url = $item['images'][0]['url'];
			}
			$artist->save();
		}

		return $artist;
	}

	private function save_genre($value)
	{
		$genre = Genre::where('name', $value)->first();

		if(empty($genre)){
			// insert into genre
			$genre = new Genre;
			$genre->name = $value;
			$genre->save();
		}

		return $genre;
	}

	private function save_artists_genres($artist_id, $genre_id)
	{
		$ag = ArtistsGenres::where('artist_id', $artist_id)->where('genre_id', $genre_id)->first();	

		if(empty($ag)){
			// insert into relation table
			$ag = new ArtistsGenres;
			$ag->artist_id = $artist_id;
			$ag->genre_id = $genre_id;
			$ag->save();
		}

		return $ag;
	}

	public function save_search_results($response)
	{
		foreach($response['artists']['items'] as $item){

			$artist = $this->save_artist($item);

			// insert genres
			foreach($item['genres'] as $value){
				$genre = $this->save_genre($value);			

				$this->save_artists_genres($artist->id, $genre->id);

			}
		}

	}

	public function get_genres_by_spotify_id($spotify_id)
	{
		// get genres of this artist (id)
		$genres = DB::select('select g.name from artists a join artists_genres ag on a.id = ag.artist_id join genres g on ag.genre_id = g.id where a.spotify_id = ? limit 0,?', [$spotify_id, self::MAX_GENRE_MATCH]);

		$results = array();
		foreach($genres as $genre){
			$results[] = $genre->name;
		}	

		return $results;

	}

}
