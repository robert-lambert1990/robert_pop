<?php

namespace Drupal\music_providers;

use GuzzleHttp\ClientInterface;

class Spotify {

  protected $clientId = '1bdcfdbc8f6d430f873dc8a00fa8bba5';
  protected $clientSecret = '2c7d5a7099894ed883d419f9003d4af3';


  protected function generateSpotifyAccessToken() {

    $client = \Drupal::httpClient();

    $response = $client->post('https://accounts.spotify.com/api/token', [
      'headers' => [
        'Authorization' => 'Basic ' . base64_encode("$this->clientId:$this->clientSecret"),
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
    $client = \Drupal::httpClient();
    $response =  $client->get("https://api.spotify.com/v1/artists/$artist_id", [
      'headers' => [
        'Authorization' => "Bearer $accessToken",
      ],
    ]);

    return json_decode($response->getBody(), true);
  }

  public function getArtistUrl($artist_id = '') {
    if (!$artist_id) {
      $artist_id = '1Xyo4u8uXC1ZmMpatF05PJ';
    }

    $api_connection = $this->spotifyApiConnection($artist_id);
    return $api_connection['external_urls']['spotify'] ?? null;
  }
}
