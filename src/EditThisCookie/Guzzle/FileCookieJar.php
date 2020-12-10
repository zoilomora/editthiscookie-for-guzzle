<?php
declare(strict_types=1);

namespace ZoiloMora\EditThisCookie\Guzzle;

use GuzzleHttp\Cookie\FileCookieJar as Base;
use GuzzleHttp\Utils;

final class FileCookieJar extends Base
{
    private int $defaultMaxAge;

    public function __construct(string $cookieFile, bool $storeSessionCookies = false, int $defaultMaxAge = 0)
    {
        $this->defaultMaxAge = $defaultMaxAge;

        parent::__construct($cookieFile, $storeSessionCookies);
    }

    public function load($filename): void
    {
        $json = \file_get_contents($filename);

        if (false === $json) {
            throw new \RuntimeException("Unable to load file {$filename}");
        }

        if ('' === $json) {
            return;
        }

        $data = Utils::jsonDecode($json, true);

        if (\is_scalar($data) && 0 !== \count($data)) {
            throw new \RuntimeException("Invalid cookie file: {$filename}");
        }

        if (\is_array($data)) {
            foreach ($data as $cookie) {
                $this->setCookie(new SetCookie($cookie, $this->defaultMaxAge));
            }
        }

        return;
    }
}
