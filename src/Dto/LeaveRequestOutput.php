<?php

namespace App\Dto;

use App\Entity\Enum\LeaveRequestStatusEnum;

class LeaveRequestOutput
{
    public function __construct(
        public int $id,
        public \DateTimeInterface $startDate,
        public \DateTimeInterface $endDate,
        public LeaveRequestStatusEnum $status,
    ) {
    }
}