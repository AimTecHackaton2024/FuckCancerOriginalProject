<?php declare(strict_types=1);


namespace Frontend\Form;

use Adminaut\Datatype\Location;
use Adminaut\Datatype\MultiReference;
use Adminaut\Datatype\Reference;
use Adminaut\Datatype\Textarea;
use Core\Datatype\File;
use Core\Entity\OrganizationCategory;
use Core\Entity\Tag;
use Core\Enum\OrganizationOperatingArea;
use Doctrine\ORM\EntityManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Frontend\Constants\OrganizationFormConstants;
use Frontend\Form\InputFilter\OrganizationAddFormInputFilter;
use Frontend\View\Helper\FileSizeHelper;
use Zend\Form\Element\Checkbox;
use Zend\Form\Element\Email;
use Zend\Form\Element\Radio;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;
use Zend\Form\Element\Url;
use Zend\Form\Exception\InvalidArgumentException;
use Zend\Form\Form;

class OrganizationAddForm extends Form
{
    public function __construct($options = [])
    {
        parent::__construct(null, $options);

        if (!($options['object_manager'] ?? null) instanceof EntityManager) {
            throw new InvalidArgumentException(sprintf("Expected form option `object_manager` to be instance of %s, %s was provided.", EntityManager::class, get_class($options['object_manager'])));
        }

        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setHydrator(new DoctrineObject($options['object_manager']));
        $this->setInputFilter(new OrganizationAddFormInputFilter());

        $photoFormat = sprintf('(%s)', implode(', ', OrganizationFormConstants::PHOTO_FILE_FORMATS));

        $this
            ->add([
                'type' => Text::class,
                'name' => 'title',
                'options' => [
                    'label' => 'Název organizace',
                    'help-block' => 'Název organizace, který běžně používáte (zde není nutné psát celý oficiální název organizace).',
                    'error-messages' => [
                        'Zadejte název organizace'
                    ]
                ],
            ])
            ->add([
                'type' => Reference::class,
                'name' => 'category',
                'options' => [
                    'label' => 'Kategorie',
                    'target_class' => OrganizationCategory::class,
                    'object_manager' => $options['object_manager'],
                    'find_method'    => [
                        'name'   => 'findBy',
                        'params' => [
                            'criteria' => [],
                            'orderBy'  => ['title' => 'ASC'],
                        ],
                    ],
                    'empty_option' => 'Vyberte kategorii...',
                    'select2_options' => [
                        'theme' => 'bootstrap-5'
                    ],
                    'help-block' => 'Zvolte kategorii, která nejlépe odpovídá obsahu vaší činnosti',
                ]
            ])
            ->add([
                'type' => MultiReference::class,
                'name' => 'tags',
                'options' => [
                    'label' => 'Tagy',
                    'target_class' => Tag::class,
                    'object_manager' => $options['object_manager'],
                    'find_method'    => [
                        'name'   => 'findBy',
                        'params' => [
                            'criteria' => [],
                            'orderBy'  => ['title' => 'ASC'],
                        ],
                    ],
                    'visualization' => 'select',
                    'disable_inarray_validator' => true,
                    'select2_options' => [
                        'theme' => 'bootstrap-5'
                    ],
                    'help-block' => 'Zvolte tagy (klíčová slova), které souvisí s vaší činností a obsahem témat, které řešíte (např. finanční pomoc, pacientská organizace, rakovina prsu, osvěta, psychoterapie atd.). Tagy slouží k tomu, aby si vás snáze našli ti, kteří by mohli využít vaše služby či informace.',
                ],
                'attributes' => [
                    'data-placeholder' => 'Vyberte tagy...',
                    'data-tags' => true,
                ]
            ])
            ->add([
                'type' => Textarea::class,
                'name' => 'perex',
                'options' => [
                    'label' => 'Základní informace',
                    'help-block' => sprintf('Zde máte prostor napsat pár klíčových informací o vaší činnosti. Délka textu je omezena na %d znaků.', OrganizationFormConstants::PEREX_MAX_LENGTH),
                ],
                'attributes' => [
                    'data-max' => OrganizationFormConstants::PEREX_MAX_LENGTH,
                ],
            ])
            ->add([
                'type' => Textarea::class,
                'name' => 'article',
                'options' => [
                    'label' => 'O vás',
                    'editor' => 'ckeditor',
                    'help-block' => 'Zde máte prostor napsat důležité informace, které by o vás měla vědět veřejnost, která se bude na vás obracet.',
                ],
            ])
            ->add([
                'type' => File::class,
                'name' => 'photoMain',
                'error_message' => 'Vyberte hlavní foto.',
                'options' => [
                    'label' => 'Hlavní foto',
                    'help-block' => sprintf('Nahrajte fotografii, která nejlépe vystihuje vaši činnost (nikoliv však logo). Maximální velikost fotografie je %s ve formátu %s', $this->fileSize(OrganizationFormConstants::PHOTO_FILE_SIZE), $photoFormat),
                ],
            ])
            ->add([
                'type' => File::class,
                'name' => 'photo1',
                'options' => [
                    'label' => 'Foto #1',
                    'help-block' => sprintf('Nahrajte fotografii ve velikosti maximálně %s a formátu %s.', $this->fileSize(OrganizationFormConstants::PHOTO_FILE_SIZE), $photoFormat),
                ],
            ])
            ->add([
                'type' => File::class,
                'name' => 'photo2',
                'options' => [
                    'label' => 'Foto #2',
                    'help-block' => sprintf('Nahrajte fotografii ve velikosti maximálně %s a formátu %s.', $this->fileSize(OrganizationFormConstants::PHOTO_FILE_SIZE), $photoFormat),
                ],
            ])
            ->add([
                'type' => File::class,
                'name' => 'photo3',
                'options' => [
                    'label' => 'Foto #3',
                    'help-block' => sprintf('Nahrajte fotografii ve velikosti maximálně %s a formátu %s.', $this->fileSize(OrganizationFormConstants::PHOTO_FILE_SIZE), $photoFormat),
                ],
            ])
            ->add([
                'type' => File::class,
                'name' => 'photo4',
                'options' => [
                    'label' => 'Foto #4',
                    'help-block' => sprintf('Nahrajte fotografii ve velikosti maximálně %s a formátu %s.', $this->fileSize(OrganizationFormConstants::PHOTO_FILE_SIZE), $photoFormat),
                ],
            ])
            ->add([
                'type' => Url::class,
                'name' => 'homepage',
                'options' => [
                    'label' => 'Web',
                    'help-block' => 'Vložte odkaz na vaše webové stránky.',
                ],
            ])
            ->add([
                'type' => Url::class,
                'name' => 'facebook',
                'options' => [
                    'label' => 'Facebook',
                    'help-block' => 'Vložte odkaz na vaši facebookovou stránku.',
                ],
            ])
            ->add([
                'type' => Url::class,
                'name' => 'instagram',
                'options' => [
                    'label' => 'Instagram',
                    'help-block' => 'Vložte odkaz na váš instagramový profil.',
                ],
            ])
            ->add([
                'type' => Url::class,
                'name' => 'twitter',
                'options' => [
                    'label' => 'Twitter',
                    'help-block' => 'Vložte odkaz na váš účet Twitteru.',
                ],
            ])
            ->add([
                'type' => Url::class,
                'name' => 'linkedin',
                'options' => [
                    'label' => 'LinkedIn',
                    'help-block' => 'Vložte odkaz na váš profil LinkedIn.',
                ],
            ])
            ->add([
                'type' => Url::class,
                'name' => 'youtube',
                'options' => [
                    'label' => 'YouTube',
                    'help-block' => 'Vložte odkaz na váš Youtube kanál.',
                ],
            ])
            ->add([
                'type' => Text::class,
                'name' => 'phone',
                'options' => [
                    'label' => 'Telefon',
                    'help-block' => 'Vyplňte kontaktní telefon.',
                ],
            ])
            ->add([
                'type' => Email::class,
                'name' => 'email',
                'options' => [
                    'label' => 'E-mail',
                    'help-block' => 'Vyplňte kontaktní email.',
                ],
            ])
            ->add([
                'type' => Text::class,
                'name' => 'officialName',
                'options' => [
                    'label' => 'Oficiální název',
                    'help-block' => 'Vyplňte oficiální název organizace/projektu/nadace, jak je zapsán ve Veřejném rejstříku.',
                ],
            ])
            ->add([
                'type' => Text::class,
                'name' => 'companyId',
                'options' => [
                    'label' => 'IČO',
                    'help-block' => 'Vyplňte IČO organizace/projektu/nadace.',
                ],
            ])
            ->add([
                'type' => Text::class,
                'name' => 'street',
                'options' => [
                    'label' => 'Ulice',
                    'help-block' => 'Vyplňte ulici včetně orientačního čísla, kde sídlíte.',
                ],
            ])
            ->add([
                'type' => Text::class,
                'name' => 'city',
                'options' => [
                    'label' => 'Obec',
                    'help-block' => 'Vyplňte obec, ve které sídlíte.',
                ],
            ])
            ->add([
                'type' => Text::class,
                'name' => 'zip',
                'options' => [
                    'label' => 'PSČ',
                    'help-block' => 'Uveďte 5 místné poštovní směrovací číslo místa, kde sídlíte.',
                ],
            ])
            ->add([
                'type' => Location::class,
                'name' => 'locationLat',
                'options' => [
                    'label' => 'Lokace',
                    'longitude_property' => 'locationLng',
                    'save_as' => 'elements',
                    'default_center' => ['lat' => 49.8174919, 'lng' => 15.472962],
                    'default_zoom' => 6,
                    'enable_download_data' => true,
                    'download_data_from' => ['officialName', 'street', 'city'],
                    'help-block' => 'Na základě uvedené adresy se zobrazí umístění vašeho sídla. Pokud umístění zcela neodpovídá realitě, je možné posunout na mapě ikonku, kam je třeba.',
                ],
                'attributes' => [
                    'placeholder' => 'Zeměpisná šířka',
                ]
            ])
            ->add([
                'type' => Text::class,
                'name' => 'locationLng',
                'attributes' => [
                    'placeholder' => 'Zeměpisná délka',
                ]
            ])
            ->add([
                'type' => Radio::class,
                'name' => 'operatingArea',
                'options' => [
                    'label' => 'Projekt působí',
                    'value_options' => OrganizationOperatingArea::__VALUE_OPTIONS,
                    'help-block' => 'Zvolte možnost působnosti vašeho projektu v rámci ČR',
                    'disable_inarray_validator' => true,
                ],
            ])
            ->add([
                'type' => Checkbox::class,
                'name' => 'moreVenues',
                'options' => [
                    'label' => 'Máme více poboček',
                    'help-block' => 'Máte-li v ČR více poboček, kde vás lidé mohou zastihnout a kontaktovat, doplňte nížekontaktní údaje a my je za vás doplníme.',
                ],
            ])
            ->add([
                'type' => Textarea::class,
                'name' => 'notes',
                'options' => [
                    'label' => 'Další poznámky pro tým Fuck Cancer',
//                    'help-block' => '',
                ],
            ])
            ->add([
                'type' => Submit::class,
                'options' => [
                    'label' => 'Odeslat',
                ]
            ])
        ;

        $this->get('email')->getValidator()->setMessage('Vyplňte e-mail ve správném formátu');
        $this->get('category')->getInputSpecification()['validators'][0]->setMessage('Vyberte alespoň jednu kategorii ze seznamu.');
    }

    private function fileSize($bytes): string
    {
        return (new FileSizeHelper())($bytes, 0);
    }
}