<?php

namespace Drupal\music_providers;

use Drupal\Core\Link;
use Drupal\Core\Url;
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

  protected function generateArtistUrl(string $provider, string $artist_name): string {
    $current_user = \Drupal::currentUser();

    if (!$current_user->isAuthenticated()) {
      return $artist_name;
    }

    $artist_name_formatted = strtolower(str_replace(' ', '-', $artist_name));
    $url = Url::fromRoute('music_providers.artist_page', [
      'music_provider' => $provider,
      'artist_name' => $artist_name_formatted,
    ]);
    $link = Link::fromTextAndUrl($artist_name, $url);
    return $link->toString();
  }

}
