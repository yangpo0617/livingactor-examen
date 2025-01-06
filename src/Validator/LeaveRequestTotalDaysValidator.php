<?php

namespace App\Validator;

use App\Entity\Enum\LeaveRequestStatusEnum;
use App\Entity\LeaveRequest;
use App\Repository\LeaveRequestRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class LeaveRequestTotalDaysValidator extends ConstraintValidator
{
    public function __construct(private LeaveRequestRepository $leaveRequestRepository)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof LeaveRequestTotalDays) {
            throw new UnexpectedTypeException($constraint, LeaveRequestTotalDays::class);
        }
        /** @var LeaveRequest $value */
        $totalDays = $this->leaveRequestRepository->getTotalDaysRequested(
            $value->getEmployee(),
            new \DateTime('1st January This Year'),
            new \DateTime('31st December This Year'),
            [LeaveRequestStatusEnum::PENDING->value, LeaveRequestStatusEnum::APPROVED->value]
        );

        if ($totalDays + $value->calculateDays() > 30) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}