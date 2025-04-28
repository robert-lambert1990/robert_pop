<?php

namespace Drupal\music_providers\Service;

use Drupal\music_providers\MusicProviderInterface;

class MusicProviderFactory {

  protected MusicProviderInterface $provider;

  public function __construct(MusicProviderInterface $provider) {
    $this->provider = $provider;
  }

  public function getProvider(string $provider): MusicProviderInterface {
    switch ($provider) {
      case 'spotify':
        return $this->provider;
      default:
        throw new \InvalidArgumentException("Invalid music provider: $provider");
    }
  }
}
