<?php

namespace Drupal\music_providers;

use GuzzleHttp\ClientInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use GuzzleHttp\Exception\RequestException;

class SpotifyAuthService {

  private const SPOTIFY_TOKEN_URL = 'https://accounts.spotify.com/api/token';

  protected ClientInterface $httpClient;
  protected ConfigFactoryInterface $configFactory;

  public function __construct(ClientInterface $httpClient, ConfigFactoryInterface $configFactory) {
    $this->httpClient = $httpClient;
    $this->configFactory = $configFactory;
  }

  protected function getSpotifyAuth(): string {
    $config = $this->configFactory->get('music_providers.spotify_settings');
    $client_id = $config->get('client_id');
    $client_secret = $config->get('client_secret');

    return base64_encode("$client_id:$client_secret");
  }

  public function generateAccessToken(): string {
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
}
