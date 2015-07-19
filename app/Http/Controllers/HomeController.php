<?php namespace App\Http\Controllers;

class HomeController extends Controller {

	protected $theme;

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
		// $this->theme = $theme->uses('default')->layout('default');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		$title = 'Certificate of Attendance';
		$certification  = "Google I/O 2015 Extended Pampanga";
		$location       = "Holy Angel University, Angeles City, Philippines";
		$date_day       = 4;
		$date_month 	= "July";
		$date_year 		= "2015";
		$name = Input::get('name') ? Input::get('name') : 'Juan dela Cruz';

		$theme = 'default_red';

		$name_style = '';
		if (strlen($name) > 16) {
			$name_style = 'style="font-size: 4em;"';
		}


		return view('templates.default_red.index')->with(compact(
			'name', 'name_style', 'title', 'theme',
			'certification', 'location', 'date_day', 'date_month', 'date_year'
		));
	}

}
