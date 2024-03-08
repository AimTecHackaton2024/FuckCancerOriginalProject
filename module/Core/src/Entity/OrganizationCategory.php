<?php declare(strict_types=1);


namespace Core\Entity;


use Adminaut\Entity\AdminautEntityInterface;
use Adminaut\Entity\AdminautEntityTrait;
use Core\Enum\OrganizationStatus;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 * Class OrganizationCategory
 * @ORM\Entity(repositoryClass="Core\Repository\OrganizationCategoryRepository")
 * @ORM\Table(name="organization_categories")
 * @ORM\HasLifecycleCallbacks
 */
class OrganizationCategory implements AdminautEntityInterface
{
    use AdminautEntityTrait;

    /**
     * @ORM\Column(
     *     name     = "title",
     *     type     = "string",
     *     unique   = false,
     *     nullable = false
     * );
     * @Annotation\Options({
     *     "label"      : "Název",
     *     "primary"    : true,
     *     "listed"     : true,
     *     "required"   : true,
     *     "filterable" : false,
     *     "searchable" : true
     * });
     * @Annotation\Required(true);
     * @Annotation\Type("Adminaut\Datatype\Text");
     * @var string
     */
    private $title = '';

    /**
     * @ORM\Column(
     *     name     = "icon",
     *     type     = "string",
     *     unique   = false,
     *     nullable = false
     * );
     * @Annotation\Options({
     *     "label"          : "Ikona",
     *     "primary"        : false,
     *     "listed"         : true,
     *     "required"       : true,
     *     "filterable"     : false,
     *     "searchable"     : false,
     *     "add-on-prepend" : "fa-",
     *     "help-block"     : "Do tohoto pole vložte název ikony. Ikonu najdete <a href='https://fontawesome.com/search' target='_blank'>zde</a>, po rozkliknutí detailu vybrané ikony zkopírujte text kliknutím na její název (v levém horním rohu)."
     * });
     * @Annotation\Required(true);
     * @Annotation\Type("Adminaut\Datatype\Text");
     * @var string
     */
    private $icon = '';

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): void
    {
        $this->icon = $icon;
    }
}