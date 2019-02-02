<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TincketFinishInstallation extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tincket:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Finish the automatic installation of the client.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->configureComposerAndInstall();
        $this->installNpm();
        $this->installAndConfigureAssets();

        $this->info("\nDone!");
        $this->warn("Do not forget to run `npm run dev` or `npm run prod` as needed");

        return true;
    }

    private function configureComposerAndInstall()
    {
        $version = $this->ask('Which major version? Use composer notation like ^1.6.0 or ^1.7.0, etc. '
                . 'Do not specify minor version here. Do it manually if needed');

        $composer_file = base_path('composer-test.json');
        $composer = file_get_contents($composer_file);
        $composer = preg_replace('/("tincket\/.+":( )+)".*"/', '$1"' . $version . '"', $composer);

        file_put_contents($composer_file, $composer);

        exec('composer install');
    }

    private function installNpm()
    {
        exec('npm install');
    }

    private function installAndConfigureAssets()
    {
        \Illuminate\Support\Facades\Artisan::call('vendor:publish', ['--tag' => 'tincket/client/install']);
        $this->info(\Illuminate\Support\Facades\Artisan::output());
        \Illuminate\Support\Facades\Artisan::call('vendor:publish', ['--tag' => 'tincket/client/config']);
        $this->info(\Illuminate\Support\Facades\Artisan::output());
        
        // modify primary color in asset.scss
        $primary = $this->ask('Indicate primary color of the brand. Eg: #ea3400');        
        $sass_file = resource_path('assets/sass/app.scss');
        $sass = file_get_contents($sass_file);
        $sass = preg_replace('/(\$primary:)( )*.*/', "$1 $primary;", $sass);        
        file_put_contents($sass_file, $sass);
        
        // modify includes in scss for prod environment
        $theme_file = resource_path('assets/sass/vendor/tincket-client/theme/theme.scss');
        $theme = file_get_contents($theme_file);
        // this reasource is used in development env. We replace final asset destination
        $theme = str_replace('packages/tincket-client/resources/assets/sass/', '', $theme);
        file_put_contents($theme_file, $theme);        
    }

}
