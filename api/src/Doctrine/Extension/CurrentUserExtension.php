<?php

declare(strict_types=1);

namespace App\Doctrine\Extension;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Group;
use App\Entity\User;
use App\Exception\Group\GroupNotFoundException;
use App\Repository\GroupRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CurrentUserExtension implements QueryCollectionExtensionInterface
{
    private TokenStorageInterface $tokenStorage;
    private GroupRepository $usergroupRepository;

    public function __construct(TokenStorageInterface $tokenStorage, GroupRepository $usergroupRepository)
    {
        $this->tokenStorage = $tokenStorage;
        $this->groupRepository = $usergroupRepository;
    }


    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        string $operationName = null
    ) {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    private function addWhere(QueryBuilder $qb, string $resourceClass): void
    {
        /** @var User|null $user */
        $user = $this->tokenStorage->getToken()
            ? $this->tokenStorage->getToken()->getUser()
            : null;

        $rootAlias = $qb->getRootAliases()[0];

        if (Group::class === $resourceClass) {
            if ($qb->getParameters()->first()->getValue() !== $user->getId()) {
                throw new AccessDeniedHttpException('You can\'t retrieve another user groups');
            }
        }

        if (User::class === $resourceClass) {
            foreach ($user->getGroups() as $group) {
                if ($group->getId() === $qb->getParameters()->first()->getValue()) {
                    return;
                }
            }

            throw new AccessDeniedHttpException('You can\'t retrieve users of another group');
        }


    }

    private function isGroupAndUserIsMember(string $parameterId, User $user): bool
    {
        try {
            return $user->isMemberOfGroup($this->groupRepository->findOneByIdOrFail($parameterId));
        } catch (GroupNotFoundException $e) {
            return false;
        }
    }

}