<?php declare(strict_types=1);


namespace Core\Service;

use Core\Constants\EcomailTemplates;
use Core\Entity\Organization;
use Core\MailModule\Model\Message\EcomailMessage;
use MassimoFilippi\MailModule\Model\Recipient\Recipient;
use MassimoFilippi\MailModule\Model\Sender\Sender;
use MassimoFilippi\MailModule\Service\MailServiceInterface;

class MailService
{
    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @var Config
     */
    private $config;

    public function __construct(MailServiceInterface $mailer, Config $config)
    {
        $this->mailer = $mailer;
        $this->config = $config;
    }

    private function prepareSender(): Sender
    {
        return new Sender('xko@fuckcancer.cz', 'Xko - Fuck Cancer');
    }

    private function prepareRecipientFromOrganization(Organization $organization): Recipient
    {
        return new Recipient($organization->getEmail(), $organization->getTitle());
    }

    public function sendXkoRegistration(Organization $organization)
    {
        $message = new EcomailMessage($this->prepareSender(), $this->prepareRecipientFromOrganization($organization));
        $message->setSubject('Děkujeme Vám za zájem, stát se součástí platformy Xko');
        $message->setTemplateId(EcomailTemplates::XKO_REGISTRATION);

        $this->mailer->sendMail($message);
    }

    public function sendXkoConfirmation(Organization $organization)
    {
        $message = new EcomailMessage($this->prepareSender(), $this->prepareRecipientFromOrganization($organization));
        $message->setSubject('Vítejte v projektu Xko');
        $message->setTemplateId(EcomailTemplates::XKO_CONFIRMATION);

        $this->mailer->sendMail($message);
    }

    public function sendXkoNewRegistrationNotification(Organization $organization)
    {
        $recipients = $this->config->get('fuck_cancer/recipients/new_registration_notification');
        $message = new EcomailMessage($this->prepareSender(), array_shift($recipients));
        $message->setSubject('Nová registrace organizace do Xka');
        $message->setTemplateId(EcomailTemplates::XKO_NEW_REGISTRATION_NOTIFICATION);
        $message->setMergeTags([
            'NAZEV_ORGANIZACE' => $organization->getTitle(),
            'ID_ORGANIZACE' => $organization->getId(),
        ]);

        foreach ($recipients as $recipient) {
            $message->addRecipient($recipient);
        }

        $this->mailer->sendMail($message);
    }
}