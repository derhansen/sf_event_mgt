<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Service;

use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\RateLimiter\LimiterInterface;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use TYPO3\CMS\Core\Http\NormalizedParams;
use TYPO3\CMS\Core\RateLimiter\Storage\CachingFrameworkStorage;

readonly class RateLimiterService
{
    public function __construct(public CachingFrameworkStorage $cachingFrameworkStorage) {}

    public function isRequestRateLimited(
        ServerRequestInterface $request,
        string $identifier,
        array $rateLimiterSettings
    ): bool {
        if ((bool)($rateLimiterSettings['enabled'] ?? false) === false) {
            return false;
        }

        $rateLimiter = $this->getRateLimiter(
            $request,
            $identifier,
            (int)($rateLimiterSettings['limit'] ?? 5),
            $rateLimiterSettings['interval'] ?? '15 minutes'
        );

        $limit = $rateLimiter->consume();
        return !$limit->isAccepted();
    }

    protected function getRateLimiter(
        ServerRequestInterface $request,
        string $identifier,
        int $limit = 5,
        string $interval = '15 minutes'
    ): LimiterInterface {
        $config = [
            'id' => 'ext-sfeventmgt-' . $identifier,
            'policy' => 'sliding_window',
            'limit' => $limit,
            'interval' => $interval,
        ];

        $normalizedParams = $request->getAttribute('normalizedParams') ?? NormalizedParams::createFromRequest($request);
        $remoteIp = $normalizedParams->getRemoteAddress();

        $limiterFactory = new RateLimiterFactory(
            $config,
            $this->cachingFrameworkStorage
        );
        return $limiterFactory->create($remoteIp);
    }
}