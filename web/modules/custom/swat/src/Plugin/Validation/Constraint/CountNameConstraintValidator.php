<?php

namespace Drupal\swat\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CountNameConstraintValidator extends ConstraintValidator {

  public function validate($value, Constraint $constraint) {
    /* @var \Drupal\Core\Field\FieldItemListInterface $value */
    /* @var \Drupal\swat\Plugin\Validation\Constraint\CountNameConstraint $constraint */
    /* @var \Drupal\Core\Entity\Plugin\DataType\EntityAdapter $adapter */
    /* @var \Drupal\swat\Entity\Swat $event */
    $name = $_POST['name']['0']['value'];
    if (strlen($name) < 2 || !preg_match('/^[A-Za-z]*$/', $name) ) {
      $this->context->buildViolation($constraint->message)
        ->addViolation();
    }
  }

}
