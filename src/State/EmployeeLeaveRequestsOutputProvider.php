<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\LeaveRequestOutput;
use App\Entity\Employee;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

readonly class EmployeeLeaveRequestsOutputProvider implements ProviderInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.item_provider')]
        private ProviderInterface $itemProvider,
    ) {
    }
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $output = [];
        /** @var Employee $employee */
        $employee = $this->itemProvider->provide($operation, $uriVariables, $context);
        foreach ($employee->getLeaveRequests() as $leaveRequest) {
            $output[] = new LeaveRequestOutput(
                $leaveRequest->getId(),
                $leaveRequest->getStartDate(),
                $leaveRequest->getEndDate(),
                $leaveRequest->getStatus()
            );
        }
       return $output;
    }
}