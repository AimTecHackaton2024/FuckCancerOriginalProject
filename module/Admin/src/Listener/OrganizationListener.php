<?php declare(strict_types=1);


namespace Admin\Listener;

use Adminaut\Datatype\MultiReference;
use Adminaut\Form\Form;
use Core\Entity\Organization;
use Core\Entity\Tag;
use Core\Service\MailService;
use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\Event;
use Zend\EventManager\EventManagerInterface;
use Zend\Validator\Explode;
use Zend\Validator\InArray as InArrayValidator;
use Zend\Validator\ValidatorChain;

class OrganizationListener extends AbstractListenerAggregate
{
    private $entityManager;

    /**
     * @var EventManager
     */
    private $doctrineEventManager;

    /**
     * @var MailService
     */
    private $mailer;

    public function __construct(EntityManager $entityManager, MailService $mailer)
    {
        $this->entityManager = $entityManager;
        $this->doctrineEventManager = $entityManager->getEventManager();
        $this->mailer = $mailer;
    }


    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $events = $events->getSharedManager();

        $this->listeners[] = $events->attach('*', 'organizations.onCreateAddForm', [$this, 'onCreateForm']);
        $this->listeners[] = $events->attach('*', 'organizations.onCreateEditForm', [$this, 'onCreateForm']);
        $this->listeners[] = $events->attach('*', 'organizations.beforeCreateRecord', [$this, 'convertNewTagsToEntity']);
        $this->listeners[] = $events->attach('*', 'organizations.beforeUpdateRecord', [$this, 'convertNewTagsToEntity']);
//        $this->doctrineEventManager->addEventListener([Events::postUpdate], $this);
    }

    public function postUpdate(LifecycleEventArgs $eventArgs)
    {
        /** @var Organization $organization */
        if (!(($organization = $eventArgs->getEntity()) instanceof Organization)) {
            return;
        }

        if ($organization->isCurrentlyApproved()) {
            $this->mailer->sendXkoConfirmation($organization);
        }
    }

    public function onCreateForm(Event $event)
    {
        /** @var Form $form */
        $form = $event->getParam('form');

        $tagsElementInputFilter = $form->getInputFilter()->get('tags');
//        $tagsElementInputFilter->getFilterChain()->attach(new TagFilter($this->entityManager));

        $validatorChain = new ValidatorChain();
        foreach ($validatorChain->getValidators() as $validator) {
            if (!$validator['instance'] instanceof Explode) {
                $validatorChain->attach($validator);
            } elseif (!$validator['instance']->getValidator() instanceof InArrayValidator) {
                $validatorChain->attach($validator['instance']->getValidator());
            }
        }

        $tagsElementInputFilter->setValidatorChain($validatorChain);
    }

    public function convertNewTagsToEntity(Event $event)
    {
        /** @var Form $form */
        $form = $event->getParam('form');

        /** @var MultiReference $tagsElement */
        $tagsElement = $form->get('tags');

        $values = $tagsElement->getValue();
        foreach ($values ?? [] as $i => &$value) {
            if (is_numeric($value)) {
                continue;
            }

            if (is_string($value)) {
                /** @var Tag $fromDatabase */
                $fromDatabase = $this->entityManager->getRepository(Tag::class)->findOneBy([
                    'title' => $value,
                ]);

                if ($fromDatabase) {
                    $values[$i] = (string) $fromDatabase->getId();
                    continue;
                }

                $tag = new Tag();
                $tag->setTitle($value);

                $this->entityManager->persist($tag);
                $this->entityManager->flush($tag);

                $values[$i] = (string) $tag->getId();
            }
        }

        $tagsElement->setValue($values);
    }

}