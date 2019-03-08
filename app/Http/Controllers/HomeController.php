<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\SEOTools;

class HomeController extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		SEOTools::setTitle('Inicial');
		return view('home/index');
	}
}
