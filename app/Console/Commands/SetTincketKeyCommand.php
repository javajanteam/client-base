<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetTincketKeyCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tincket:key-set';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the tincket application key. Generated from the engine.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $brandKey = $this->ask('Paste here the TK_BRAND_KEY');
        $appKey   = $this->ask('Paste here the TK_APPLICATION_KEY');

        $this->writeNewEnvironmentFileWith($brandKey, $appKey);

        $this->info("\n Done! @ " . $this->laravel->environmentFilePath());

        return true;
    }

    /**
     * Write a new environment file with the given key.
     *
     * @param  string  $key
     * @return void
     */
    protected function writeNewEnvironmentFileWith($brandKey, $appKey)
    {
        file_put_contents($this->laravel->environmentFilePath(), str_replace(
            'TK_BRAND_KEY=' . env('TK_BRAND_KEY'), 'TK_BRAND_KEY=' . $brandKey, file_get_contents($this->laravel->environmentFilePath())
        ));

        file_put_contents($this->laravel->environmentFilePath(), str_replace(
            'TK_APPLICATION_KEY=' . env('TK_APPLICATION_KEY'), 'TK_APPLICATION_KEY=' . $appKey, file_get_contents($this->laravel->environmentFilePath())
        ));
    }

}
