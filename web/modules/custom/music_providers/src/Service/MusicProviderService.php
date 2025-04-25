<?php

namespace Drupal\music_providers\Service;

use Drupal\music_providers\MusicProviderInterface;

class MusicProviderService {

  protected $musicProvider;

  public function __construct(MusicProviderInterface $musicProvider) {
    $this->musicProvider = $musicProvider;
  }

  public function fetchArtistUrl($artist_id) {
    return $this->musicProvider->fetchArtistUrl($artist_id);
  }
}
