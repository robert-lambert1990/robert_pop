<?php

namespace Drupal\music_providers\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;

class ArtistController extends ControllerBase {

  public function viewArtist(string $id): Response {
    // Fetch artist information using the Spotify service.
    $spotify_service = \Drupal::service('music_providers.spotify');
    $artist_info = $spotify_service->fetchArtistInformation($id);

    if (!$artist_info) {
      return new Response('Artist not found', 404);
    }

    // Render artist information.
    $output = [
      '#theme' => 'artist_page',
      '#artist' => $artist_info,
    ];

    return $this->render($output);
  }

}
