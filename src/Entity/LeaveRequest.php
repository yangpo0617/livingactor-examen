<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Controller\UpdateLeaveRequestStatus;
use App\Entity\Enum\LeaveRequestStatusEnum;
use App\Repository\LeaveRequestRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as CustomAssert;

/**
 * Représente une demande de congé.
 */
#[ORM\Entity(repositoryClass: LeaveRequestRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
        new Patch(
            uriTemplate: '/leave_requests/{id}/update_status',
            controller: UpdateLeaveRequestStatus::class,
            denormalizationContext: ['groups' => ['updateStatus']],
            name: 'update-status',
        )
    ],
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']])]
#[ApiFilter(SearchFilter::class, properties: ['status' => 'exact', 'employee' => 'exact'])]
#[CustomAssert\LeaveRequestTotalDays]
class LeaveRequest
{
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\GreaterThanOrEqual('today')]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => '2024-12-01'
        ]
    )]
    #[Groups(['read', 'write'])]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\GreaterThanOrEqual(propertyPath: 'startDate')]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => '2024-12-05'
        ]
    )]
    #[Groups(['read', 'write'])]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(type: Types::STRING, enumType: LeaveRequestStatusEnum::class)]
    #[Assert\NotBlank]
    #[Groups(['read', 'updateStatus'])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => 'APPROVED',
            'enum' => ['PENDING', 'APPROVED', 'REJECTED']
        ]
    )]
    private ?LeaveRequestStatusEnum $status = null;

    #[ORM\ManyToOne(targetEntity: Employee::class, inversedBy: 'leaveRequests')]
    #[ORM\JoinColumn('employee_id', nullable: false)]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => '/api/employees/1'
        ]
    )]
    #[Groups(['read', 'write'])]
    private Employee $employee;

    public function __construct()
    {
        $this->status = LeaveRequestStatusEnum::PENDING;
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

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;
        return $this;
    }

    public function calculateDays(): int
    {
        return $this->endDate->diff($this->startDate)->days;
    }

    public function getStatus(): ?LeaveRequestStatusEnum
    {
        return $this->status;
    }

    public function setStatus(?LeaveRequestStatusEnum $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getEmployee(): Employee
    {
        return $this->employee;
    }

    public function setEmployee(Employee $employee): static
    {
        $this->employee = $employee;
        return $this;
    }
}