<?php declare(strict_types=1);


namespace Core\Entity;


use Adminaut\Entity\AdminautEntityInterface;
use Adminaut\Entity\AdminautEntityTrait;
use Adminaut\Entity\File;
use Core\Enum\OrganizationOperatingArea;
use Core\Enum\OrganizationStatus;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 * Class OrganizationFactory
 * @ORM\Entity(repositoryClass="Core\Repository\OrganizationRepository")
 * @ORM\Table(name="organizations")
 * @ORM\HasLifecycleCallbacks
 */
class Organization implements AdminautEntityInterface
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
     *     "value_options" : \Core\Enum\OrganizationStatus::__VALUE_OPTIONS
     * });
     * @Annotation\Required(true);
     * @Annotation\Type("Adminaut\Datatype\Select");
     * @var string
     */
    private $status = OrganizationStatus::__DEFAULT;

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
     *     "required"   : false,
     *     "filterable" : false,
     *     "searchable" : false
     * });
     * @Annotation\Attributes({
     *     "autocomplete" : "off"
     * })
     * @Annotation\Required(false);
     * @Annotation\Type("Adminaut\Datatype\Date");
     * @var DateTime|null
     */
    private $datePublished;

    /**
     * @ORM\Column(
     *     name     = "show_from",
     *     type     = "datetime",
     *     unique   = false,
     *     nullable = true
     * );
     * @Annotation\Options({
     *     "label"      : "Zobrazit od",
     *     "primary"    : false,
     *     "listed"     : false,
     *     "required"   : false,
     *     "filterable" : false,
     *     "searchable" : false,
     *     "help-block"  : "První datum, kdy je organizace viditelná na webu. Pokud je pole prázdné, zobrazí se okamžitě."
     * });
     * @Annotation\Attributes({
     *     "autocomplete" : "off"
     * })
     * @Annotation\Required(false);
     * @Annotation\Type("Adminaut\Datatype\Datetime");
     * @var DateTime|null
     */
    private $showFrom;

    /**
     * @ORM\Column(
     *     name     = "show_to",
     *     type     = "datetime",
     *     unique   = false,
     *     nullable = true
     * );
     * @Annotation\Options({
     *     "label"      : "Zobrazit do",
     *     "primary"    : false,
     *     "listed"     : false,
     *     "required"   : false,
     *     "filterable" : false,
     *     "searchable" : false,
     *     "help-block"  : "Poslední datum (včetně), kdy je organizace viditelná na webu. Pokud je pole prázdné, zobrazení je neomezené."
     * });
     * @Annotation\Attributes({
     *     "autocomplete" : "off"
     * })
     * @Annotation\Required(false);
     * @Annotation\Type("Adminaut\Datatype\Datetime");
     * @var DateTime|null
     */
    private $showTo;

    /**
     * @ORM\Column(
     *     name     = "show_on_homepage",
     *     type     = "boolean",
     *     unique   = false,
     *     nullable = false
     * );
     * @Annotation\Options({
     *     "label"      : "Zobrazit na úvodní stránce",
     *     "primary"    : false,
     *     "listed"     : true,
     *     "required"   : false,
     *     "filterable" : true,
     *     "searchable" : false
     * });
     * @Annotation\Required(false);
     * @Annotation\Type("Adminaut\Datatype\Checkbox");
     * @var bool
     */
    private $showOnHomepage = false;

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
     * @ORM\ManyToOne(targetEntity="OrganizationCategory", fetch="EAGER")
     * @ORM\JoinColumn(name="category_id", nullable=false)
     * @Annotation\Options({
     *     "label"         : "Kategorie",
     *     "primary"       : false,
     *     "listed"        : true,
     *     "required"      : true,
     *     "filterable"    : true,
     *     "searchable"    : false,
     *     "target_class" : "Core\Entity\OrganizationCategory",
     *     "empty_option" : "Vyberte kategorii..."
     * });
     * @Annotation\Required(true);
     * @Annotation\Type("Adminaut\Datatype\Reference");
     * @var OrganizationCategory|null
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", fetch="EAGER", cascade={"persist"})
     * @ORM\JoinTable(name="organizations_tags",
     *     joinColumns={@ORM\JoinColumn(name="organization_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     * )
     * @Annotation\Options({
     *     "label"                     : "Tagy",
     *     "primary"                   : false,
     *     "listed"                    : false,
     *     "required"                  : false,
     *     "filterable"                : false,
     *     "searchable"                : false,
     *     "target_class"              : "Core\Entity\Tag",
     *     "visualization"             : "select",
     * });
     * @Annotation\Attributes({
     *     "data-placeholder" : "Vyberte tagy...",
     *     "data-tags"        : true,
     * });
     * @Annotation\Required(false);
     * @Annotation\Type("Adminaut\Datatype\MultiReference");
     * @var Collection<Tag>|Tag[]
     */
    private $tags;

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
     * @var File
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
     *     name     = "homepage",
     *     type     = "string",
     *     unique   = false,
     *     nullable = false
     * );
     * @Annotation\Options({
     *     "label"          : "Homepage",
     *     "primary"        : false,
     *     "listed"         : false,
     *     "required"       : false,
     *     "filterable"     : false,
     *     "searchable"     : false,
     *     "add-on-prepend" :"<i class='fa fa-fw fa-globe'></i>"
     * });
     * @Annotation\Required(false);
     * @Annotation\Type("Adminaut\Datatype\Url");
     * @var string
     */
    private $homepage = '';

    /**
     * @ORM\Column(
     *     name     = "facebook",
     *     type     = "string",
     *     unique   = false,
     *     nullable = false
     * );
     * @Annotation\Options({
     *     "label"      : "Facebook",
     *     "primary"    : false,
     *     "listed"     : false,
     *     "required"   : false,
     *     "filterable" : false,
     *     "searchable" : false,
     *     "add-on-prepend":"<i class='fa fa-fw fa-facebook'></i>"
     * });
     * @Annotation\Attributes({
     *     "placeholder":"https://www.facebook.com/"
     * });
     * @Annotation\Validator({
     *     "name": "Zend\Validator\Regex",
     *     "options": {
     *         "pattern": "/^https:\/\/(www\.)?facebook\.com\/(.+)$/",
     *         "messageTemplates": {
     *             "regexNotMatch": "Zadaná Facebook URL není ve správném formátu"
     *         }
     *     }
     * });
     * @Annotation\Required(false);
     * @Annotation\Type("Adminaut\Datatype\Url");
     * @var string
     */
    private $facebook = '';

    /**
     * @ORM\Column(
     *     name     = "instagram",
     *     type     = "string",
     *     unique   = false,
     *     nullable = false
     * );
     * @Annotation\Options({
     *     "label"      : "Instagram",
     *     "primary"    : false,
     *     "listed"     : false,
     *     "required"   : false,
     *     "filterable" : false,
     *     "searchable" : false,
     *     "add-on-prepend":"<i class='fa fa-fw fa-instagram'></i>"
     * });
     * @Annotation\Attributes({
     *     "placeholder":"https://www.instagram.com/"
     * });
     * @Annotation\Validator({
     *     "name": "Zend\Validator\Regex",
     *     "options": {
     *         "pattern": "/^https:\/\/(www\.)?instagram\.com\/(.+)$/",
     *         "messageTemplates": {
     *             "regexNotMatch": "Zadaná Instagram URL není ve správném formátu"
     *         }
     *     }
     * });
     * @Annotation\Required(false);
     * @Annotation\Type("Adminaut\Datatype\Url");
     * @var string
     */
    private $instagram = '';

    /**
     * @ORM\Column(
     *     name     = "twitter",
     *     type     = "string",
     *     unique   = false,
     *     nullable = false
     * );
     * @Annotation\Options({
     *     "label"      : "Twitter",
     *     "primary"    : false,
     *     "listed"     : false,
     *     "required"   : false,
     *     "filterable" : false,
     *     "searchable" : false,
     *     "add-on-prepend":"<i class='fa fa-fw fa-twitter'></i>"
     * });
     * @Annotation\Attributes({
     *     "placeholder":"https://www.twitter.com/"
     * });
     * @Annotation\Validator({
     *     "name": "Zend\Validator\Regex",
     *     "options": {
     *         "pattern": "/^https:\/\/(www\.)?twitter\.com\/(.+)$/",
     *         "messageTemplates": {
     *             "regexNotMatch": "Zadaná Twitter URL není ve správném formátu"
     *         }
     *     }
     * });
     * @Annotation\Required(false);
     * @Annotation\Type("Adminaut\Datatype\Url");
     * @var string
     */
    private $twitter = '';

    /**
     * @ORM\Column(
     *     name     = "linkedin",
     *     type     = "string",
     *     unique   = false,
     *     nullable = false
     * );
     * @Annotation\Options({
     *     "label"      : "LinkedIn",
     *     "primary"    : false,
     *     "listed"     : false,
     *     "required"   : false,
     *     "filterable" : false,
     *     "searchable" : false,
     *     "add-on-prepend":"<i class='fa fa-fw fa-linkedin'></i>"
     * });
     * @Annotation\Attributes({
     *     "placeholder":"https://www.linkedin.com/"
     * });
     * @Annotation\Validator({
     *     "name": "Zend\Validator\Regex",
     *     "options": {
     *         "pattern": "/^https:\/\/(?:www|.*\.)?linkedin\.com\/(.+)$/",
     *         "messageTemplates": {
     *             "regexNotMatch": "Zadaná LinkedIn URL není ve správném formátu"
     *         }
     *     }
     * });
     * @Annotation\Required(false);
     * @Annotation\Type("Adminaut\Datatype\Url");
     * @var string
     */
    private $linkedin = '';

    /**
     * @ORM\Column(
     *     name     = "youtube",
     *     type     = "string",
     *     unique   = false,
     *     nullable = false
     * );
     * @Annotation\Options({
     *     "label"      : "YouTube",
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
     * @Annotation\Validator({
     *     "name": "Zend\Validator\Regex",
     *     "options": {
     *         "pattern": "/^https:\/\/(www\.)?youtube\.com\/(.+)$/",
     *         "messageTemplates": {
     *             "regexNotMatch": "Zadaná YouTube URL není ve správném formátu"
     *         }
     *     }
     * });
     * @Annotation\Required(false);
     * @Annotation\Type("Adminaut\Datatype\Url");
     * @var string
     */
    private $youtube = '';

    /**
     * @ORM\Column(
     *     name     = "phone",
     *     type     = "string",
     *     unique   = false,
     *     nullable = false
     * );
     * @Annotation\Options({
     *     "label"      : "Telefon",
     *     "primary"    : false,
     *     "listed"     : true,
     *     "required"   : false,
     *     "filterable" : false,
     *     "searchable" : true,
     *     "add-on-prepend":"<i class='fa fa-fw fa-phone'></i>"
     * });
     * @Annotation\Required(false);
     * @Annotation\Type("Adminaut\Datatype\Text");
     * @var string
     */
    private $phone = '';

    /**
     * @ORM\Column(
     *     name     = "email",
     *     type     = "string",
     *     unique   = false,
     *     nullable = false
     * );
     * @Annotation\Options({
     *     "label"      : "E-mail",
     *     "primary"    : false,
     *     "listed"     : true,
     *     "required"   : true,
     *     "filterable" : false,
     *     "searchable" : true,
     *     "add-on-prepend":"<i class='fa fa-fw fa-envelope'></i>"
     * });
     * @Annotation\Required(true);
     * @Annotation\Type("Adminaut\Datatype\Email");
     * @var string
     */
    private $email = '';

    /**
     * @ORM\Column(
     *     name     = "location_lat",
     *     type     = "float",
     *     unique   = false,
     *     nullable = false
     * );
     * @Annotation\Options({
     *     "label"                    :"Lokace",
     *     "primary"                  : false,
     *     "listed"                   : false,
     *     "required"                 : true,
     *     "filterable"               : false,
     *     "searchable"               : false,
     *     "longitude_property"       : "locationLng",
     *     "save_as"                  : "elements",
     *     "default_center"           : { "lat" : 49.8174919, "lng" : 15.472962 },
     *     "default_zoom"             : 6,
     *     "enable_download_data"     : true,
     *     "download_data_from"       : {"officialName","street","city"}
     * });
     * @Annotation\Attributes({"placeholder":"Zeměpisná šířka", "required":"required"});
     * @Annotation\Required(true);
     * @Annotation\Type("Adminaut\Datatype\Location");
     * @var float
     */
    protected $locationLat;

    /**
     * @ORM\Column(
     *     name     = "location_lng",
     *     type     = "float",
     *     unique   = false,
     *     nullable = false
     * );
     * @Annotation\Attributes({"placeholder":"Zeměpisná délka", "required":"required"});
     * @Annotation\Required(true);
     * @var float
     */
    protected $locationLng;

    /**
     * @ORM\Column(
     *     name     = "official_name",
     *     type     = "string",
     *     unique   = false,
     *     nullable = false
     * );
     * @Annotation\Options({
     *     "label"      : "Oficiální název",
     *     "primary"    : false,
     *     "listed"     : true,
     *     "required"   : true,
     *     "filterable" : false,
     *     "searchable" : true
     * });
     * @Annotation\Required(true);
     * @Annotation\Type("Adminaut\Datatype\Text");
     * @var string
     */
    private $officialName = '';

    /**
     * @ORM\Column(
     *     name     = "company_id",
     *     type     = "string",
     *     unique   = false,
     *     nullable = false
     * );
     * @Annotation\Options({
     *     "label"      : "IČO",
     *     "primary"    : false,
     *     "listed"     : true,
     *     "required"   : true,
     *     "filterable" : false,
     *     "searchable" : true
     * });
     * @Annotation\Required(true);
     * @Annotation\Type("Adminaut\Datatype\Text");
     * @var string
     */
    private $companyId = '';

    /**
     * @ORM\Column(
     *     name     = "street",
     *     type     = "string",
     *     unique   = false,
     *     nullable = false
     * );
     * @Annotation\Options({
     *     "label"      : "Ulice",
     *     "primary"    : false,
     *     "listed"     : true,
     *     "required"   : true,
     *     "filterable" : false,
     *     "searchable" : true
     * });
     * @Annotation\Required(true);
     * @Annotation\Type("Adminaut\Datatype\Text");
     * @var string
     */
    private $street = '';

    /**
     * @ORM\Column(
     *     name     = "city",
     *     type     = "string",
     *     unique   = false,
     *     nullable = false
     * );
     * @Annotation\Options({
     *     "label"      : "Obec",
     *     "primary"    : false,
     *     "listed"     : true,
     *     "required"   : true,
     *     "filterable" : false,
     *     "searchable" : true
     * });
     * @Annotation\Required(true);
     * @Annotation\Type("Adminaut\Datatype\Text");
     * @var string
     */
    private $city = '';

    /**
     * @ORM\Column(
     *     name     = "zip",
     *     type     = "string",
     *     unique   = false,
     *     nullable = false
     * );
     * @Annotation\Options({
     *     "label"      : "PSČ",
     *     "primary"    : false,
     *     "listed"     : true,
     *     "required"   : true,
     *     "filterable" : false,
     *     "searchable" : true
     * });
     * @Annotation\Required(true);
     * @Annotation\Type("Adminaut\Datatype\Text");
     * @var string
     */
    private $zip = '';

    /**
     * @ORM\Column(
     *     name     = "operating_area",
     *     type     = "string",
     *     unique   = false,
     *     nullable = false
     * );
     * @Annotation\Options({
     *     "label"      : "Působení projektu",
     *     "primary"    : false,
     *     "listed"     : true,
     *     "required"   : true,
     *     "filterable" : true,
     *     "searchable" : false,
     *     "value_options" : \Core\Enum\OrganizationOperatingArea::__VALUE_OPTIONS
     * });
     * @Annotation\Required(true);
     * @Annotation\Type("Adminaut\Datatype\Select");
     * @var string
     */
    private $operatingArea = null;

    /**
     * @ORM\Column(
     *     name     = "more_venues",
     *     type     = "boolean",
     *     unique   = false,
     *     nullable = false
     * );
     * @Annotation\Options({
     *     "label"      : "Více poboček",
     *     "primary"    : false,
     *     "listed"     : true,
     *     "required"   : false,
     *     "filterable" : true,
     *     "searchable" : false
     * });
     * @Annotation\Required(false);
     * @Annotation\Type("Adminaut\Datatype\Checkbox");
     * @var bool
     */
    private $moreVenues = false;

    /**
     * @ORM\Column(
     *     name     = "notes",
     *     type     = "text",
     *     unique   = false,
     *     nullable = false
     * );
     * @Annotation\Options({
     *     "label"      : "Poznámky",
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
    private $notes = '';

    /**
     * @ORM\Column(
     *     name     = "validation_status",
     *     type     = "text",
     *     unique   = false,
     *     nullable = false
     * );
     * @Annotation\Options({
     *     "label"      : "Ověřovací status",
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
    private $validationStatus = '';

    private $currentlyApproved = false;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
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
        if ($this->status === OrganizationStatus::NEW && $status === OrganizationStatus::ACTIVE) {
            $this->currentlyApproved = true;
        }

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

    public function getShowFrom(): ?DateTime
    {
        return $this->showFrom;
    }

    public function setShowFrom(?DateTime $showFrom): void
    {
        $this->showFrom = $showFrom;
    }

    public function getShowTo(): ?DateTime
    {
        return $this->showTo;
    }

    public function setShowTo(?DateTime $showTo): void
    {
        $this->showTo = $showTo;
    }

    public function isShowOnHomepage(): bool
    {
        return $this->showOnHomepage;
    }

    public function setShowOnHomepage(bool $showOnHomepage): void
    {
        $this->showOnHomepage = $showOnHomepage;
    }

    public function getPerex(): string
    {
        return $this->perex;
    }

    public function setPerex(string $perex): void
    {
        $this->perex = $perex;
    }

    public function getCategory(): ?OrganizationCategory
    {
        return $this->category;
    }

    public function setCategory(?OrganizationCategory $category): void
    {
        $this->category = $category;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setTags($tags): void
    {
        $this->tags = $tags;
    }

    public function addTags($tags): void
    {
        foreach ($tags as $tag) {
            if ($this->tags->contains($tag)) {
                continue;
            }

            $this->tags->add($tag);
        }
    }

    public function removeTags($tags): void
    {
        foreach ($tags as $tag) {
            if (!$this->tags->contains($tag)) {
                continue;
            }

            $this->tags->removeElement($tag);
        }
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

    public function getHomepage(): string
    {
        return $this->homepage;
    }

    public function setHomepage(string $homepage): void
    {
        $this->homepage = $homepage;
    }

    public function getFacebook(): string
    {
        return $this->facebook;
    }

    public function setFacebook(string $facebook): void
    {
        $this->facebook = $facebook;
    }

    public function getInstagram(): string
    {
        return $this->instagram;
    }

    public function setInstagram(string $instagram): void
    {
        $this->instagram = $instagram;
    }

    public function getTwitter(): string
    {
        return $this->twitter;
    }

    public function setTwitter(string $twitter): void
    {
        $this->twitter = $twitter;
    }

    public function getLinkedin(): string
    {
        return $this->linkedin;
    }

    public function setLinkedin(string $linkedin): void
    {
        $this->linkedin = $linkedin;
    }

    public function getYoutube(): string
    {
        return $this->youtube;
    }

    public function setYoutube(string $youtube): void
    {
        $this->youtube = $youtube;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getLocationLat(): ?float
    {
        return $this->locationLat;
    }

    public function setLocationLat(float $locationLat): void
    {
        $this->locationLat = $locationLat;
    }

    public function getLocationLng(): ?float
    {
        return $this->locationLng;
    }

    public function setLocationLng(float $locationLng): void
    {
        $this->locationLng = $locationLng;
    }

    public function getOfficialName(): string
    {
        return $this->officialName;
    }

    public function setOfficialName(string $officialName): void
    {
        $this->officialName = $officialName;
    }

    public function getCompanyId(): string
    {
        return $this->companyId;
    }

    public function setCompanyId(string $companyId): void
    {
        $this->companyId = $companyId;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getZip(): string
    {
        return $this->zip;
    }

    public function setZip(string $zip): void
    {
        $this->zip = $zip;
    }

    public function getOperatingArea(): ?string
    {
        return $this->operatingArea;
    }

    public function setOperatingArea(?string $operatingArea): void
    {
        $this->operatingArea = $operatingArea;
    }

    public function isMoreVenues(): bool
    {
        return $this->moreVenues;
    }

    public function setMoreVenues(bool $moreVenues): void
    {
        $this->moreVenues = $moreVenues;
    }

    public function getNotes(): string
    {
        return $this->notes;
    }

    public function setNotes(string $notes): void
    {
        $this->notes = $notes;
    }

    public function getValidationStatus(): string
    {
        return $this->validationStatus;
    }

    public function setValidationStatus(string $validationStatus): void
    {
        $this->validationStatus = $validationStatus;
    }

    public function isCurrentlyApproved(): bool
    {
        return $this->currentlyApproved;
    }
}