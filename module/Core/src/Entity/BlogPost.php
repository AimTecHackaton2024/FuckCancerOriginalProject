<?php declare(strict_types=1);


namespace Core\Entity;

use Adminaut\Entity\AdminautEntityInterface;
use Adminaut\Entity\AdminautEntityTrait;
use Adminaut\Entity\File;
use Core\Enum\BlogPostStatus;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 * Class BlogPost
 * @ORM\Entity(repositoryClass="Core\Repository\BlogPostRepository")
 * @ORM\Table(name="blog_posts")
 * @ORM\HasLifecycleCallbacks
 */
class BlogPost implements AdminautEntityInterface
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
     *     "label"      : "Titulek",
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
     *     name     = "status",
     *     type     = "string",
     *     unique   = false,
     *     nullable = false
     * );
     * @Annotation\Options({
     *     "label"      : "Status",
     *     "primary"    : false,
     *     "listed"     : true,
     *     "required"   : true,
     *     "filterable" : true,
     *     "searchable" : false,
     *     "value_options" : \Core\Enum\BlogPostStatus::__VALUE_OPTIONS
     * });
     * @Annotation\Required(true);
     * @Annotation\Type("Adminaut\Datatype\Select");
     * @var string
     */
    private $status = BlogPostStatus::__DEFAULT;

    /**
     * @ORM\Column(
     *     name     = "date_published",
     *     type     = "date",
     *     unique   = false,
     *     nullable = true
     * );
     * @Annotation\Options({
     *     "label"      : "Datum vydání",
     *     "primary"    : false,
     *     "listed"     : false,
     *     "required"   : true,
     *     "filterable" : false,
     *     "searchable" : false
     * });
     * @Annotation\Attributes({
     *     "autocomplete" : "off"
     * })
     * @Annotation\Required(true);
     * @Annotation\Type("Adminaut\Datatype\Date");
     * @var DateTime|null
     */
    private $datePublished;

    /**
     * @ORM\Column(
     *     name     = "perex",
     *     type     = "text",
     *     unique   = false,
     *     nullable = false
     * );
     * @Annotation\Options({
     *     "label"      : "Perex",
     *     "primary"    : false,
     *     "listed"     : false,
     *     "required"   : false,
     *     "filterable" : false,
     *     "searchable" : false
     * });
     * @Annotation\Required(false);
     * @Annotation\Type("Adminaut\Datatype\Textarea");
     * @var string
     */
    private $perex = '';

    /**
     * @ORM\Column(
     *     name     = "article",
     *     type     = "text",
     *     unique   = false,
     *     nullable = false
     * );
     * @Annotation\Options({
     *     "label"      : "Článek",
     *     "primary"    : false,
     *     "listed"     : false,
     *     "required"   : false,
     *     "filterable" : false,
     *     "searchable" : false,
     *     "editor"     : "ckeditor"
     * });
     * @Annotation\Required(false);
     * @Annotation\Type("Adminaut\Datatype\Textarea");
     * @var string
     */
    private $article = '';

    /**
     * @ORM\OneToOne(targetEntity="Adminaut\Entity\File")
     * @ORM\JoinColumn(name="photo_main", referencedColumnName="id", nullable=true)
     * @Annotation\Options({
     *     "label"      : "Hlavní foto",
     *     "primary"    : false,
     *     "listed"     : false,
     *     "required"   : true,
     *     "filterable" : false,
     *     "searchable" : false
     * });
     * @Annotation\Required(true);
     * @Annotation\Type("Adminaut\Datatype\FileImage");
     * @var File|null
     */
    protected $photoMain;

    /**
     * @ORM\OneToOne(targetEntity="Adminaut\Entity\File")
     * @ORM\JoinColumn(name="photo_1", referencedColumnName="id", nullable=true)
     * @Annotation\Options({
     *     "label"      : "Foto #1",
     *     "primary"    : false,
     *     "listed"     : false,
     *     "required"   : false,
     *     "filterable" : false,
     *     "searchable" : false
     * });
     * @Annotation\Required(false);
     * @Annotation\Type("Adminaut\Datatype\FileImage");
     * @var File|null
     */
    protected $photo1;

    /**
     * @ORM\OneToOne(targetEntity="Adminaut\Entity\File")
     * @ORM\JoinColumn(name="photo_2", referencedColumnName="id", nullable=true)
     * @Annotation\Options({
     *     "label"      : "Foto #2",
     *     "primary"    : false,
     *     "listed"     : false,
     *     "required"   : false,
     *     "filterable" : false,
     *     "searchable" : false
     * });
     * @Annotation\Required(false);
     * @Annotation\Type("Adminaut\Datatype\FileImage");
     * @var File|null
     */
    protected $photo2;

    /**
     * @ORM\OneToOne(targetEntity="Adminaut\Entity\File")
     * @ORM\JoinColumn(name="photo_3", referencedColumnName="id", nullable=true)
     * @Annotation\Options({
     *     "label"      : "Foto #3",
     *     "primary"    : false,
     *     "listed"     : false,
     *     "required"   : false,
     *     "filterable" : false,
     *     "searchable" : false
     * });
     * @Annotation\Required(false);
     * @Annotation\Type("Adminaut\Datatype\FileImage");
     * @var File|null
     */
    protected $photo3;

    /**
     * @ORM\OneToOne(targetEntity="Adminaut\Entity\File")
     * @ORM\JoinColumn(name="photo_4", referencedColumnName="id", nullable=true)
     * @Annotation\Options({
     *     "label"      : "Foto #4",
     *     "primary"    : false,
     *     "listed"     : false,
     *     "required"   : false,
     *     "filterable" : false,
     *     "searchable" : false
     * });
     * @Annotation\Required(false);
     * @Annotation\Type("Adminaut\Datatype\FileImage");
     * @var File|null
     */
    protected $photo4;

    /**
     * @ORM\Column(
     *     name     = "youtube_video",
     *     type     = "string",
     *     unique   = false,
     *     nullable = false
     * );
     * @Annotation\Options({
     *     "label"      : "Youtube video",
     *     "primary"    : false,
     *     "listed"     : false,
     *     "required"   : false,
     *     "filterable" : false,
     *     "searchable" : false,
     *     "add-on-prepend":"<i class='fa fa-fw fa-youtube'></i>"
     * });
     * @Annotation\Attributes({
     *     "placeholder":"https://www.youtube.com/"
     * });
     * @Annotation\Required(false);
     * @Annotation\Type("Adminaut\Datatype\Url");
     * @var string
     */
    private $youtubeVideo = '';

    public function __construct()
    {
        $this->datePublished = new DateTime();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getDatePublished(): ?DateTime
    {
        return $this->datePublished;
    }

    public function setDatePublished(?DateTime $datePublished): void
    {
        $this->datePublished = $datePublished;
    }

    public function getPerex(): string
    {
        return $this->perex;
    }

    public function setPerex(string $perex): void
    {
        $this->perex = $perex;
    }

    public function getArticle(): string
    {
        return $this->article;
    }

    public function setArticle(string $article): void
    {
        $this->article = $article;
    }

    public function getPhotoMain(): ?File
    {
        return $this->photoMain;
    }

    public function setPhotoMain(?File $photoMain): void
    {
        $this->photoMain = $photoMain;
    }

    public function getPhoto1(): ?File
    {
        return $this->photo1;
    }

    public function setPhoto1(?File $photo1): void
    {
        $this->photo1 = $photo1;
    }

    public function getPhoto2(): ?File
    {
        return $this->photo2;
    }

    public function setPhoto2(?File $photo2): void
    {
        $this->photo2 = $photo2;
    }

    public function getPhoto3(): ?File
    {
        return $this->photo3;
    }

    public function setPhoto3(?File $photo3): void
    {
        $this->photo3 = $photo3;
    }

    public function getPhoto4(): ?File
    {
        return $this->photo4;
    }

    public function setPhoto4(?File $photo4): void
    {
        $this->photo4 = $photo4;
    }

    public function getYoutubeVideo(): string
    {
        return $this->youtubeVideo;
    }

    public function setYoutubeVideo(string $youtubeVideo): void
    {
        $this->youtubeVideo = $youtubeVideo;
    }
}