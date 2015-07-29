<?php 
namespace CertificateBuilder;

class ThemeList {
    const THEME_PATH = '/resources/views/templates/';
    public static function names() {
        $themes = array();

        $path = base_path() . self::THEME_PATH;
        $dh  = opendir($path);
        while (false !== ($filename = readdir($dh))) {
            if ($filename == '.' || $filename == '..') continue;
            
            $theme = $path . $filename . '/theme.ini';
            if (file_exists($theme)) {
                $settings = parse_ini_file($theme, true);
                if (!isset($settings['name'])) continue;

                $themes[$filename] = $settings['name'];
            }
        }

        return $themes;
    }
}