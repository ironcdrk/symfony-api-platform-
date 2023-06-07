<?php

declare(strict_types=1);

namespace Mailer\Messenger\Handler;


use Mailer\Messenger\Message\RequestResetPasswordMessage;
use Mailer\Service\Mailer\ClientRoute;
use Mailer\Service\Mailer\MailerService;
use Mailer\Templating\TwigTemplate;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class RequestResetPasswordMessageHandler implements MessageHandlerInterface
{
    private MailerService $mailerService;
    private string $host;

    public function __construct(MailerService $mailerService, string $host)
    {
        $this->mailerService = $mailerService;
        $this->host = $host;
    }

    /**
     * @param RequestResetPasswordMessage $message
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke(RequestResetPasswordMessage $message): void
    {
        $payload = [
            'url' => \sprintf(
                '%s%s?uid=%s&rpt=%s',
                $this->host,
                ClientRoute::RESET_PASSWORD,
                $message->getId(),
                $message->getResetPasswordToken()
            ),
        ];

        $this->mailerService->send($message->getEmail(), TwigTemplate::REQUEST_RESET_PASSWORD, $payload);
    }

}