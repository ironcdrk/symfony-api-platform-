<?php

declare(strict_types=1);

namespace Mailer\Service\Mailer;

use Mailer\Templating\TwigTemplate;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Psr\Log\LoggerInterface;

use Symfony\Component\Mime\Email;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class MailerService
{
    private const TEMPLATE_SUBJECT_MAP = [
        TwigTemplate::USER_REGISTER => 'Bienvenid@!',
        TwigTemplate::REQUEST_RESET_PASSWORD => 'Restablecer contraseña',
        TwigTemplate::GROUP_REQUEST => 'Invitación a grupo',
    ];

    private MailerInterface $mailer;
    private Environment $engine;
    private LoggerInterface $logger;
    private string $mailerDefaultSender;

    public function __construct(
        MailerInterface $mailer,
        Environment $engine,
        LoggerInterface $logger,
        string $mailerDefaultSender
    ) {
        $this->mailer = $mailer;
        $this->engine = $engine;
        $this->logger = $logger;
        $this->mailerDefaultSender = $mailerDefaultSender;
    }

    /**
     * @param string $receiver
     * @param string $template
     * @param array $payload
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function send(string $receiver, string $template, array $payload): void
    {
        $email = (new Email())
            ->from($this->mailerDefaultSender)
            ->to($receiver)
            ->subject(self::TEMPLATE_SUBJECT_MAP[$template])
            ->html($this->engine->render($template, $payload));

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->logger->error(\sprintf('Error sending email: %s', $e->getMessage()));
        }
    }
}