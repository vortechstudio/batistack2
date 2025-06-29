<?php

declare(strict_types=1);

namespace App\Console\Commands\Server;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

use function Laravel\Prompts\intro;
use function Laravel\Prompts\note;

final class ServeDesktopCommand extends Command
{
    protected $signature = 'serve:desktop';

    protected $description = 'Command description';

    public function handle(): void
    {
        intro('Running Desktop Environment');

        $this->initViteServer();
        $this->initPHPServer();
        $this->initTauriServer();
    }

    private function initTauriServer(): void
    {
        note('Starting Desktop App');

        if (! File::exists(base_path('src-tauri/target'))) {
            Process::path('src-tauri')->forever()->run('cargo build');
        }

        Process::forever()->run('npm run dev:tauri:desktop -- --port=50003');
    }

    private function initViteServer(): void
    {
        note('Starting Vite Development Server');

        Process::start('npm run dev:vite:desktop');
    }

    private function initPHPServer(): void
    {
        note('Starting PHP Server');

        Process::start('php artisan serve --port=50000');
    }
}
