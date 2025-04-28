<?php

namespace Drupal\Tests\music_providers\Unit\Service;

use Drupal\music_providers\Service\MusicProviderFactory;
use Drupal\music_providers\MusicProviderInterface;
use PHPUnit\Framework\TestCase;

class MusicProviderFactoryTest extends TestCase {

  public function testGetProvider() {
    $providerMock = $this->createMock(MusicProviderInterface::class);
    $factory = new MusicProviderFactory($providerMock);

    $provider = $factory->getProvider('spotify');
    $this->assertInstanceOf(MusicProviderInterface::class, $provider);
  }

  public function testInvalidProvider() {
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('Invalid music provider: invalid_provider');

    $providerMock = $this->createMock(MusicProviderInterface::class);
    $factory = new MusicProviderFactory($providerMock);

    $factory->getProvider('invalid_provider');
  }
}
