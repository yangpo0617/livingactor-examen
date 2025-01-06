<?php

namespace App\Controller;

use App\Entity\Enum\LeaveRequestStatusEnum;
use App\Entity\LeaveRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class UpdateLeaveRequestStatus extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function __invoke(Request $request, LeaveRequest $leaveRequest): Response
    {
        $data = json_decode($request->getContent(), true);
        if (isset($data['status'])) {
            if (!in_array($data['status'], [LeaveRequestStatusEnum::APPROVED->value, LeaveRequestStatusEnum::REJECTED->value, LeaveRequestStatusEnum::PENDING->value])) {
                throw new \Exception('Status is not any of PENDING, APPROVED or REJECTED, please fill a valid status');
            }
        }
        switch ($data['status']) {
            case LeaveRequestStatusEnum::APPROVED->value:
                $leaveRequest->setStatus(LeaveRequestStatusEnum::APPROVED);
                break;
            case LeaveRequestStatusEnum::REJECTED->value:
                $leaveRequest->setStatus(LeaveRequestStatusEnum::REJECTED);
                break;
            case LeaveRequestStatusEnum::PENDING->value:
                $leaveRequest->setStatus(LeaveRequestStatusEnum::PENDING);
        }
        $this->entityManager->flush();

        return new Response('status changed', Response::HTTP_OK);
    }
}