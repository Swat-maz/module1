<?php

namespace Drupal\swat\Entity;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\user\EntityOwnerInterface;
use Drupal\user\EntityOwnerTrait;

/**
 * @ContentEntityType(
 *   id = "swat",
 *   label = @Translation("Swat"),
 *   label_collection = @Translation("Swats"),
 *   label_singular = @Translation("swat"),
 *   label_plural = @Translation("swats"),
 *   base_table = "swat",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "label" = "title",
 *     "owner" = "author",
 *     "published" = "published",
 *   },
 *   handlers = {
 *     "access" = "Drupal\entity\EntityAccessControlHandler",
 *     "form" = {
 *       "add" = "Drupal\swat\Form\SwatForm",
 *       "edit" = "Drupal\swat\Form\SwatForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *     },
 *     "list_builder" = "Drupal\Core\Entity\EntityListBuilder",
 *     "local_action_provider" = {
 *       "collection" = "Drupal\entity\Menu\EntityCollectionLocalActionProvider",
 *     },
 *     "permission_provider" = "Drupal\entity\EntityPermissionProvider",
 *     "route_provider" = {
 *       "default" = "Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   links = {
 *     "canonical" = "/swat/{swat}",
 *     "collection" = "/admin/content/swats",
 *     "add-form" = "/admin/content/swats/add",
 *     "edit-form" = "/admin/content/swats/manage/{swat}",
 *     "delete-form" = "/admin/content/swats/manage/{swat}/delete",
 *   },
 *   admin_permission = "access content"
 * )
 */
class Swat extends ContentEntityBase implements EntityOwnerInterface, EntityPublishedInterface {

  use EntityOwnerTrait, EntityPublishedTrait;

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    // Get the field definitions for 'id' and 'uuid' from the parent.
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Your name'))
      ->setRequired(TRUE)
      ->setDisplayOptions('form', ['weight' => 0]);

    $fields['date'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Date'))
      ->setRequired(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'settings' => [
          'format_type' => 'html_date',
        ],
        'weight' => 0,
      ]);

    $fields['email'] = BaseFieldDefinition::create('email')
      ->setLabel(t('Email'))
      ->setRequired(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'settings' => [
          'format_type' => 'email',
        ],
        'weight' => 0,
      ])
      ->setDisplayOptions('form', ['weight' => 15]);

    $fields['tel'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Telephone'))
      ->setRequired(TRUE)
      ->setDescription(t('Start entering the phone number after + XX XXX XXX XX XX'))
      ->setSettings(array(
        'max_length' => 12,
      ))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'settings' => [
          'placeholder' => '112223333333',
          'format_type' => 'telephone',
        ],
        'weight' => 0,
      ])
      ->setDisplayOptions('form', ['weight' => 17]);

    $fields['feedback'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Feedback'))
      ->setRequired(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'weight' => 10,
      ])
      ->setDisplayOptions('form', ['weight' => 20]);

    $fields['avatar'] = BaseFieldDefinition::create('image')
      ->setLabel(t('Your avatar photo'))
      ->setRequired(TRUE)
      ->setSettings(array(
        'file_extensions' => 'png jpg jpeg',
        'max_filesize' => '2097152',
      ))
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'weight' => 10,
      ])
      ->setDisplayOptions('form', ['weight' => 25]);

    $fields['photo'] = BaseFieldDefinition::create('image')
      ->setLabel(t('Your photo for feedback'))
      ->setRequired(TRUE)
      ->setSettings(array(
        'file_extensions' => 'png jpg jpeg',
        'max_filesize' => '5242880',
      ))
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'weight' => 10,
      ])
      ->setDisplayOptions('form', ['weight' => 30]);

    // Get the field definitions for 'owner' and 'published' from the traits.
    $fields += static::ownerBaseFieldDefinitions($entity_type);
    $fields += static::publishedBaseFieldDefinitions($entity_type);

//    $fields['published']->setDisplayOptions('form', [
//      'settings' => [
//        'display_label' => TRUE,
//      ],
//      'weight' => 30,
//    ]);

    return $fields;
  }

  /**
   * @return string
   */
  public function getTitle() {
    return $this->get('name')->value;
  }

  /**
   * @param string $title
   *
   * @return $this
   */
  public function setTitle($title) {
    return $this->set('name', $title);
  }

  /**
   * @return \Drupal\Core\Datetime\DrupalDateTime
   */
  public function getDate() {
    return $this->get('date')->date;
  }

  /**
   * @param \Drupal\Core\Datetime\DrupalDateTime $date
   *
   * @return $this
   */
  public function setDate(DrupalDateTime $date) {
    return $this->set('date', $date->format('Y-m-d\\TH:i:s'));
  }

  /**
   * @return \Drupal\filter\Render\FilteredMarkup
   */
  public function getDescription() {
    return $this->get('feedback')->processed;
  }

  /**
   * @param string $description
   * @param string $format
   *
   * @return $this
   */
  public function setDescription($description, $format) {
    return $this->set('feedback', [
      'value' => $description,
      'format' => $format,
    ]);
  }

}
