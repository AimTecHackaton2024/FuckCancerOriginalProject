<?php
namespace Frontend\Controller;

use Adminaut\Datatype\MultiReference;
use Core\Service\MailService;
use Doctrine\Common\Persistence\ObjectManager;
use Frontend\Form\OrganizationAddForm;
use Frontend\Manager\OrganizationManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PagesController extends AbstractActionController
{
    /**
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * @var OrganizationManager
     */
    private $organizationManager;

    /**
     * @var MailService
     */
    private $mailer;

    public function __construct(ObjectManager $entityManager, OrganizationManager $organizationManager, MailService $mailer)
    {
        $this->entityManager = $entityManager;
        $this->organizationManager = $organizationManager;
        $this->mailer = $mailer;
    }

    public function aboutAction()
    {
        return new ViewModel();
    }

    public function addOrganizationAction()
    {
        $form = new OrganizationAddForm([
            'object_manager' => $this->entityManager,
        ]);
        $form->get('tags')->getValueOptions();

        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost()->toArray();
            $files = $this->getRequest()->getFiles()->toArray();
            $form->setData(array_merge_recursive($post, $files));

            if ($form->isValid()) {
                $organization = $this->organizationManager->create($form);

                $this->flashMessenger()->addSuccessMessage(sprintf('Vaše organizace "%s" byla přidána a čeká na naše schálení, budeme vás kontaktovat.', $organization->getTitle()));
                $this->mailer->sendXkoRegistration($organization);
                $this->mailer->sendXkoNewRegistrationNotification($organization);
                return $this->redirect()->refresh();
            } else {
                /** @var MultiReference $tagsElement */
                $tagsElement = $form->get('tags');
                $tagsElement->setValueOptions(array_merge($tagsElement->getValueOptions(), array_map(function($value) {
                    if (is_numeric($value)) {
                        return null;
                    }

                    return [
                        'label' => $value,
                        'value' => $value,
                    ];
                }, $post['tags'] ?: [])));
            }
        }

        return new ViewModel([
            'form' => $form->prepare(),
        ]);
    }
}
