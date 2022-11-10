<?php


namespace App\Repository;


use App\Entity\User;
use App\Exceptions\User\UserNotFoundException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;

class UserRepository extends BaseRepository
{

    protected static function entityClass(): string
    {
        return User::class;
    }

    public function findOneByEmailOrFail(string $email): User
    {
        if(null === $user = $this->objectRepository->findOneBy(['email'=>$email])){
            throw UserNotFoundException::fromEmail($email);
        }
        return $user;
    }

    /**
     * @param User $user
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(User $user): void
    {
        $this->saveEntity($user);
    }

    /**
     * @param User $user
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(User $user): void
    {
        $this->removeEntity($user);
    }
}