<?php namespace App\Http\Controllers;

use Input;
use Auth;

use Wkhtmltopdf;

use App\Event;
use App\Http\Requests\EventFormRequest;

use CertificateBuilder\ThemeList;

class CertificatesController extends Controller {

    protected $theme = array();

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->event = new Event();

        $this->themes = ThemeList::names();
    }

    public function index()
    {

        $list = $this->event->where('user_id', '=', Auth::user()->id)->get();
        return view('certificates.index')->with(compact('list'));
    }

    public function show($event_id)
    {
        $event = $this->event->find($event_id);
        if (!$event) {
            return redirect('/certificates')->withError('Event not found.');
        }

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
        if (!$event) {
            return redirect('/certificates')->withError('Event not found.');
        }

        $themes = $this->themes;
        $cert_types = $this->event->certTypes();
        return view('certificates.form')->with(compact('event', 'themes', 'cert_types'));
    }

    public function update($id, EventFormRequest $request)
    {
        $data = (array)$request->all();
        $event = $this->event->find($id);
        if (!$event) {
            return redirect('/certificates')->withError('Event not found.');
        }

        $data['code'] = $event->code;
        $event->update($data);
        if ($event) {
            return redirect('/certificates/' . $event->id)->with('message', 'Event added.');    
        }
        
        return redirect('/certificates/create')->withError('Event failed.');
    }

    public function destroy($id) {
        $event = $this->event->find($id);
        if (!$event) {
            return redirect('/certificates')->withError('Event not found.');
        }

        if ($event->delete($id)) {
            return \Redirect::route('certificates.index')->with('message', 'Recipe deleted.');
        }
        return \Redirect::route('certificates.index')->withError('Unable to delete.');
    }

    public function sandbox()
    {
        $themes = $this->themes;
        $event_id = Input::get('setting');
        
        $event = null;
        if ($event_id) {
            $event = $this->event->find($event_id);
        }

        return view('certificates.sandbox')->with(compact('themes', 'event'));
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
        $prefix = 'xx_';

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

        $renderFile = view('templates.'.$theme.'.index')->with(compact(
            'name', 'name_style', 'title', 'theme',
            'event_name', 'event_place', 'date_day', 'date_month', 'date_year'
        ));

        if (Input::get('download')) {
            $wkoptions_path = base_path() . '/public/tmp/';

            if (!file_exists($wkoptions_path)) {
                mkdir($wkoptions_path, 0777, true);
            }

            $pdf_settings['pdf'] = \Config::get('pdf.settings');

            $this->wkoptions = array(
                'path' => $wkoptions_path,
                'binpath'     => \Config::get('pdf.wkhtmltopdf_bin'),
                'orientation' => $pdf_settings['pdf']['orientation'],
                'page_size'   => $pdf_settings['pdf']['page_size'],
                'margins' => [
                    'top'   => $pdf_settings['pdf']['margins']['top'],
                    'bottom'=> $pdf_settings['pdf']['margins']['bottom'],
                    'left'  => $pdf_settings['pdf']['margins']['left'],
                    'right'  => $pdf_settings['pdf']['margins']['right'],
                ]
            );

            $filename = preg_replace('/[^\da-z]/i', '', $name);
            $filename = $prefix . strtolower($filename) . '.pdf';

            try {
                $wkhtmltopdf = new Wkhtmltopdf($this->wkoptions);
                $wkhtmltopdf->setHtml($renderFile);
                $wkhtmltopdf->output(Wkhtmltopdf::MODE_DOWNLOAD, $filename);
            } catch (Exception $e) {
                $this->error($e->getMessage());
                return false;
            }

            return '';
        } else {
            return $renderFile;
        }
    }

}
