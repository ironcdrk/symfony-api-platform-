<?php

declare(strict_types=1);

namespace App\Api\Action\User;


use App\Service\User\RequestResetPasswordService;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RequestResetPassword
{

    /**
     * @var RequestResetPasswordService
     */
    private RequestResetPasswordService $requestResetPasswordService;

    public function __construct(RequestResetPasswordService $requestResetPasswordService)
    {
        $this->requestResetPasswordService = $requestResetPasswordService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function __invoke(Request $request): JsonResponse
    {
        $this->requestResetPasswordService->send($request);
        return new JsonResponse(['message'=>'Request reset password email sent']);
    }
}