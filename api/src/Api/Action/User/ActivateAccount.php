<?php

declare(strict_types=1);

namespace App\Api\Action\User;


use App\Entity\User;
use App\Service\Request\RequestService;
use App\Service\User\ActivateAccountService;
use Symfony\Component\HttpFoundation\Request;

class ActivateAccount
{
    /**
     * @var ActivateAccountService
     */
    private ActivateAccountService $activateAccountService;

    public function __construct(ActivateAccountService $activateAccountService)
    {

        $this->activateAccountService = $activateAccountService;
    }

    public function __invoke(Request $request, string $id): User
    {
        return $this->activateAccountService->activate($id, RequestService::getField($request, 'token'));
    }
}