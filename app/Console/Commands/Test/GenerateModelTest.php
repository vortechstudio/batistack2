<?php

declare(strict_types=1);

namespace App\Console\Commands\Test;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use SplFileInfo;

final class GenerateModelTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-model-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function configure()
    {
        $this->setAliases(['gmtest']);
        parent::configure();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $files = File::allFiles(base_path('app/Models'));

        collect($files)->each(function (SplFileInfo $file) {
            $modelRelativePathName = str($file->getRelativePathname())->remove('.php');
            $path = $modelRelativePathName
                ->prepend(base_path('tests/Feature/Modules/'))
                ->append('Test.php')
                ->toString();

            File::ensureDirectoryExists(dirname($path));

            if (File::exists($path)) {
                return;
            }

            $content = '<?php'.PHP_EOL.PHP_EOL.'declare(strict_type=1);'.PHP_EOL.PHP_EOL."use Illuminate\Foundation\Testing\RefreshDatabase;".PHP_EOL.PHP_EOL.'uses(RefreshDatabase::class);'.PHP_EOL.PHP_EOL."describe('".basename((string) $modelRelativePathName)." Model', function(){});";
            File::put($path, $content);
        });
    }
}
