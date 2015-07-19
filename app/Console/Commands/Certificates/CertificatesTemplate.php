<?php namespace App\Console\Commands\Certificates;

use Illuminate\Console\Command;
use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem as File;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CertificatesTemplate extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cert:template';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate certificate template';

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
        $name = $this->getThemeName();
        if ($this->files->isDirectory($this->getPath(null)))
        {
            return $this->error('Certificate "'.$name.'" is already exists.');
        }

        $this->makeDir($this->getViewsPath($name));
        $this->makeFile($this->getViewsPath($name) . '/index.blade.php', $this->getTemplate('index.blade.php'));

        $this->makeDir($this->getPublicPath($name));
        $this->makeDir($this->getPublicPath($name . '/css'));
        $this->makeDir($this->getPublicPath($name . '/images'));

        $this->info('Theme "'.$this->getThemeName().'" has been created.');
    }

    /**
     * Make directory.
     *
     * @param  string $directory
     * @return void
     */
    protected function makeDir($directory)
    {
        if ( ! $this->files->isDirectory($directory))
        {
            $this->info('Creating directory ' . $directory);
            $this->files->makeDirectory($directory, 0777, true);
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
            $content = $file;

            $template = preg_replace('/sample/', $this->getThemeName().'$1', $template);
            $this->files->put($content, $template);

            $this->info('Createing file ' . $content);
        }
    }

    protected function getViewsPath($path)
    {
        $base = base_path() . '/resources/views/templates';
        if ($path != null) {
            $base .= '/' . $path;
        }
        return $base;
    }

    protected function getPublicPath($path)
    {
        $base = base_path() . '/public/templates';
        if ($path != null) {
            $base .= '/' . $path;
        }
        return $base;
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
        $rootPath = base_path();
        if ($path == null) {
            return $rootPath.'/'.strtolower($this->getThemeName());    
        }

        return $rootPath.'/'.strtolower($this->getThemeName()).'/' . $path;
    }

    /**
     * Get the theme name.
     *
     * @return string
     */
    protected function getThemeName()
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