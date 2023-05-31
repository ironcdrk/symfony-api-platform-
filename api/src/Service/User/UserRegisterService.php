<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Exception\User\UserAlreadyExistException;
//use App\Messenger\Message\UserRegisteredMessage;
use App\Messenger\RoutingKey;
use App\Messenger\Message\UserRegisteredMessage;
use App\Repository\UserRepository;
use App\Service\Password\EncoderService;
//use Doctrine\ORM\ORMException;  => deprecated
use App\Service\Request\RequestService;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\MessageBusInterface;

//use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;

class UserRegisterService
{
    private UserRepository $userRepository;
    private EncoderService $encoderService;
    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $messageBus;

    public function __construct(UserRepository $userRepository, EncoderService $encoderService, MessageBusInterface $messageBus) {
        $this->userRepository = $userRepository;
        $this->encoderService = $encoderService;
        $this->messageBus = $messageBus;
    }

    public function create(Request $request): User
    {
        $name = RequestService::getField($request, 'name');
        $email = RequestService::getField($request, 'email');
        $password = RequestService::getField($request, 'password');

        $user = new User($name, $email);
        $user->setPassword($this->encoderService->generateEncodedPassword($user, $password));

        try {
            $this->userRepository->save($user);
        } catch (\Exception $exception) {
            throw UserAlreadyExistException::fromEmail($email);
        }

        $this->messageBus->dispatch(
            new UserRegisteredMessage($user->getId(),$user->getName(),$user->getEmail(),$user->getToken()),
            [new AmqpStamp(RoutingKey::USER_QUEUE)]
        );
        return $user;
    }

//    public function create(string $name, string $email, string $password): User
//    {
//        $user = new User($name, $email);
//        $user->setPassword($this->encoderService->generateEncodedPassword($user, $password));
//
//        try {
//            $this->userRepository->save($user);
//        } catch (ORMException $e) {
//            throw UserAlreadyExistException::fromEmail($email);
//        }

//        $this->messageBus->dispatch(
//            new UserRegisteredMessage($user->getId(), $user->getName(), $user->getEmail(), $user->getToken()),
//            [new AmqpStamp(RoutingKey::USER_QUEUE)]
//        );

//        return $user;
//    }
}