<?php

namespace Drupal\swat\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class SwatTelephoneConstraintValidator extends ConstraintValidator {

  public function validate($value, Constraint $constraint) {
    /* @var \Drupal\Core\Field\FieldItemListInterface $value */
    /* @var \Drupal\swat\Plugin\Validation\Constraint\SwatTelephoneConstraint $constraint */
    $tel = $_POST['tel']['0']['value'];
    if (!preg_match('/[0-9]{12}/', $tel)){
      $this->context->buildViolation($constraint->message)
        ->addViolation();
    }
  }

}
