<?php namespace App\Console\Commands\Certificates;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use App\Event;

use Wkhtmltopdf;

class Certificates extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cert:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genereate certificates';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->eventModel = new Event();
        

        $setting_filename = $this->argument('event_id');
        $limit = (int)$this->option('limit');

        $this->setting = $this->eventModel->where('code', '', $setting_filename);
        
        // Create the destination folder
        $wkoptions_path = base_path() . '/public/download/' . $setting_filename;
        if (!file_exists($wkoptions_path)) {
            mkdir($wkoptions_path, 0777, true);
        }

        // Start the process
        $path = base_path() . '/CertificateBuilder/pdf_settings.ini';
        $pdf_settings = parse_ini_file($path . 'setup.ini', true);;

        $this->wkoptions = array(
            'path'        => $wkoptions_path . '/',
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

        $column = [
            'name' => 1,
            'email' => 2
        ];
        
        $file = fopen($path . $setup['app']['source'], 'r');
        $ctr = 0;
        while(!feof($file)) {
            $line = fgetcsv($file);
            $ctr++;

            $name = trim($line[$column['name']]);
            $email = '';
            if ($column['email']) {
                $email = trim($line[$column['email']]);    
            }

            $line['name'] = $name;
            $line['email'] = $email;

            if (!$name) {
                $this->comment('Validation failed: No name. Line #' . $ctr);
                continue;
            }

            $func = $setting_filename . '_validate';
            if (function_exists($func)) {
                $res = $func($line);
                if ($res == 'skip') {
                    $this->comment('Skipped line #' . $ctr);
                    continue;
                }
                if ($res != '') {
                    $this->error($res);
                    continue;
                }
            }

            $this->comment($name . ' - ' . $email);
            $this->createPdf($line);

            // Break if this is a test
            if ($limit > 0) {
                if ($limit <= $ctr) {
                    break;
                }
                $limit++;
            }
        }
    }

    public function createPdf($data)
    {
        $filename = preg_replace('/[^\da-z]/i', '', $data['name']);
        $filename = $this->setting->filename_prefix . strtolower($filename) . '.pdf';

        $renderFile = url('/certificates/preview');
        $renderFile .= '?name=' . urlencode($data['name']);
        $renderFile .= '&setting=' . urlencode($this->setting->code);
        
        $this->info($filename);

        try {
            $wkhtmltopdf = new Wkhtmltopdf($this->wkoptions);
            $wkhtmltopdf->setUrl($renderFile);
            $wkhtmltopdf->output(Wkhtmltopdf::MODE_SAVE, $filename);
        } catch (Exception $e) {
            $this->error($e->getMessage());
            return false;
        }

        return true;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('event_id', InputArgument::REQUIRED, 'Event ID'),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('limit', null, InputOption::VALUE_OPTIONAL, 'Max limit of certs to create.', 0)
        );
    }

}
