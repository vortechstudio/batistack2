<?php

namespace App\Console\Commands\Server;

use File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;
use Str;

use function Laravel\Prompts\intro;
use function Laravel\Prompts\note;
use function Laravel\Prompts\warning;

class ServeMobileCommand extends Command
{
    protected $signature = 'serve:mobile {--android} {--ios}';

    protected $description = 'Command description';

    public function handle()
    {
        if (! $this->option('ios') && ! $this->option('android')) {
            warning("A device option is needed : 'mobile:serve --android' or 'mobile:serve --ios'");
        }

        intro('Running Mobile Environment');

        $this->initViteServer();
        $this->initPHPServer();
        $this->initTauriServer();
    }

    private function initTauriServer(): void
    {
        $device = $this->option('ios') ? 'ios' : 'android';

        note(Str::headline("Starting Mobile {$device} App"));

        if (! File::exists(base_path('src-tauri/target'))) {
            Process::path('src-tauri')->forever()->run('cargo build');
        }

        if (! File::exists(base_path("src-tauri/gen/{$device}"))) {
            Process::forever()->run("npm run tauri {$device} init");
        }

        Process::forever()->run("npm run dev:tauri:mobile:{$device} -- --port=50005");
    }

    private function initViteServer(): void
    {
        note('Starting Vite Development Server');

        Process::start('npm run dev:vite:mobile');
    }

    private function initPHPServer(): void
    {
        note('Starting PHP Server');

        Process::start('php artisan serve --port=50000');
    }
}
