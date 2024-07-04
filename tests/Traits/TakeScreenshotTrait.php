<?php declare(strict_types=1);

namespace Tests\Traits;

use Exception;
use Spatie\Browsershot\Browsershot;
use Tests\Helpers\SessionCookie;

trait TakeScreenshotTrait
{
    protected function takeScreenshot(string $url): void
    {
        $filename       = $this->buildScreenshotFilename();
        $domain         = env('APP_DOMAIN') . (strlen(env('APP_PORT')) > 0 ? ':' . env('APP_PORT') : '');
        $beforeChecksum = is_file($filename) ? hash_file('sha256', $filename) : null;

        Browsershot::
            url('http://' . $domain . $url)
            ->noSandbox()
            ->windowSize(1400, 1000)
            ->setChromePath('/usr/lib/chromium/chromium')
            ->useCookies((new SessionCookie)->build())
            ->newHeadless()
            ->save($filename);

        $afterChecksum = hash_file('sha256', $filename);
        $this->matchScreenshot($beforeChecksum, $afterChecksum);
    }

    protected function matchScreenshot(?string $beforeChecksum, ?string $afterChecksum): void
    {
        if (env('CI_ENV') === true) {
            if (is_null($beforeChecksum)) {
                throw new Exception('Can\'t find reference screenshot in GitHub repo');
            }
            if (is_null($afterChecksum)) {
                throw new Exception('Can\'t find newly generated screenshot');
            }

            // generate diff file
            if ($beforeChecksum !== $afterChecksum) {
                throw new Exception('Found old screenshots, generate new ones locally and push to GitHub');
            }
        }
        $this->assertTrue(true);
    }

    protected function buildScreenshotFilename(): string
    {
        $folderName = 'images' . '/' . str_replace('\\', '_', get_class($this));
        $fileName   = implode('_', array_merge([$this->name()], func_get_args(), [php_uname('m')]));

        if (! file_exists(storage_path($folderName))) {
            mkdir(storage_path($folderName));
        }
        return storage_path($folderName . '/' . $fileName . '.png');
    }
}
