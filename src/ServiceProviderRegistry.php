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
 * Represents a collection of service providers.
 */
final class ServiceProviderRegistry
{
    /**
     * Container builder instance.
     *
     * @var \N7e\DependencyInjection\ContainerBuilderInterface
     */
    private readonly ContainerBuilderInterface $containerBuilder;

    /**
     * Registered service providers.
     *
     * @var \N7e\ServiceProviderInterface[]
     */
    private array $serviceProviders;

    /**
     * Semaphore used to determine whether service providers has been loaded.
     *
     * @var bool
     */
    private bool $hasLoadedServiceProviders;

    /**
     * Create a new service provider registry instance.
     *
     * @param \N7e\DependencyInjection\ContainerBuilderInterface $containerBuilder Container builder instance.
     */
    public function __construct(ContainerBuilderInterface $containerBuilder)
    {
        $this->containerBuilder = $containerBuilder;
        $this->serviceProviders = [];
        $this->hasLoadedServiceProviders = false;
    }

    /**
     * Determine whether service providers have been loaded.
     *
     * @return bool True if service providers have been loaded.
     */
    public function hasLoaded(): bool
    {
        return $this->hasLoadedServiceProviders;
    }

    /**
     * Register given service provider.
     *
     * @param \N7e\ServiceProviderInterface $serviceProvider Arbitrary service provider.
     * @throws \N7e\ServiceProvidersAlreadyLoadedException If service providers have already been loaded.
     */
    public function register(ServiceProviderInterface $serviceProvider): void
    {
        if ($this->hasLoadedServiceProviders) {
            throw new ServiceProvidersAlreadyLoadedException();
        }

        $this->serviceProviders[] = $serviceProvider;

        $serviceProvider->configure($this->containerBuilder);
    }

    /**
     * Load all registered service providers.
     *
     * @note This can only be done once and will throw an exception if attempted multiple times.
     * @param \N7e\DependencyInjection\ContainerInterface $container
     * @throws \N7e\ServiceProvidersAlreadyLoadedException If service providers have already been loaded.
     */
    public function load(ContainerInterface $container): void
    {
        if ($this->hasLoadedServiceProviders) {
            throw new ServiceProvidersAlreadyLoadedException();
        }

        foreach ($this->serviceProviders as $serviceProvider) {
            $serviceProvider->load($container);
        }

        $this->hasLoadedServiceProviders = true;
    }
}
