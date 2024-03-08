<?php declare(strict_types=1);


namespace Admin\Controller;

use Adminaut\Controller\AdminautBaseController;
use Adminaut\Entity\AdminautEntityInterface;
use Adminaut\Manager\FileManager;
use Adminaut\Manager\ModuleManager;
use Adminaut\Options\ModuleOptions;
use Adminaut\Service\AccessControlService;
use Core\Entity\OrganizationCategory;
use Core\Entity\Tag;
use Exception;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;

/**
 * Class SettingsController
 * @method string translate($message, $textDomain = 'default', $locale = null)
 */
class SettingsController extends AdminautBaseController
{
    /**
     * @var PhpRenderer
     */
    private $renderer;

    /**
     * @var ModuleManager
     */
    private $moduleManager;

    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * SettingsController constructor.
     * @param PhpRenderer $renderer
     * @param ModuleManager $moduleManager
     * @param FileManager $fileManager
     */
    public function __construct(PhpRenderer $renderer, ModuleManager $moduleManager, FileManager $fileManager)
    {
        $this->renderer = $renderer;
        $this->moduleManager = $moduleManager;
        $this->fileManager = $fileManager;
    }



    public function indexAction()
    {
        if (!$this->isAllowed('settings', AccessControlService::READ)) {
            return $this->redirect()->toRoute('adminaut/dashboard');
        }

        return $this->__applyLayout(new ViewModel(), 'index', null);
    }

    /**
     * @return Response|ViewModel
     */
    public function listAction()
    {
        if (!$this->isAllowed('settings', AccessControlService::READ)) {
            return $this->redirect()->toRoute('adminaut/dashboard');
        }

        $moduleOptions = $this->__getModuleOptions();

        $form = $this->moduleManager->createForm($moduleOptions);

        $listedElements = [];

        /* @var $element \Zend\Form\Element */
        foreach ($form->getElements() as $key => $element) {
            if ($element->getOption('listed')
                || (method_exists($element, 'isPrimary')
                    && $element->isPrimary()
                    || $element->getOption('primary'))
            ) {
                $listedElements[$key] = $element;
            }
        }

        $list = $this->moduleManager->findAll($moduleOptions->getEntityClass());

        return $this->__applyLayout(new ViewModel([
            'list' => $list,
            'listedElements' => $listedElements,
            'hasPrimary' => ($form->getPrimaryField() !== 'id'),
            'moduleOption' => $moduleOptions,
        ]), '_list', (string) $moduleOptions->getModuleId());
    }

    /**
     * @return Response|ViewModel
     */
    public function addAction()
    {
        $moduleOptions = $this->__getModuleOptions();

        if (!$this->isAllowed('settings', AccessControlService::READ)) {
            return $this->redirect()->toRoute('adminaut/dashboard');
        }

        $entityClass = $moduleOptions->getEntityClass();
        $fm = $this->fileManager;
        $form = $this->moduleManager->createForm($moduleOptions);
        $form->bind(new $entityClass());

        /** @var Request $request */
        $request = $this->getRequest();

        if ($request->isPost()) {
            $postData = $request->getPost()->toArray();
            $files = $request->getFiles()->toArray();
            $post = array_merge_recursive($postData, $files);

            $form->setData($post);
            if ($form->isValid()) {
                try {
                    foreach ($files as $key => $file) {
                        if ($file['error'] != 0) {
                            continue;
                        }

                        $fm->upload($form->getElements()[$key], $this->authentication()->getIdentity());
                    }
                    $this->getEventManager()->trigger('settings-' . $moduleOptions->getModuleId() . '.beforeCreateRecord', $this, [
                        'form' => &$form
                    ]);
                    $entity = $this->moduleManager->create($moduleOptions->getEntityClass(), $form, null, $this->authentication()->getIdentity());
                    $this->getEventManager()->trigger('settings-'. $moduleOptions->getModuleId() . '.createRecord', $this, [
                        'entity' => $entity,
                    ]);
                    $primaryFieldValue = isset($form->getElements()[$form->getPrimaryField()]) ? (method_exists($form->getElements()[$form->getPrimaryField()], 'getListedValue') ? $form->getElements()[$form->getPrimaryField()]->getListedValue() : $form->getElements()[$form->getPrimaryField()]->getValue()) : $entity->getId();
                    $this->addSuccessMessage(sprintf($this->translate('Record "%s" has been successfully created.', 'adminaut'), $primaryFieldValue));

                    switch ($post['submit']) {
                        case 'create-and-continue' :
                            return $this->redirect()->toRoute('adminaut/settings/edit', ['module_id' => $moduleOptions->getModuleId(), 'entity_id' => $entity->getId()]);
                        case 'create-and-new' :
                            return $this->redirect()->toRoute('adminaut/settings/add', ['module_id' => $moduleOptions->getModuleId()]);
                        case 'create' :
                        default :
                            return $this->redirect()->toRoute('adminaut/settings/list', ['module_id' => $moduleOptions->getModuleId()]);
                    }
                } catch (Exception $e) {
                    $this->addErrorMessage(sprintf($this->translate('Error: %s', 'adminaut'), $e->getMessage()));
                }
            }
        }

        $viewModel = new ViewModel([
            'form' => $form,
            'moduleOption' => $moduleOptions,
        ]);
        $viewModel->setTemplate('admin/settings/_add');
        return $viewModel;
    }

    /**
     * @return Response|ViewModel
     */
    public function editAction()
    {
        $entityId = $this->params()->fromRoute('entity_id');

        $moduleOptions = $this->__getModuleOptions();

        if (!$this->isAllowed('settings', AccessControlService::READ)) {
            return $this->redirect()->toRoute('adminaut/dashboard');
        }

        if (null === $entityId) {
            return $this->redirect()->toRoute('adminaut/settings/list', ['module_id' => $moduleOptions->getModuleId()]);
        }

        /** @var AdminautEntityInterface $entity */
        $entity = $this->moduleManager->findOneById($moduleOptions->getEntityClass(), $entityId);

        if (!$entity) {
            $this->addErrorMessage($this->translate('Record was not found.', 'adminaut'));
            return $this->redirect()->toRoute('adminaut/settings/list', ['module_id' => $moduleOptions->getModuleId()]);
        }

        $fm = $this->fileManager;
        /* @var $form \Adminaut\Form\Form */
        $form = $this->moduleManager->createForm($moduleOptions);
        $form->bind($entity);

        /** @var Request $request */
        $request = $this->getRequest();

        if ($request->isPost()) {
            $postData = $request->getPost()->toArray();
            $files = $request->getFiles()->toArray();

            $post = array_merge_recursive($postData, $files);

            $form->setData($post);
            if ($form->isValid()) {
                try {
                    foreach ($files as $key => $file) {
                        if ($file['error'] != UPLOAD_ERR_OK) {
                            if ($file['error'] == UPLOAD_ERR_NO_FILE) {
                                $form->getElements()[$key]->setFileObject(null);
                            }
                            continue;
                        }

                        $fm->upload($form->getElements()[$key], $this->authentication()->getIdentity());
                    }

                    $this->getEventManager()->trigger($moduleOptions->getModuleId() . '.beforeUpdateRecord', $this, [
                        'entity' => &$entity,
                        'form' => &$form
                    ]);

                    $this->moduleManager->update($entity, $form, null, $this->authentication()->getIdentity());

                    $primaryFieldValue = isset($form->getElements()[$form->getPrimaryField()]) ? (method_exists($form->getElements()[$form->getPrimaryField()], 'getListedValue') ? $form->getElements()[$form->getPrimaryField()]->getListedValue() : $form->getElements()[$form->getPrimaryField()]->getValue()) : $entity->getId();
                    $this->addSuccessMessage(sprintf($this->translate('Record "%s" has been successfully updated.', 'adminaut'), $primaryFieldValue));
                    $this->getEventManager()->trigger($moduleOptions->getModuleId() . '.updateRecord', $this, [
                        'entity' => &$entity,
                    ]);

                    if ($post['submit'] == 'save-and-continue') {
                        return $this->redirect()->toRoute('adminaut/settings/edit', ['module_id' => $moduleOptions->getModuleId(), 'entity_id' => $entityId]);
                    } else {
                        return $this->redirect()->toRoute('adminaut/settings/list', ['module_id' => $moduleOptions->getModuleId()]);
                    }
                } catch (Exception $e) {
                    $this->addErrorMessage(sprintf($this->translate('Error: %s', 'adminaut'), $e->getMessage()));
                }
            }
        }

        $viewModel = new ViewModel([
            'form' => $form,
            'entity' => $entity,
            'primary' => $form->getPrimaryField(),
            'moduleOption' => $moduleOptions,
            'widgets' => $form->getWidgets(),
        ]);
        $viewModel->setTemplate('admin/settings/_edit');
        return $viewModel;
    }

    /**
     * @return Response
     */
    public function deleteAction()
    {
        $entityId = $this->params()->fromRoute('entity_id');
        $moduleOptions = $this->__getModuleOptions();

        if (null === $entityId) {
            return $this->redirect()->toRoute('adminaut/settings/list', ['module_id' => $moduleOptions->getModuleId()]);
        }

        $form = $this->moduleManager->createForm($moduleOptions);
        $primaryField = $form->getPrimaryField();

        /* @var $entity AdminautEntityInterface */
        $entity = $this->moduleManager->findOneById($moduleOptions->getEntityClass(), $entityId);
        if (!$entity) {
            $this->addErrorMessage($this->translate('Record was not found.', 'adminaut'));
            return $this->redirect()->toRoute('adminaut/settings/list', ['module_id' => $moduleOptions->getModuleId()]);
        }

        try {
            $this->moduleManager->delete($entity, $this->authentication()->getIdentity());
            $primaryFieldValue = $entity->{'get' . ucfirst($primaryField)}();
            $this->addSuccessMessage(sprintf($this->translate('Record "%s" has been deleted.', 'adminaut'), $primaryFieldValue));
            return $this->redirect()->toRoute('adminaut/settings/list', ['module_id' => $moduleOptions->getModuleId(), 'entity_id' => $entityId]);
        } catch (Exception $e) {
            $this->addErrorMessage(sprintf($this->translate('Error: %s', 'adminaut'), $e->getMessage()));
            return $this->redirect()->toRoute('adminaut/settings/list', ['module_id' => $moduleOptions->getModuleId(), 'entity_id' => $entityId]);
        }
    }

    /**
     * @param ViewModel $viewModel
     * @param string $template
     * @param string|null $page
     * @return ViewModel
     */
    private function __applyLayout(ViewModel $viewModel, string $template, ?string $page) {
        $viewModel->setTemplate('admin/settings/' . $template);

        $layoutView = new ViewModel();
        $layoutView->setTemplate('admin/settings/_layout.phtml');
        $layoutView->setVariables([
            'content' => $this->renderer->render($viewModel),
            'template' => $template,
            'page' => $page
        ]);

        return $layoutView;
    }

    private function __getModuleOptions()
    {
        switch ($this->params()->fromRoute('module_id'))
        {
            case 'organization-categories' :
                return new ModuleOptions([
                    'module_id' => 'organization-categories',
                    'module_name' => 'Kategorie organizací',
                    'entity_class' => OrganizationCategory::class,
                ]);
            case 'tags' :
                return new ModuleOptions([
                    'module_id' => 'tags',
                    'module_name' => 'Tagy',
                    'entity_class' => Tag::class,
                ]);
        }

        return null;
    }

}