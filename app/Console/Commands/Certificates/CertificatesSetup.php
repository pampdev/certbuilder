<?php namespace App\Console\Commands\Certificates;

use Illuminate\Console\Command;
use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem as File;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CertificatesSetup extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cert:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate certificate event';

    /**
     * Repository config.
     *
     * @var Illuminate\Config\Repository
     */
    protected $config;

    /**
     * Filesystem
     *
     * @var Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Create a new command instance.
     *
     * @param \Illuminate\Config\Repository     $config
     * @param \Illuminate\Filesystem\Filesystem $files
     * @return \Teepluss\Theme\Commands\ThemeGeneratorCommand
     */
    public function __construct(Repository $config, File $files)
    {
        $this->config = $config;

        $this->files = $files;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        if ($this->files->isDirectory($this->getPath(null)))
        {
            return $this->error('Certificate "'.$this->getSetupName().'" is already exists.');
        }

        $this->makeDir(null);

        $this->makeFile('participants.csv', $this->getTemplate('participants.csv'));
        $this->makeFile('setup.ini', $this->getTemplate('setup.ini'));
        $this->makeFile('validation.php', $this->getTemplate('validation.php'));

        $this->info('Setup "'.$this->getSetupName().'" has been created.');
    }

    /**
     * Make directory.
     *
     * @param  string $directory
     * @return void
     */
    protected function makeDir($directory)
    {
        if ( ! $this->files->isDirectory($this->getPath($directory)))
        {
            $this->info('Createing directory ' . $this->getPath($directory));
            $this->files->makeDirectory($this->getPath($directory), 0777, true);
        }
    }

    /**
     * Make file.
     *
     * @param  string $file
     * @param  string $template
     * @return void
     */
    protected function makeFile($file, $template = null)
    {
        if ( ! $this->files->exists($this->getPath($file)))
        {
            $content = $this->getPath($file);

            $template = preg_replace('/sample/', $this->getSetupName().'$1', $template);
            $this->files->put($content, $template);

            $this->info('Createing file ' . $content);
        }
    }

    /**
     * Get root writable path.
     *
     * @param  string $path
     * @return string
     */
    protected function getRootPath()
    {
        return base_path() . '/resources/certificate_settings';
    }

    /**
     * Get default template.
     *
     * @param  string $template
     * @return string
     */
    protected function getTemplate($template)
    {
        $path = realpath(__DIR__.'/template/'.$template.'.txt');

        return $this->files->get($path);
    }

    /**
     * Get root writable path.
     *
     * @param  string $path
     * @return string
     */
    protected function getPath($path)
    {
        $rootPath = $this->getRootPath();
        if ($path == null) {
            return $rootPath.'/'.strtolower($this->getSetupName());    
        }

        return $rootPath.'/'.strtolower($this->getSetupName()).'/' . $path;
    }

    /**
     * Get the theme name.
     *
     * @return string
     */
    protected function getSetupName()
    {
        return strtolower($this->argument('name'));
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('name', InputArgument::REQUIRED, 'Name of the certificate settings to generate.'),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array();
    }

}