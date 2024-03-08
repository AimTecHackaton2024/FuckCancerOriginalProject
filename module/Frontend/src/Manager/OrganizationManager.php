<?php declare(strict_types=1);


namespace Frontend\Manager;

use Adminaut\Manager\FileManager;
use Adminaut\Manager\ModuleManager;
use Core\Entity\Organization;
use Core\Entity\Tag;
use Doctrine\ORM\EntityManager;
use Frontend\Form\OrganizationAddForm;

class OrganizationManager extends AbstractManager
{

    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(FileManager $fileManager, EntityManager $entityManager)
    {
        $this->fileManager = $fileManager;
        $this->entityManager = $entityManager;
    }

    public function create(OrganizationAddForm $form)
    {
        $data = $form->getData();

        foreach (['photoMain', 'photo1', 'photo2', 'photo3', 'photo4'] as $fileElement) {
            $file = $data[$fileElement];

            if ($file['error'] !== 0) {
                continue;
            }

            $this->fileManager->upload($form->getElements()[$fileElement]);
        }

        $organization = new Organization();
        $this->bind($organization, $form);

        if (!empty($data['tags'])) {
            foreach ($data['tags'] as $tag) {
                if (is_numeric($tag)) {
                    continue;
                }



                $organization->addTags([ new Tag($tag) ]);
            }
        }


       // $this->createOrganisationUser($organization);

        $this->entityManager->persist($organization);
        $this->entityManager->flush();

        return $organization;
    }

    //fuj, ale celé je to fuj, takže se to v tom fuj ztratí
    public function createOrganisationUser(Organization $organization)
    {
        try {
            $datetime = new \DateTime();
            $sql = "INSERT INTO user (email, password, name, role, active, inserted, inserted_by, updated, updated_by, deleted, deleted_by, dtype) 
                VALUES (:email, :password, :name, 'organization', 1, :inserted, 0, :updated, 0, 0, 0, 0)";
            $stmt = $this->entityManager->getConnection()->prepare($sql);
            $stmt->bindValue('email', $organization->getEmail());
            $stmt->bindValue('password', ''); // Assuming password is empty for now
            $stmt->bindValue('name', $organization->getTitle());
            $stmt->bindValue('inserted', $datetime->format('Y-m-d H:i:s')); // Formatting datetime
            $stmt->bindValue('updated', $datetime->format('Y-m-d H:i:s')); // Formatting datetime
            $stmt->execute();
        } catch (\Exception $e) {
            // Handle exceptions here
        }
    }


}