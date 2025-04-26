<?php

namespace Drupal\Tests\music_providers\Unit;

use Drupal\music_providers\Spotify;
use Drupal\music_providers\Service\SpotifyAuthService;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;

class SpotifyTest extends TestCase {

  public function testFetchArtistDataWithValidName() {
    $httpClientMock = $this->createMock(ClientInterface::class);
    $configFactoryMock = $this->createMock(\Drupal\Core\Config\ConfigFactoryInterface::class);
    $currentUserMock = $this->createMock(\Drupal\Core\Session\AccountProxyInterface::class);
    $authServiceMock = $this->createMock(SpotifyAuthService::class);

    $spotify = new Spotify($httpClientMock, $configFactoryMock, $currentUserMock, $authServiceMock);

    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('Name cannot be empty.');

    $spotify->fetchArtistData('name', '');
  }

  public function testFetchArtistDataWithInvalidType() {
    $httpClientMock = $this->createMock(ClientInterface::class);
    $configFactoryMock = $this->createMock(\Drupal\Core\Config\ConfigFactoryInterface::class);
    $currentUserMock = $this->createMock(\Drupal\Core\Session\AccountProxyInterface::class);
    $authServiceMock = $this->createMock(SpotifyAuthService::class);

    $spotify = new Spotify($httpClientMock, $configFactoryMock, $currentUserMock, $authServiceMock);

    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('Invalid type provided.');

    $spotify->fetchArtistData('invalid_type', 'value');
  }
}
