<?php

namespace Drupal\music_providers;

final class Spotify extends MusicProvider {

  protected function getSpotifyAuth() {
    $config = $this->configFactory->get('music_providers.spotify_settings');
    $client_id = $config->get('client_id');
    $client_secret = $config->get('client_secret');

    return base64_encode("$client_id:$client_secret");
  }

  protected function generateSpotifyAccessToken() {
    $auth = $this->getSpotifyAuth();

    $response = $this->httpClient->post('https://accounts.spotify.com/api/token', [
      'headers' => [
        'Authorization' => 'Basic ' . $auth,
        'Content-Type' => 'application/x-www-form-urlencoded',
      ],
      'form_params' => [
        'grant_type' => 'client_credentials',
      ],
    ]);

    return json_decode($response->getBody(), true)['access_token'];
  }

  protected function spotifyApiConnection($artist_id) {
    $accessToken = $this->generateSpotifyAccessToken();

    $response = $this->httpClient->get("https://api.spotify.com/v1/artists/$artist_id", [
      'headers' => [
        'Authorization' => "Bearer $accessToken",
      ],
    ]);

    return json_decode($response->getBody(), true);
  }

  public function fetchArtistUrl($artist_id = '') {
    $api_connection = $this->spotifyApiConnection($artist_id);
    $spotifyUrl = $api_connection['external_urls']['spotify'] ?? null;

    return $spotifyUrl;
  }
}
