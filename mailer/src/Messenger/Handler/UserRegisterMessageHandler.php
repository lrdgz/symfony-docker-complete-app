<?php

declare(strict_types=1);

namespace Mailer\Messenger\Handler;

use Mailer\Messenger\Message\UserRegisterMessage;
use Mailer\Service\Mailer\ClientRoute;
use Mailer\Service\Mailer\MailerService;
use Mailer\Templating\TwigTemplate;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UserRegisterMessageHandler implements MessageHandlerInterface
{
    private MailerService $mailerService;
    private string $host;

    public function __construct(MailerService $mailerService, string $host)
    {
        $this->mailerService = $mailerService;
        $this->host = $host;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function __invoke(UserRegisterMessage $message): void
    {
        $payload = [
            'name' => $message->getName(),
            'url' => \sprintf(
                '%s%s?token=%s&uid=%s',
                $this->host,
                ClientRoute::ACTIVATE_ACCOUNT,
                $message->getToken(),
                $message->getId(),
            )
        ];

        $this->mailerService->send($message->getEmail(), TwigTemplate::USER_REGISTER, $payload);
    }
}