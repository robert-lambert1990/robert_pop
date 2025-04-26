<?php

namespace Drupal\music_providers;

use Drupal\Core\Config\ConfigFactoryInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\music_providers\Service\SpotifyAuthService;
final class Spotify extends MusicProvider {

  protected $authService;

  private const SPOTIFY_API_BASE_URL = 'https://api.spotify.com/v1';
  public function __construct(ClientInterface $httpClient, ConfigFactoryInterface $configFactory, AccountProxyInterface $currentUser, SpotifyAuthService $authService) {
    parent::__construct($httpClient, $configFactory, $currentUser);
    $this->authService = $authService;
  }
  protected function spotifyApiRequest(string $endpoint, array $query = []): array {

    $accessToken = $this->authService->generateAccessToken();

    try {
      $response = $this->httpClient->get(self::SPOTIFY_API_BASE_URL . $endpoint, [
        'headers' => [
          'Authorization' => "Bearer $accessToken",
        ],
        'query' => $query,
      ]);

      return json_decode($response->getBody(), true);

    } catch (RequestException $e) {
      throw new \RuntimeException('Failed to connect to Spotify API: ' . $e->getMessage());
    }
  }

  public function fetchArtistData(string $type, string $value): ?array {

    if (empty($value)) {
      throw new \InvalidArgumentException(ucfirst($type) . ' cannot be empty.');
    }

    $endpoint = $type === 'id' ? "/artists/$value" : "/search";
    $query = $type === 'name' ? ['q' => str_replace('-', ' ', $value), 'type' => 'artist', 'limit' => 1] : [];

    $api_connection = $this->spotifyApiRequest($endpoint, $query);

    $artist_data = $type === 'name'
      ? $api_connection['artists']['items'][0] ?? null
      : $api_connection;

    if (!$artist_data) {
      return null;
    }

    return $this->normalizeArtistData(
      $artist_data['name'] ?? '',
      $artist_data['external_urls']['spotify'] ?? '',
      $artist_data['id'] ?? '',
      $artist_data['images'][0]['url'] ?? '',
      $artist_data['genres'] ?? []
    );
  }

  public function fetchArtistUrl(string $artist_id): ?string {
    if (empty($artist_id)) {
      throw new \InvalidArgumentException('Artist ID cannot be empty.');
    }

    $artist_information = $this->fetchArtistData('id', $artist_id);
    if (!$artist_information) {
      return null;
    }

    return $this->generateArtistUrl('spotify', $artist_information['name']);
  }
}
