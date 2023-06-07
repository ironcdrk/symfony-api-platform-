<?php

declare(strict_types=1);

namespace Mailer\Messenger\Message;


class RequestResetPasswordMessage
{
    /**
     * @var String
     */
    private String $id;
    /**
     * @var string
     */
    private string $email;
    /**
     * @var string
     */
    private string $resetPasswordToken;

    public function __construct(String $id, string $email, string $resetPasswordToken)
    {
        $this->id = $id;
        $this->email = $email;
        $this->resetPasswordToken = $resetPasswordToken;
    }

    /**
     * @return String
     */
    public function getId(): String
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getResetPasswordToken(): string
    {
        return $this->resetPasswordToken;
    }



}