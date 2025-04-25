<?php

namespace Drupal\music_providers;

use GuzzleHttp\Exception\RequestException;
use Drupal\Core\Url;

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

  protected function spotifyApiConnection(string $artist_id): array {
    $accessToken = $this->generateSpotifyAccessToken();

    try {

      $response = $this->httpClient->get(self::SPOTIFY_API_BASE_URL . "/artists/$artist_id", [
        'headers' => [
          'Authorization' => "Bearer $accessToken",
        ],
      ]);

      return json_decode($response->getBody(), true);

    } catch (RequestException $e) {

      throw new \RuntimeException('Failed to connect to Spotify API: ' . $e->getMessage());

    }
  }

  public function fetchArtistUrl(string $artist_id): ?string {

    if (empty($artist_id)) {

      throw new \InvalidArgumentException('Artist ID cannot be empty.');

    }

    $artist_information = $this->fetchArtistInformation($artist_id);

    $url = Url::fromRoute('music_providers.artist_page', ['id' => $artist_id]);
    $link = \Drupal\Core\Link::fromTextAndUrl($artist_information['name'], $url);
    return $link->toString();

  }

  public function fetchArtistInformation(string $artist_id): ?array {

    if (empty($artist_id)) {

      throw new \InvalidArgumentException('Artist ID cannot be empty.');

    }

    $api_connection = $this->spotifyApiConnection($artist_id);

    return [

      'name' => $api_connection['name'] ?? null,
      'url' => $api_connection['external_urls']['spotify'] ?? null,
      'id' => $api_connection['id'] ?? null,
      'image' => $api_connection['images'][0]['url'] ?? null,
      'genres' => $api_connection['genres'] ?? null,

    ];

  }
}
