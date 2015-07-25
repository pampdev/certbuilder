<?php namespace App\Http\Controllers;

use Input;
use Auth;

use Wkhtmltopdf;

use App\Event;
use App\Http\Requests\EventFormRequest;

class CertificatesController extends Controller {

    protected $theme;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->event = new Event();

        $this->themes = [
            'pdg_attendee' => 'Default - Attendee',
            'pdg_organizer' => 'Default Organizer'
        ];
    }

    public function index()
    {

        $list = $this->event->where('user_id', '=', Auth::user()->id)->get();

        return view('certificates.index')->with(compact('list'));
    }

    public function show($event_id)
    {
        $event = $this->event->find($event_id);

        return view('certificates.show')->with(compact('event'));
    }

    public function create()
    {
        $event = null;
        $themes = $this->themes;
        $cert_types = $this->event->certTypes();
        return view('certificates.form')->with(compact('event', 'themes', 'cert_types'));
    }

    public function store(EventFormRequest $request)
    {
        $data = (array)$request->all();
        $data['user_id'] = Auth::user()->id;
        $this->event->create($data);
        if ($this->event) {
            return redirect('/certificates/' . $this->event->id)->with('message', 'Event added.');    
        }
        
        return redirect('/certificates/create')->withError('Event failed.');
    }

    public function edit($event_id)
    {
        $event = $this->event->find($event_id);
        $themes = $this->themes;
        $cert_types = $this->event->certTypes();
        return view('certificates.form')->with(compact('event', 'themes', 'cert_types'));
    }

    public function update($id, EventFormRequest $request)
    {
        $data = (array)$request->all();
        $event = $this->event->find($id);
        $data['code'] = $event->code;
        $event->update($data);
        if ($event) {
            return redirect('/certificates/' . $event->id)->with('message', 'Event added.');    
        }
        
        return redirect('/certificates/create')->withError('Event failed.');
    }

    public function sketchboard()
    {
        
        $themes = $this->themes;
        $event_id = Input::get('setting');
        
        $event = null;
        if ($event_id) {
            $event = $this->event->find($event_id);
        }

        return view('certificates.sketchboard')->with(compact('themes', 'event'));
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
        if ($setting_filename) {
            $path = base_path() . '/CertificateBuilder/events/' . $setting_filename . '/';
            $setup = parse_ini_file($path . 'setup.ini', true);

            foreach ($setup['event'] as $key => $value) {
                if (in_array($key, $variables)) {
                    ${$key} = trim($value);
                }
            }

            $theme = $setup['app']['theme'];
        }

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

        return view('templates.'.$theme.'.index')->with(compact(
            'name', 'name_style', 'title', 'theme',
            'event_name', 'event_place', 'date_day', 'date_month', 'date_year'
        ));
    }

    protected function setValues() 
    {

    }

}
