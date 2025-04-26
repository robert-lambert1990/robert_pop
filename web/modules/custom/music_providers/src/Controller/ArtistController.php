<?php

namespace Drupal\music_providers\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\music_providers\Service\MusicProviderFactory;

class ArtistController extends ControllerBase {

  protected $musicProviderFactory;
  public function __construct(MusicProviderFactory $musicProviderFactory) {

    $this->musicProviderFactory = $musicProviderFactory;

  }
  public static function create(ContainerInterface $container): self {

    return new static(
      $container->get('music_providers.factory')
    );

  }
  public function viewArtist(string $music_provider, string $artist_name): array {

    $music_provider = $this->musicProviderFactory->getProvider($music_provider);
    $artist_info = $music_provider->fetchArtistData('name', $artist_name);

    return [
      '#theme' => 'artist_page',
      '#artist' => $artist_info,
    ];
  }

}
