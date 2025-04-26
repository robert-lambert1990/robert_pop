<?php

namespace Drupal\music_providers;

interface MusicProviderInterface {

  public function fetchArtistUrl(string $artist_id): ?string;
  public function fetchArtistData(string $type, string $value): ?array;

}
