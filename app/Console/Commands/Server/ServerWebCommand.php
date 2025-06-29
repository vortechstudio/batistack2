<?php

namespace App\Console\Commands\Server;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

use function Laravel\Prompts\intro;
use function Laravel\Prompts\note;

class ServerWebCommand extends Command
{
    protected $signature = 'serve:web';

    protected $description = 'Command description';

    public function handle()
    {
        intro('Running Web Environment');

        $this->initViteServer();
        $this->initPHPServer();
    }

    private function initViteServer(): void
    {
        note('Starting Vite Development Server');

        Process::start('npm run dev:vite:web');
    }

    private function initPHPServer(): void
    {
        note('Starting PHP Server');

        Process::forever()->run('php artisan serve --port=50000');
    }
}
