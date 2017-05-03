<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;


class ArtistController extends Controller
{

	const BASE_URL = "https://api.spotify.com/v1/";

	public function search_by_artist($query)
	{
		$client = new Client();
		$response = $client->get(self::BASE_URL.'search', ['query' => ['q' => $query, 'type' => 'artist']]);

		$body = $response->getBody();
		return $body->getContents();
	}

	public function search_by_genres(array $genres)
	{
		$client = new Client();
		$base_url = 'https://api.spotify.com/v1/search';
		$response = $client->get(self::BASE_URL.'search', ['debug' => true, 'query' => ['q' => 'genre:"'.implode(',', $genres) . '"', 'type' => 'artist']]);

		$body = $response->getBody();
		return $body->getContents();
	}
}
