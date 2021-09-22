<?php

declare(strict_types=1);

namespace Mailer\Serializer\Messenger;

use Mailer\Messenger\Message\UserRegisterMessage;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\Serializer;

class EventSerializer extends Serializer
{

    public function decode(array $encodedEnvelop): Envelope
    {
        $translatedType = $this->translateType($encodedEnvelop['headers']['type']);
        $encodedEnvelop['headers']['type'] = $translatedType;
        return parent::decode($encodedEnvelop);
    }

    private function translateType(string $type): string
    {
        $map = ['App\Messenger\Message\UserRegisterMessage' => UserRegisterMessage::class];

        if (\array_key_exists($type, $map)) {
            return $map[$type];
        }

        return $type;
    }
}