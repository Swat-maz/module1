<?php

namespace Drupal\swat\Controller;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

class SwatListBuilder extends EntityListBuilder {

  public function buildHeader() {
    $header = [];
    $header['name'] = $this->t('Name');
    $header['timestamp'] = $this->t('Added');
    $header['email'] = $this->t('Email');
    $header['tel'] = $this->t('Phone number');
    $header['avatar'] = $this->t('Avatar photo');
    $header['feedback'] = $this->t('Feedback');
    $header['photo'] = $this->t('Feedback photo');

//    $header['published'] = $this->t('Published');
    return $header + parent::buildHeader();
  }

  public function buildRow(EntityInterface $event) {
    /** @var \Drupal\swat\Entity\Swat $event */
    $row = [];
    $row['name'] = $event->toLink();
    $row['date'] = $event->getDate()->format('m/d/y h:i:s');
//    $row['published'] = $event->isPublished() ? $this->t('Yes') : $this->t('No');
    return $row + parent::buildRow($event);
  }

}
