<?php

namespace Drupal\music_providers;

use Drupal\music_providers\MusicProviderInterface;
use GuzzleHttp\ClientInterface;

final class Spotify implements MusicProviderInterface {



  protected function generateSpotifyAccessToken() {
    $client = \Drupal::httpClient();
    $config = \Drupal::config('music_providers.spotify_settings');
    $clientId = $config->get('client_id');
    $clientSecret = $config->get('client_secret');

    $response = $client->post('https://accounts.spotify.com/api/token', [
      'headers' => [
        'Authorization' => 'Basic ' . base64_encode("$clientId:$clientSecret"),
        'Content-Type' => 'application/x-www-form-urlencoded',
      ],
      'form_params' => [
        'grant_type' => 'client_credentials',
      ],
    ]);

    return json_decode($response->getBody(), true)['access_token'];
  }

  protected function spotifyApiConnection($artist_id) {
    $client = \Drupal::httpClient();
    $accessToken = $this->generateSpotifyAccessToken();

    $response =  $client->get("https://api.spotify.com/v1/artists/$artist_id", [
      'headers' => [
        'Authorization' => "Bearer $accessToken",
      ],
    ]);

    return json_decode($response->getBody(), true);
  }


  //Need to validate the artist id is an acceptable string

  public function fetchArtistUrl($artist_id = '') {

    //For testing

    if (!$artist_id) {
      $artist_id = '1Xyo4u8uXC1ZmMpatF05PJ';
    }

    $api_connection = $this->spotifyApiConnection($artist_id);
    $spotifyUrl = $api_connection['external_urls']['spotify'] ?? null;
    //Return artist url from response

    return $spotifyUrl;

  }
}
