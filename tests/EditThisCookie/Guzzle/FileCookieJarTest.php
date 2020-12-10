<?php
declare(strict_types=1);

namespace ZoiloMora\Tests\EditThisCookie\Guzzle;

use GuzzleHttp\Cookie\CookieJarInterface;
use PHPUnit\Framework\TestCase;
use ZoiloMora\EditThisCookie\Guzzle\FileCookieJar;

final class FileCookieJarTest extends TestCase
{
    private const ORIGINAL_COOKIES_FILE = __DIR__ . '/EditThisCookie.json';
    private const TEMP_COOKIES_FILE = __DIR__ . '/../../../var/EditThisCookie.json';

    private const DEFAULT_MAX_AGE = 100;

    /** @test */
    public function given_a_edit_this_cookie_file_when_instance_a_file_cookie_jar_then_cookies_have_been_read()
    {
        for ($i = 0; $i < 10; $i++) {
            $this->myTest();
        }
    }

    protected function setUp(): void
    {
        parent::setUp();

        \copy(self::ORIGINAL_COOKIES_FILE, self::TEMP_COOKIES_FILE);
    }

    private function myTest(): void
    {
        $fileCookieJar = $this->getFileCookieJar();
        $cookies = $this->getArrayEditThisCookies();

        $this->assertSame(\count($cookies), $fileCookieJar->count());

        unset($fileCookieJar);
    }

    private function getFileCookieJar(): CookieJarInterface
    {
        return new FileCookieJar(
            self::TEMP_COOKIES_FILE,
            false,
            self::DEFAULT_MAX_AGE,
        );
    }

    private function getArrayEditThisCookies(): array
    {
        $file = \file_get_contents(self::ORIGINAL_COOKIES_FILE);

        return \json_decode($file, true);
    }
}
