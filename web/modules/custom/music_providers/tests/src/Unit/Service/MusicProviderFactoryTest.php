<?php

namespace Drupal\Tests\music_providers\Unit\Service;

use Drupal\music_providers\Service\MusicProviderFactory;
use Drupal\music_providers\Spotify;
use PHPUnit\Framework\TestCase;

class MusicProviderFactoryTest extends TestCase {

  public function testGetProvider() {
    $spotifyMock = $this->createMock(Spotify::class);
    $factory = new MusicProviderFactory($spotifyMock);

    $provider = $factory->getProvider('spotify');
    $this->assertInstanceOf(Spotify::class, $provider);
  }

  public function testInvalidProvider() {
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('Invalid music provider: invalid_provider');

    $spotifyMock = $this->createMock(Spotify::class);
    $factory = new MusicProviderFactory($spotifyMock);

    $factory->getProvider('invalid_provider');
  }
}
