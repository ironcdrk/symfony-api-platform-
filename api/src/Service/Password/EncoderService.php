<?php

declare(strict_types=1);

namespace App\Service\Password;

use App\Entity\User;
use App\Exception\Password\PasswordException;
//use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class EncoderService
{
    private const MINIMUM_LENGTH = 6;

//    private UserPasswordEncoderInterface $userPasswordEncoder;
    private UserPasswordHasherInterface $userPasswordEncoder;

//    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    public function __construct(UserPasswordHasherInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function generateEncodedPassword(UserInterface $user, string $password)
    {
        if (self::MINIMUM_LENGTH > \strlen($password)) {
            throw PasswordException::invalidLength();
        }

//        return $this->userPasswordEncoder->encodePassword($user, $password);
        return $this->userPasswordEncoder->hashPassword($user, $password);
    }

    public function isValidPassword(User $user, string $oldPassword): bool
    {
        return $this->userPasswordEncoder->isPasswordValid($user, $oldPassword);
    }
}