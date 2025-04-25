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
  public function viewArtist(string $id): array {

    $music_provider = $this->musicProviderFactory->getProvider('spotify');
    $artist_info = $music_provider->fetchArtistInformation($id);

    return [
      '#theme' => 'artist_page',
      '#artist' => $artist_info,
    ];
  }

}
