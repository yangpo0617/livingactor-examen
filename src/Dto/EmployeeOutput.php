<?php

namespace App\Dto;

class EmployeeOutput
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public float $salary,
        public int $leaveDays,
    ) {
    }
}