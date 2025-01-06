<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\EmployeeOutput;
use App\Entity\Employee;
use App\Entity\Enum\LeaveRequestStatusEnum;
use App\Repository\LeaveRequestRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

readonly class EmployeeOutputProvider implements ProviderInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.item_provider')]
        private ProviderInterface $itemProvider,
        private LeaveRequestRepository $leaveRequestRepository
    ) {
    }
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        /** @var Employee $employee */
        $employee = $this->itemProvider->provide($operation, $uriVariables, $context);
        $totalDays = $this->leaveRequestRepository->getTotalDaysRequested(
            $employee,
            new \DateTime('1st January This Year'),
            new \DateTime('31st December This Year'),
            [LeaveRequestStatusEnum::APPROVED->value]
        );

        $outputClass = $context['output']['class'] ?? null;
        if ($outputClass === EmployeeOutput::class) {
            return new EmployeeOutput(
                $employee->getId(),
                $employee->getName(),
                $employee->getEmail(),
                $employee->getSalary(),
                $totalDays
            );
        }
        return $employee;
    }
}