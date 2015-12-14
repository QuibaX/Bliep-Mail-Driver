<?php

namespace Bliep\Mail;

use GuzzleHttp\ClientInterface;
use Illuminate\Mail\Transport\Transport;
use Swift_Mime_Message;
use GuzzleHttp\Client as HttpClient;

class BliepTransport extends Transport {

    private $apiKey;
    private $url;

    public function __construct($apiKey, $url)
    {
        $this->apiKey = $apiKey;
        $this->url = $url;
    }

    /**
     * Send the given Message.
     *
     * Recipient/sender data will be retrieved from the Message API.
     * The return value is the number of recipients who were accepted for delivery.
     *
     * @param Swift_Mime_Message $message
     * @param string[] $failedRecipients An array of failures by-reference
     *
     * @return int
     */
    public function send(Swift_Mime_Message $message, &$failedRecipients = null)
    {
        $client = new HttpClient();

        $data = [
            'apiKey' => $this->apiKey,
            'to' => $message->getTo(),
            'subject' => $message->getSubject(),
            'from' => $message->getFrom(),
            'message' => $message->getBody()
        ];

        if (version_compare(ClientInterface::VERSION, '6') === 1) {
            $options = ['form_params' => $data];
        } else {
            $options = ['body' => $data];
        }

        return $client->post($this->url, $options);
    }
}