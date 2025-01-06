<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class LeaveRequestTotalDays extends Constraint
{
    public string $message = 'Un employé ne peut pas dépasser 30 jours de congé cumulés par année civile.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}