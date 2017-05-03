<?php

namespace App\Api\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class SpotifyController extends Controller
{

	const BASE_URL = "https://api.spotify.com/v1/";
	const PLACEHOLDER_IMAGE = "//placehold.it/600x600";

	public function __construct(){
		$this->guzzle = new Client();
	}

	private function clean_images($items)
	{
		foreach($items as &$item){
			$item['images'] = (empty($item['images'])) ? array(self::PLACEHOLDER_IMAGE) : $item['images'];
		}

		return $items;
	}

	public function search_by_artist($query)
	{
		//$client = new Client();
		$response = $this->guzzle->get(self::BASE_URL.'search', ['query' => ['q' => $query, 'type' => 'artist']]);

		$body = $response->getBody();


		return $body->getContents();
		return $this->clean_images($body->getContents());
	}

	public function search_by_genres(array $genres)
	{
		//$client = new Client();
		$base_url = 'https://api.spotify.com/v1/search';
		$response = $this->guzzle->get(self::BASE_URL.'search', ['query' => ['q' => 'genre:"'.implode(',', $genres) . '"', 'type' => 'artist']]);

		$body = $response->getBody();
		return $body->getContents();
	}
}
