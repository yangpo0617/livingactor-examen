<?php

namespace App\Entity\Enum;

enum LeaveRequestStatusEnum: string
{
    case PENDING = 'PENDING';
    case APPROVED = 'APPROVED';
    case REJECTED = 'REJECTED';
}
