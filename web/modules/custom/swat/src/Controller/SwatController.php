<?php

namespace Drupal\swat\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\file\Entity\File;
use Symfony\Component\DependencyInjection\ContainerInterface;
/**
 * Defines ResponseController class.
 */
class SwatController extends ControllerBase {

  /**
   * Do some func.
   *
   * @var \Drupal\swat\Controller\SwatController
   */
  protected $formBuild;

  protected $entityBuild;

  /**
   * Creating a container for form rendering.
   */
  public static function create(ContainerInterface $container) {
    $instance              = parent::create($container);
    $instance->formBuild   = $container->get('entity.form_builder');
    $instance->entityBuild = $container->get('entity_type.manager');
    return $instance;
  }

  /**
   * Getting the Form and render it.
   */
  public function build() {
    $entity        = $this->entityBuild
      ->getStorage('swat')
      ->create([
        'entity_type' => 'node',
        'entity'      => 'swat',
      ]);
    $guestbookform = $this->formBuild->getForm($entity, 'add');
    return $guestbookform;
  }

  /**
   * Show information from database.
   */
  public function show(): array {
    $form = $this->build();
    $constn = Database::getConnection();
    $query = $constn->select('swat', 's');
    $query->fields('s', [
      'id',
      'name',
      'email',
      'date',
      'photo__target_id',
      'avatar__target_id',
      'tel',
      'feedback',
    ]);
    $query->orderBy('s.date', 'DESC');
    $data = $query->execute()->fetchAllAssoc('id');
    $result = [];
    $data = json_decode(json_encode($data), TRUE);
    foreach ($data as $value) {
      $full_name = $value['name'];
      $id = $value['id'];
      $email = $value['email'];
      $timestamp = $value['date'];
      $phone = $value['tel'];
      $time = date('F/d/Y G:i:s', $timestamp);
      $feedback = $value['feedback'];
      $avatarphoto = File::load($value['avatar__target_id']);
      $feedbackphoto = File::load($value['photo__target_id']);
      if ($avatarphoto !== NULL) {
        $ava = $avatarphoto->getFileUri();
      }
      else {
        $ava = 'public://default/default.jpeg';
      }
      if ($feedbackphoto !== NULL) {
        $feedfoto = $feedbackphoto->getFileUri();
      }
      else {
        $feedfoto = '';
      }
      $avatarka = [
        '#type' => 'image',
        '#theme' => 'image_style',
        '#style_name' => 'medium',
        '#uri' => $ava,
      ];
      $userphoto = [
        '#type' => 'image',
        '#theme' => 'image_style',
        '#style_name' => 'medium',
        '#uri' => $feedfoto,
      ];
      $result[] = [
        "id" => $id,
        "name" => $full_name,
        "email" => $email,
        "feedback" => $feedback,
        "photo" => $userphoto,
        "ava" => $avatarka,
        "time" => $time,
        "phone" => $phone,
        "uri" => file_url_transform_relative(file_create_url($feedfoto)),
      ];
    }
    return [
      '#form' => $form,
      '#theme' => 'swat',
      '#items' => $result,
    ];
  }

}
