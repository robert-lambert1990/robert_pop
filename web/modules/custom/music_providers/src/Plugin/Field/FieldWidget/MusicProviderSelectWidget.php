<?php

namespace Drupal\music_providers\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\music_providers\Service\MusicProviderFactory;

/**
 * Plugin implementation of the 'music_provider_select' widget.
 *
 * @FieldWidget(
 *   id = "music_provider_select",
 *   label = @Translation("Music Provider Select"),
 *   field_types = {
 *     "music_provider"
 *   }
 * )
 */
class MusicProviderSelectWidget extends WidgetBase {

  protected $musicProviderFactory;

  public function __construct($plugin_id, $plugin_definition, $field_definition, $settings, $third_party_settings, MusicProviderFactory $musicProviderFactory) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $third_party_settings);
    $this->musicProviderFactory = $musicProviderFactory;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['third_party_settings'],
      $container->get('music_providers.factory')
    );
  }

  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $options = [
      'spotify' => 'Spotify',
    ];

    $element['value'] = [
      '#type' => 'select',
      '#title' => $this->t('Music Provider'),
      '#options' => $options,
      '#default_value' => $items[$delta]->value ?? null,
    ];

    $element['artist_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Artist ID'),
      '#default_value' => $items[$delta]->artist_id ?? '',
    ];

    return $element;
  }
}
