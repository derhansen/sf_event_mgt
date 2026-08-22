<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Security;

use TYPO3Fluid\Fluid\Core\Cache\FluidCacheInterface;

/**
 * A no-op Fluid cache implementation that never stores or returns compiled templates.
 * Used in parseStringFluid() to ensure templates are always parsed from source,
 * so that AllowedViewHelperInvoker is invoked on every render.
 */
class NullFluidCache implements FluidCacheInterface
{
    public function get(string $name): mixed
    {
        return null;
    }

    public function set(string $name, mixed $value): void {}

    public function flush(?string $name = null): void {}
}
