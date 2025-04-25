<?php

namespace Drupal\music_providers\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'music_provider' field type.
 *
 * @FieldType(
 *   id = "music_provider",
 *   label = @Translation("Music Provider"),
 *   description = @Translation("A field to select a music provider and specify an artist ID."),
 *   default_widget = "music_provider_select",
 *   default_formatter = "string"
 * )
 */
class MusicProviderItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_storage_definition) {
    return [
      'columns' => [
        'value' => [
          'type' => 'varchar',
          'length' => 255,
        ],
        'artist_id' => [
          'type' => 'varchar',
          'length' => 255,
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_storage_definition) {
    $properties['value'] = DataDefinition::create('string')
      ->setLabel(t('Music Provider'))
      ->setRequired(TRUE);

    $properties['artist_id'] = DataDefinition::create('string')
      ->setLabel(t('Artist ID'))
      ->setRequired(FALSE);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $value = $this->get('value')->getValue();
    $artist_id = $this->get('artist_id')->getValue();
    return $value === null || $value === '' || $artist_id === null || $artist_id === '';
  }
}
