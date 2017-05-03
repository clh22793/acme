<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Providers\SpotifyServiceProvider;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;


class ArtistController extends Controller
{
	public function __construct(/*SpotifyServiceProvider $spotify*/){
		//print "do something";
	}

	// DEPRECATED
	public function search(Request $request/*, SpotifyServiceProvider $spotify*/)
	{
		$query = $request->input('q');

		// do search
		//$this->do_search($query);
		//return "searching for: ".$query;
		//$spotify->search_by_artist($query);
	}

	public function do_search($query)
	{
		$client = new Client();
		$base_url = 'https://api.spotify.com/v1/search';
		$response = $client->get($base_url, ['query' => ['q' => $query, 'type' => 'artist']]);

		return $response->getBody();
	}
}
