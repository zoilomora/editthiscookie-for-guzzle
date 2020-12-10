<?php
declare(strict_types=1);

namespace ZoiloMora\EditThisCookie\Guzzle;

use GuzzleHttp\Cookie\SetCookie as Base;

final class SetCookie extends Base
{
    private int $defaultMaxAge;
    private ?array $original = null;

    public function __construct(array $data = [], int $defaultMaxAge = 0)
    {
        $this->defaultMaxAge = $defaultMaxAge;

        if (true === \array_key_exists('id', $data)) {
            $this->original = $data;
            $data = $this->convertToStandardFormat($data);
        }

        parent::__construct($data);
    }

    public function toArray(): array
    {
        if (null !== $this->original) {
            return $this->convertToEditThisCookieFormat();
        }

        return parent::toArray();
    }

    private function convertToStandardFormat(array $data): array
    {
        $cookie = [
            'Name' => $data['name'],
            'Value' => $data['value'],
            'Domain' => $data['domain'],
            'Path' => $data['path'],
            'Expires' => (int) $data['expirationDate'],
            'Secure' => $data['secure'],
            'HttpOnly' => $data['httpOnly'],
        ];

        if (0 === $cookie['Expires']) {
            $cookie['Max-Age'] = $this->defaultMaxAge;
        }

        return $cookie;
    }

    private function convertToEditThisCookieFormat(): array
    {
        return [
            'domain' => $this->getDomain(),
            'expirationDate' => $this->getExpires(),
            'httpOnly' => $this->getHttpOnly(),
            'name' => $this->getName(),
            'path' => $this->getPath(),
            'secure' => $this->getSecure(),
            'value' => $this->getValue(),
            'hostOnly' => $this->original['hostOnly'],
            'sameSite' => $this->original['sameSite'],
            'session' => $this->original['session'],
            'storeId' => $this->original['storeId'],
            'id' => $this->original['storeId'],
        ];
    }
}
