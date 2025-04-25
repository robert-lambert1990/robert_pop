<?php

namespace Drupal\music_providers;

use GuzzleHttp\Exception\RequestException;
use Drupal\Core\Url;
use Drupal\Core\Link;

final class Spotify extends MusicProvider {

  private const SPOTIFY_API_BASE_URL = 'https://api.spotify.com/v1';
  private const SPOTIFY_TOKEN_URL = 'https://accounts.spotify.com/api/token';

  protected function getSpotifyAuth(): string {
    $config = $this->configFactory->get('music_providers.spotify_settings');
    $client_id = $config->get('client_id');
    $client_secret = $config->get('client_secret');

    return base64_encode("$client_id:$client_secret");
  }

  protected function generateSpotifyAccessToken(): string {
    $auth = $this->getSpotifyAuth();

    try {
      $response = $this->httpClient->post(self::SPOTIFY_TOKEN_URL, [
        'headers' => [
          'Authorization' => 'Basic ' . $auth,
          'Content-Type' => 'application/x-www-form-urlencoded',
        ],
        'form_params' => [
          'grant_type' => 'client_credentials',
        ],
      ]);

      $data = json_decode($response->getBody(), true);
      return $data['access_token'] ?? throw new \RuntimeException('Access token not found in response.');

    } catch (RequestException $e) {
      throw new \RuntimeException('Failed to generate Spotify access token: ' . $e->getMessage());
    }
  }

  protected function spotifyApiRequest(string $endpoint, array $query = []): array {
    $accessToken = $this->generateSpotifyAccessToken();

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

  protected function fetchArtistData(string $type, string $value): ?array {

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

  public function fetchArtistInformation(string $artist_id): ?array {
    return $this->fetchArtistData('id', $artist_id);
  }

  public function fetchArtistInformationName(string $artist_name): ?array {
    return $this->fetchArtistData('name', $artist_name);
  }

  public function fetchArtistUrl(string $artist_id): ?string {
    if (empty($artist_id)) {
      throw new \InvalidArgumentException('Artist ID cannot be empty.');
    }

    $artist_information = $this->fetchArtistInformation($artist_id);
    $artist_name = strtolower(str_replace(' ', '-', $artist_information['name']));

    $url = Url::fromRoute('music_providers.artist_page', [
      'music_provider' => 'spotify',
      'artist_name' => $artist_name,
    ]);

    $link = Link::fromTextAndUrl($artist_information['name'], $url);
    return $link->toString();
  }
}
