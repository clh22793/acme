<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/*
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
*/

class ArtistController extends Controller
{
	public function search(Request $request)
	{
		$query = $request->input('q');

		// do search
		$this->do_search($query);
		return "searching for: ".$query;
	}

	public function do_search($query)
	{
		
	}
}
