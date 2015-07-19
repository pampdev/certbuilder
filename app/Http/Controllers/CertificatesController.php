<?php namespace App\Http\Controllers;

use Input;
use Wkhtmltopdf;

class CertificatesController extends Controller {

    protected $theme;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    public function index()
    {
        $this->middleware('auth');
        $themes = [
            'default_red' => 'Default - red'
        ];
        return view('certificates.index')->with(compact('themes'));
    }

    public function preview()
    {
        // Default values
        $title = 'Certificate of Attendance';
        $event_name  = "Sample Event Name";
        $event_place = "Angeles City, Philippines";
        $date = date('Y-m-d');
        
        $name = 'Juan dela Cruz';
        $theme = 'default_red';

        $variables = [
            'title', 'event_name', 'event_place', 'date', 'theme', 'name'
        ];

        // Get default values from the settings
        $setting_filename = Input::get('setting');
        $path = base_path() . '/resources/certificate_settings/' . $setting_filename . '/';
        $setup = parse_ini_file($path . 'setup.ini', true);

        foreach ($setup['event'] as $key => $value) {
            if (isset($variables[$key])) {
                ${$key} = trim($value);
            }
        }

        $theme = $setup['app']['theme'];

        // Can be changed via _GET
        foreach ($variables as $key) {
            if (Input::get($key)) {
                ${$key} = Input::get($key);
            }
        }

        // Format the date
        $timestamp = strtotime($date);
        $date_day       = date('d', $timestamp);
        $date_month     = date('M', $timestamp);
        $date_year      = date('Y', $timestamp);

        $name_style = '';
        if (strlen($name) > 16) {
            $name_style = 'style="font-size: 4em;"';
        }

        return view('templates.default_red.index')->with(compact(
            'name', 'name_style', 'title', 'theme',
            'event_name', 'event_place', 'date_day', 'date_month', 'date_year'
        ));
    }

    protected function setValues() 
    {

    }

}
