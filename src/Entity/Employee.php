<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Dto\EmployeeOutput;
use App\State\EmployeeLeaveRequestsOutputProvider;
use App\State\EmployeeOutputProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Représente un employé.
 */
#[ORM\Entity]
#[UniqueEntity('email')]
#[ApiResource(
    operations: [
        new Get(
            output: EmployeeOutput::class,
            provider: EmployeeOutputProvider::class
        ),
        new Get(
            uriTemplate: '/employee/{id}/leave_requests',
            provider: EmployeeLeaveRequestsOutputProvider::class
        ),
    ]
)]
class Employee
{
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column(type: Types::FLOAT)]
    #[Assert\NotBlank]
    #[Assert\Positive]
    private ?float $salary = null;

    /** @var ArrayCollection|LeaveRequest[] */
    #[ORM\OneToMany(targetEntity: LeaveRequest::class, mappedBy: 'employee', cascade: ['persist', 'remove'])]
    #[CustomAssert\LeaveRequestTotalDays]
    public iterable $leaveRequests;

    public function __construct()
    {
        $this->leaveRequests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getSalary(): ?float
    {
        return $this->salary;
    }

    public function setSalary(?float $salary): static
    {
        $this->salary = $salary;
        return $this;
    }

    public function getLeaveRequests(): iterable
    {
        return $this->leaveRequests;
    }

    public function setLeaveRequests(iterable $leaveRequests): static
    {
        $this->leaveRequests = $leaveRequests;
        return $this;
    }
}