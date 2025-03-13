<?php

/*
 * Copyright (c) 2025 Martin Pettersson
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace N7e;

use N7e\DependencyInjection\ContainerBuilderInterface;
use N7e\DependencyInjection\ContainerInterface;

/**
 * Has the ability to provide specific functionality.
 */
interface ServiceProviderInterface
{
    /**
     * Configure given container builder.
     *
     * @param \N7e\DependencyInjection\ContainerBuilderInterface $containerBuilder Container builder instance.
     */
    public function configure(ContainerBuilderInterface $containerBuilder): void;

    /**
     * Provide functionality.
     *
     * @param \N7e\DependencyInjection\ContainerInterface $container Configured dependency injection container.
     */
    public function load(ContainerInterface $container): void;
}
