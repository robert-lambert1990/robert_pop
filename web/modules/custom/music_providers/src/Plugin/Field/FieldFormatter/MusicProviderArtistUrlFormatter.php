<?php

namespace Drupal\music_providers\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\music_providers\Service\MusicProviderFactory;

/**
 * Plugin implementation of the 'music_provider_artist_url' formatter.
 *
 * @FieldFormatter(
 *   id = "music_provider_artist_url",
 *   label = @Translation("Artist URL"),
 *   field_types = {
 *     "music_provider"
 *   }
 * )
 */
class MusicProviderArtistUrlFormatter extends FormatterBase
{

  /**
   * The music provider factory service.
   *
   * @var \Drupal\music_providers\Service\MusicProviderFactory
   */
  protected $musicProviderFactory;

  /**
   * Constructs a MusicProviderArtistUrlFormatter object.
   *
   * @param string $plugin_id
   *   The plugin ID for the formatter.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The field definition.
   * @param array $settings
   *   The formatter settings.
   * @param string $label
   *   The formatter label.
   * @param string $view_mode
   *   The view mode.
   * @param array $third_party_settings
   *   Any third-party settings.
   * @param \Drupal\music_providers\Service\MusicProviderFactory $musicProviderFactory
   *   The music provider factory service.
   */
  public function __construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings, MusicProviderFactory $musicProviderFactory)
  {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);
    $this->musicProviderFactory = $musicProviderFactory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
  {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings'],
      $container->get('music_providers.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode)
  {
    $elements = [];

    foreach ($items as $delta => $item) {

      $music_provider = $this->musicProviderFactory->getProvider($item->value);
      $artist_url = $music_provider->fetchArtistUrl($item->artist_id);

      $elements[$delta] = [
        '#markup' => $artist_url,
      ];


      return $elements;
    }
  }
}
