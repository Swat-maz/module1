<?php

namespace Drupal\swat\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Constraint(
 *   id = "SwatTelephone",
 *   label = @Translation("Unique attendees"),
 * )
 */
class SwatTelephoneConstraint extends Constraint {

  /**
   * @var string
   */
  public $message = 'Enter valid telephone (fill in the entire field only number).';

}
