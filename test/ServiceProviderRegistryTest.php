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
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(ServiceProviderRegistry::class)]
class ServiceProviderRegistryTest extends TestCase
{
    private MockObject $containerBuilderMock;
    private MockObject $containerMock;
    private MockObject $serviceProviderMock;
    private ServiceProviderRegistry $services;

    #[Before]
    public function setUp(): void
    {
        $this->containerBuilderMock = $this->getMockBuilder(ContainerBuilderInterface::class)->getMock();
        $this->containerMock = $this->getMockBuilder(ContainerInterface::class)->getMock();
        $this->services = new ServiceProviderRegistry($this->containerBuilderMock);
        $this->serviceProviderMock = $this->getMockBuilder(ServiceProviderInterface::class)->getMock();
    }

    #[Test]
    public function shouldConfigureRegisteredServiceProvider(): void
    {
        $this->serviceProviderMock
            ->expects($this->once())
            ->method('configure')
            ->with($this->containerBuilderMock);

        $this->services->register($this->serviceProviderMock);
    }

    #[Test]
    public function registerShouldThrowIfAlreadyLoaded(): void
    {
        $this->expectException(ServiceProvidersAlreadyLoadedException::class);

        $this->services->load($this->containerMock);

        $this->services->register($this->serviceProviderMock);
    }

    #[Test]
    public function shouldLoadAllRegisteredServiceProviders(): void
    {
        $serviceProviderMock = $this->getMockBuilder(ServiceProviderInterface::class)->getMock();

        $this->services->register($this->serviceProviderMock);
        $this->services->register($serviceProviderMock);

        $this->serviceProviderMock
            ->expects($this->once())
            ->method('load')
            ->with($this->containerMock);
        $serviceProviderMock
            ->expects($this->once())
            ->method('load')
            ->with($this->containerMock);

        $this->services->load($this->containerMock);
    }

    #[Test]
    public function loadShouldThrowIfAlreadyLoaded(): void
    {
        $this->expectException(ServiceProvidersAlreadyLoadedException::class);

        $this->services->load($this->containerMock);
        $this->services->load($this->containerMock);
    }
}
