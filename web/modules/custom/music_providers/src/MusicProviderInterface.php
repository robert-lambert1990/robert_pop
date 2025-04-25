<?php

namespace Drupal\music_providers;

interface MusicProviderInterface {

  public function fetchArtistUrl(string $artist_id): ?string;

}
