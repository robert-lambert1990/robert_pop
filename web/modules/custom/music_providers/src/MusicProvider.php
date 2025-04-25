<?php

namespace Drupal\music_providers;

use GuzzleHttp\ClientInterface;
use Drupal\Core\Config\ConfigFactoryInterface;

abstract class MusicProvider implements MusicProviderInterface {

  protected ClientInterface $httpClient;
  protected ConfigFactoryInterface $configFactory;

  public function __construct(ClientInterface $httpClient, ConfigFactoryInterface $configFactory) {
    $this->httpClient = $httpClient;
    $this->configFactory = $configFactory;
  }

  protected function normalizeArtistData(string $name, string $url, string $id, string $image, array $genres = []): array {
    return [
      'name' => $name,
      'url' => $url,
      'id' => $id,
      'image' => $image,
      'genres' => $genres,
    ];
  }

}
