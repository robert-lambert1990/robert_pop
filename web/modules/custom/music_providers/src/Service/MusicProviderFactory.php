<?php

namespace Drupal\music_providers\Service;

use Drupal\music_providers\MusicProviderInterface;

class MusicProviderFactory {

  protected $spotify;
  public function __construct(MusicProviderInterface $spotify) {
    $this->spotify = $spotify;
  }

  public function getProvider(string $provider): MusicProviderInterface {
    switch ($provider) {
      case 'spotify':
        return $this->spotify;
      default:
        throw new \InvalidArgumentException("Invalid music provider: $provider");
    }
  }
}
