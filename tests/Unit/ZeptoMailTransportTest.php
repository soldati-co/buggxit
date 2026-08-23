<?php

namespace Tests\Unit;

use App\Mail\Transport\ZeptoMailTransport;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mime\Email;
use Tests\TestCase;

class ZeptoMailTransportTest extends TestCase
{
    public function test_it_sends_the_expected_payload_to_the_zeptomail_api(): void
    {
        Http::fake(['api.zeptomail.com/*' => Http::response(['message' => 'OK'], 200)]);

        $email = (new Email())
            ->from('noreply@buggxit.store')
            ->to('buyer@example.com')
            ->replyTo('info@buggxit.store')
            ->subject('Your order is confirmed')
            ->html('<p>Thanks!</p>');

        (new ZeptoMailTransport('Zoho-enczapikey test-token'))->send($email);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.zeptomail.com/v1.1/email'
                && $request->hasHeader('Authorization', 'Zoho-enczapikey test-token')
                && $request['from']['address'] === 'noreply@buggxit.store'
                && $request['to'][0]['email_address']['address'] === 'buyer@example.com'
                && $request['reply_to'][0]['address'] === 'info@buggxit.store'
                && $request['subject'] === 'Your order is confirmed'
                && $request['htmlbody'] === '<p>Thanks!</p>';
        });
    }

    public function test_it_sends_attachments_as_base64_with_mime_type_and_filename(): void
    {
        Http::fake(['api.zeptomail.com/*' => Http::response(['message' => 'OK'], 200)]);

        $email = (new Email())
            ->from('noreply@buggxit.store')
            ->to('buyer@example.com')
            ->subject('Your receipt')
            ->html('<p>Attached.</p>')
            ->attach('%PDF-1.4 fake pdf content', 'Receipt-ORD-1.pdf', 'application/pdf');

        (new ZeptoMailTransport('Zoho-enczapikey test-token'))->send($email);

        Http::assertSent(function ($request) {
            $attachment = $request['attachments'][0] ?? null;

            return $attachment
                && $attachment['name'] === 'Receipt-ORD-1.pdf'
                && $attachment['mime_type'] === 'application/pdf'
                && base64_decode($attachment['content']) === '%PDF-1.4 fake pdf content';
        });
    }

    public function test_it_throws_a_transport_exception_when_the_api_call_fails(): void
    {
        Http::fake(['api.zeptomail.com/*' => Http::response(['error' => ['message' => 'Invalid token']], 401)]);

        $email = (new Email())
            ->from('noreply@buggxit.store')
            ->to('buyer@example.com')
            ->subject('Test')
            ->html('<p>Test</p>');

        $this->expectException(TransportException::class);

        (new ZeptoMailTransport('Zoho-enczapikey bad-token'))->send($email);
    }
}
