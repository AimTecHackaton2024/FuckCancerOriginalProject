<?php declare(strict_types=1);


namespace Core\Entity;


use Adminaut\Entity\AdminautEntityInterface;
use Adminaut\Entity\AdminautEntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 * Class Tag
 * @ORM\Entity
 * @ORM\Table(name="tags")
 * @ORM\HasLifecycleCallbacks
 */
class Tag implements AdminautEntityInterface
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

    public function __construct(?string $title = null)
    {
        if (null !== $title) {
            $this->setTitle($title);
        }
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
}