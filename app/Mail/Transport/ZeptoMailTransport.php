<?php

namespace App\Mail\Transport;

use Illuminate\Support\Facades\Http;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

/**
 * Sends via ZeptoMail's HTTP API (https://api.zeptomail.com/v1.1/email)
 * rather than SMTP -- see config/mail.php for why. The API's "Send Mail
 * Token" is a distinct credential from the SMTP password.
 */
class ZeptoMailTransport extends AbstractTransport
{
    public function __construct(private readonly string $token)
    {
        parent::__construct();
    }

    protected function doSend(SentMessage $message): void
    {
        $email = $message->getOriginalMessage();

        if (! $email instanceof Email) {
            throw new TransportException('ZeptoMailTransport only supports Symfony Mime Email messages.');
        }

        $from = $email->getFrom()[0] ?? null;

        if (! $from) {
            throw new TransportException('ZeptoMail requires a From address.');
        }

        $payload = array_filter([
            'from' => $this->addressPayload($from),
            'to' => array_map(fn (Address $address) => ['email_address' => $this->addressPayload($address)], $email->getTo()),
            'reply_to' => array_map(fn (Address $address) => $this->addressPayload($address), $email->getReplyTo()) ?: null,
            'subject' => $email->getSubject(),
            'htmlbody' => $email->getHtmlBody(),
            'textbody' => $email->getTextBody(),
        ]);

        $response = Http::withHeaders([
            'Authorization' => $this->token,
            'Accept' => 'application/json',
        ])->post('https://api.zeptomail.com/v1.1/email', $payload);

        if ($response->failed()) {
            throw new TransportException(
                "ZeptoMail API error ({$response->status()}): {$response->body()}"
            );
        }
    }

    private function addressPayload(Address $address): array
    {
        return array_filter([
            'address' => $address->getAddress(),
            'name' => $address->getName() ?: null,
        ]);
    }

    public function __toString(): string
    {
        return 'zeptomail';
    }
}
