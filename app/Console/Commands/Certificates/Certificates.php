<?php namespace App\Console\Commands\Certificates;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

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
        $setting_filename = $this->argument('setting');
        $this->limit = (int)$this->option('limit');
        
        $path = base_path() . '/resources/certificate_settings/' . $setting_filename . '/';
        include_once($path . '/validation.php');

        $setup = parse_ini_file($path . 'setup.ini', true);;
        $this->setting = $setup;
        $this->setting['filename'] = $setting_filename;

        $wkoptions_path = base_path() . '/public/download/' . $setting_filename;
        if (!file_exists($wkoptions_path)) {
            mkdir($wkoptions_path, 0777, true);
        }

        $this->wkoptions = array(
            'path'        => $wkoptions_path . '/',
            'binpath'     => \Config::get('pdf.wkhtmltopdf_bin'),
            'orientation' => $setup['pdf']['orientation'],
            'page_size'   => $setup['pdf']['page_size'],
            'margins' => [
                'top'   => $setup['pdf']['margins']['top'],
                'bottom'=> $setup['pdf']['margins']['bottom'],
                'left'  => $setup['pdf']['margins']['left'],
                'right'  => $setup['pdf']['margins']['right'],
            ]
        );

        $this->theme = $setup['app']['theme'];

        $column = $this->setting['csv'];
        
        $file = fopen($path . $setup['app']['source'], 'r');
        $ctr = 0;
        while(!feof($file)) {
            $line = fgetcsv($file);
            $ctr++;

            $name = trim($line[$column['name']]);
            $email = trim($line[$column['email']]);

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

            if ($this->limit > 0 && $this->limit <= $ctr) {
                break;
            }
        }
    }

    public function createPdf($data)
    {
        $filename = preg_replace('/[^\da-z]/i', '', $data['name']);
        $filename = $this->setting['file']['prefix'] . strtolower($filename) . '.pdf';

        $renderFile = url('/certificates/preview');
        $renderFile .= '?name=' . urlencode($data['name']);
        $renderFile .= '&setting=' . urlencode($this->setting['filename']);
        
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
            array('setting', InputArgument::REQUIRED, 'settings name'),
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
