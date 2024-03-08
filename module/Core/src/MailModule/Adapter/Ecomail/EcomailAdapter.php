<?php

namespace Core\MailModule\Adapter\Ecomail;

use Core\MailModule\Model\Message\EcomailMessage;
use Ecomail;
use MassimoFilippi\MailModule\Adapter\AdapterInterface;
use MassimoFilippi\MailModule\Exception\RuntimeException;
use MassimoFilippi\MailModule\Model\Message\MessageInterface;
use MassimoFilippi\MailModule\Model\ReplyTo\ReplyTo;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;

/**
 * Class EcomailAdapter
 */
class EcomailAdapter implements AdapterInterface
{
    /**
     * @var Ecomail
     */
    private $ecomail;

    //-------------------------------------------------------------------------

    /**
     * EcomailAdapter constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        if (!array_key_exists('api_key', $options)) {
            throw new ServiceNotCreatedException('Missing adapter parameter: "api_key".');
        }

        $this->ecomail = new Ecomail($options['api_key']);
    }

    //-------------------------------------------------------------------------

    private function preparePayload(MessageInterface $message): array
    {
        $payload = [];

        $payload['subject'] = $message->getSubject();
        $payload['from_name'] = $message->getSender()->getName();
        $payload['from_email'] = $message->getSender()->getEmail();

        $payload['to'] = [];
        foreach ($message->getRecipients() as $recipient) {
            $payload['to'][] = [
                'name' => $recipient->getName(),
                'email' => $recipient->getEmail(),
            ];
        }
        unset($recipient);

        if($message->hasReplyTo()) {
            /** @var ReplyTo $replyTo */
            $replyTo = current($message->getReplyTo());

            if(!empty($replyTo->getName())) {
                $payload['reply_to'] = $replyTo->getName() . ' <'. $replyTo->getEmail() .'>';
            } else {
                $payload['reply_to'] = $replyTo->getEmail();
            }
        }

        if ($message instanceof EcomailMessage) {
            if ($message->isTemplate()) {
                $payload['template_id'] = $message->getTemplateId();
            } else {
                $payload['html'] = $message->getHtml();
                $payload['text'] = $message->getText();
            }

            if ($message->hasMergeTags()) {
                $payload['global_merge_vars'] = [];
                foreach ($message->getMergeTags() as $mergeTagName => $mergeTagValue) {
                    $payload['global_merge_vars'][] = [
                        'name' => $mergeTagName,
                        'content' => $mergeTagValue,
                    ];
                }
            }
        } else {
            $payload['html'] = $message->getHtml();
            $payload['text'] = $message->getText();
        }

        return $payload;
    }

    /**
     * @param MessageInterface $message
     */
    public function sendMail(MessageInterface $message)
    {
        $payload = $this->preparePayload($message);

        try {
            if ($message instanceof EcomailMessage && $message->isTemplate()) {
                $response = $this->ecomail->sendTransactionalTemplate(['message' => $payload]);
            } else {
                $response = $this->ecomail->sendTransactionalEmail(['message' => $payload]);
            }

            if (array_key_exists('error', $response)) {
                $exceptionMessage = $response['message'];

                if (is_array($exceptionMessage)) {
                    $exceptionMessage = implode(' ', $exceptionMessage['errors']);
                }

                throw new RuntimeException($exceptionMessage);
            }
        } catch (\Exception $exception) {
            throw new RuntimeException('Exception raised during sending mail.', $exception->getCode(), $exception);
        }
    }
}
