<?php

namespace Drupal\swat\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Constraint(
 *   id = "CountName",
 *   label = @Translation("Validate Name"),
 * )
 */
class CountNameConstraint extends Constraint {

  /**
   * @var string
   */
  public $message = 'Your name must be longer than one symbol and/or use only Latin letters.';

}
