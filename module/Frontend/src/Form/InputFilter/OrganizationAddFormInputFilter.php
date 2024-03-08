<?php declare(strict_types=1);


namespace Frontend\Form\InputFilter;

use Core\Enum\OrganizationOperatingArea;
use Frontend\Constants\OrganizationFormConstants;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\InputFilter\InputFilter;
use Zend\Validator\EmailAddress;
use Zend\Validator\File\Extension;
use Zend\Validator\File\IsImage;
use Zend\Validator\File\MimeType;
use Zend\Validator\File\Size;
use Zend\Validator\File\UploadFile;
use Zend\Validator\InArray;
use Zend\Validator\NotEmpty;
use Zend\Validator\Regex;
use Zend\Validator\StringLength;

class OrganizationAddFormInputFilter extends InputFilter
{
    public function __construct()
    {
        $this
            ->add([
                'name' => 'title',
                'required' => true,
                'validators' => [
                    [
                        'name' => NotEmpty::class,
                        'options' => [
                            'messages' => [
                                NotEmpty::IS_EMPTY => 'Vyplňte název organizace.'
                            ],
                        ],
                    ]
                ],
                'filters' => [
                    [
                        'name' => StringTrim::class,
                    ],
                    [
                        'name' => StripTags::class,
                    ],
                ],
            ])
            ->add([
                'name' => 'category',
                'required' => true,
                'validators' => [
                    [
                        'name' => NotEmpty::class,
                        'options' => [
                            'messages' => [
                                NotEmpty::IS_EMPTY => 'Vyberte alespoň jednu kategorii.'
                            ],
                        ],
                    ]
                ],
            ])
            ->add([
                'name' => 'tags',
                'required' => true,
                'validators' => [
                    [
                        'name' => NotEmpty::class,
                        'options' => [
                            'messages' => [
                                NotEmpty::IS_EMPTY => 'Vyberte alespoň jeden tag.'
                            ],
                        ],
                    ]
                ],
            ])
            ->add([
                'name' => 'perex',
                'required' => true,
                'validators' => [
                    [
                        'name' => NotEmpty::class,
                        'options' => [
                            'messages' => [
                                NotEmpty::IS_EMPTY => 'Vyplnťe zakladní informace o vaší činnosti.'
                            ],
                        ],
                    ],
                    [
                        'name' => StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'max' => OrganizationFormConstants::PEREX_MAX_LENGTH,
                            'messages' => [
                                StringLength::TOO_LONG => 'Délka textu je omezena na %max% znaků.'
                            ],
                        ],
                    ],
                ],
                'filters' => [
                    [
                        'name' => StringTrim::class,
                    ],
                    [
                        'name' => StripTags::class,
                    ],
                ],
            ])
            ->add([
                'name' => 'article',
                'required' => false,
                'filters' => [
                    [
                        'name' => StringTrim::class,
                    ],
                    [
                        'name' => StripTags::class,
                        'options' => [
                            'allowTags' => ['p', 'b', 'strong', 'em', 'i', 'sub', 'sup', 'a'],
                        ],
                    ],
                ],
            ])
            ->add([
                'name' => 'photoMain',
                'required' => true,
                'validators' => [
                    [
                        'name' => UploadFile::class,
                        'break_chain_on_failure' => true,
                        'options' => [
                            'messages' => [
                                UploadFile::NO_FILE => 'Vyberte hlavní foto.'
                            ],
                        ],
                    ],
                    [
                        'name' => IsImage::class,
                        'options' => [
                            'messages' => [
                                IsImage::FALSE_TYPE => 'Vybraný soubor není obrázek.'
                            ],
                        ],
                    ],
                    [
                        'name' => Extension::class,
                        'options' => [
                            'extension' => OrganizationFormConstants::PHOTO_FILE_FORMATS,
                            'messages' => [
                                Extension::FALSE_EXTENSION => 'Vybraný soubor není ve správném formátu.'
                            ],
                        ],
                    ],
                    [
                        'name' => MimeType::class,
                        'options' => [
                            'enableHeaderCheck' => true,
                            'mimeType' => ['image/jpeg'],
                            'messages' => [
                                MimeType::FALSE_TYPE => 'Vybraný soubor není ve správném formátu.'
                            ],
                        ],
                    ],
                    [
                        'name' => Size::class,
                        'options' => [
                            'max' => OrganizationFormConstants::PHOTO_FILE_SIZE,
                            'messages' => [
                                Size::TOO_BIG => 'Vybraný soubor je příliš velký.'
                            ],
                        ],
                    ],
                ],
            ])
            ->add([
                'name' => 'photo1',
                'required' => false,
                'validators' => [
                    [
                        'name' => IsImage::class,
                        'options' => [
                            'messages' => [
                                IsImage::FALSE_TYPE => 'Vybraný soubor není obrázek.'
                            ],
                        ],
                    ],
                    [
                        'name' => Extension::class,
                        'options' => [
                            'extension' => OrganizationFormConstants::PHOTO_FILE_FORMATS,
                            'messages' => [
                                Extension::FALSE_EXTENSION => 'Vybraný soubor není ve správném formátu.'
                            ],
                        ],
                    ],
                    [
                        'name' => MimeType::class,
                        'options' => [
                            'enableHeaderCheck' => true,
                            'mimeType' => ['image/jpeg'],
                            'messages' => [
                                MimeType::FALSE_TYPE => 'Vybraný soubor není ve správném formátu.'
                            ],
                        ],
                    ],
                    [
                        'name' => Size::class,
                        'options' => [
                            'max' => OrganizationFormConstants::PHOTO_FILE_SIZE,
                            'messages' => [
                                Size::TOO_BIG => 'Vybraný soubor je příliš velký.'
                            ],
                        ],
                    ],
                ],
            ])
            ->add([
                'name' => 'photo2',
                'required' => false,
                'validators' => [
                    [
                        'name' => IsImage::class,
                        'options' => [
                            'messages' => [
                                IsImage::FALSE_TYPE => 'Vybraný soubor není obrázek.'
                            ],
                        ],
                    ],
                    [
                        'name' => Extension::class,
                        'options' => [
                            'extension' => OrganizationFormConstants::PHOTO_FILE_FORMATS,
                            'messages' => [
                                Extension::FALSE_EXTENSION => 'Vybraný soubor není ve správném formátu.'
                            ],
                        ],
                    ],
                    [
                        'name' => MimeType::class,
                        'options' => [
                            'enableHeaderCheck' => true,
                            'mimeType' => ['image/jpeg'],
                            'messages' => [
                                MimeType::FALSE_TYPE => 'Vybraný soubor není ve správném formátu.'
                            ],
                        ],
                    ],
                    [
                        'name' => Size::class,
                        'options' => [
                            'max' => OrganizationFormConstants::PHOTO_FILE_SIZE,
                            'messages' => [
                                Size::TOO_BIG => 'Vybraný soubor je příliš velký.'
                            ],
                        ],
                    ],
                ],
            ])
            ->add([
                'name' => 'photo3',
                'required' => false,
                'validators' => [
                    [
                        'name' => IsImage::class,
                        'options' => [
                            'messages' => [
                                IsImage::FALSE_TYPE => 'Vybraný soubor není obrázek.'
                            ],
                        ],
                    ],
                    [
                        'name' => Extension::class,
                        'options' => [
                            'extension' => OrganizationFormConstants::PHOTO_FILE_FORMATS,
                            'messages' => [
                                Extension::FALSE_EXTENSION => 'Vybraný soubor není ve správném formátu.'
                            ],
                        ],
                    ],
                    [
                        'name' => MimeType::class,
                        'options' => [
                            'enableHeaderCheck' => true,
                            'mimeType' => ['image/jpeg'],
                            'messages' => [
                                MimeType::FALSE_TYPE => 'Vybraný soubor není ve správném formátu.'
                            ],
                        ],
                    ],
                    [
                        'name' => Size::class,
                        'options' => [
                            'max' => OrganizationFormConstants::PHOTO_FILE_SIZE,
                            'messages' => [
                                Size::TOO_BIG => 'Vybraný soubor je příliš velký.'
                            ],
                        ],
                    ],
                ],
            ])
            ->add([
                'name' => 'photo4',
                'required' => false,
                'validators' => [
                    [
                        'name' => IsImage::class,
                        'options' => [
                            'messages' => [
                                IsImage::FALSE_TYPE => 'Vybraný soubor není obrázek.'
                            ],
                        ],
                    ],
                    [
                        'name' => Extension::class,
                        'options' => [
                            'extension' => OrganizationFormConstants::PHOTO_FILE_FORMATS,
                            'messages' => [
                                Extension::FALSE_EXTENSION => 'Vybraný soubor není ve správném formátu.'
                            ],
                        ],
                    ],
                    [
                        'name' => MimeType::class,
                        'options' => [
                            'enableHeaderCheck' => true,
                            'mimeType' => ['image/jpeg'],
                            'messages' => [
                                MimeType::FALSE_TYPE => 'Vybraný soubor není ve správném formátu.'
                            ],
                        ],
                    ],
                    [
                        'name' => Size::class,
                        'options' => [
                            'max' => OrganizationFormConstants::PHOTO_FILE_SIZE,
                            'messages' => [
                                Size::TOO_BIG => 'Vybraný soubor je příliš velký.'
                            ],
                        ],
                    ],
                ],
            ])
            ->add([
                'name' => 'homepage',
                'required' => true,
                'filters' => [
                    [
                        'name' => StringTrim::class,
                    ],
                    [
                        'name' => StripTags::class,
                    ],
                ],
                'validators' => [
                    [
                        'name' => NotEmpty::class,
                        'options' => [
                            'messages' => [
                                NotEmpty::IS_EMPTY => 'Vložte odkaz na vaše webové stránky.'
                            ],
                        ],
                    ]
                ],
            ])
            ->add([
                'name' => 'facebook',
                'required' => false,
                'validators' => [
                    [
                        'name' => Regex::class,
                        'options' => [
                            'pattern' => "/^https:\/\/(www\.)?facebook\.com\/(.+)$/",
                            'messageTemplates' => [
                                 'regexNotMatch' => 'Zadaná Facebook URL není ve správném formátu.'
                             ],
                        ],
                    ],
                ],
                'filters' => [
                    [
                        'name' => StringTrim::class,
                    ],
                    [
                        'name' => StripTags::class,
                    ],
                ],
            ])
            ->add([
                'name' => 'instagram',
                'required' => false,
                'validators' => [
                    [
                        'name' => Regex::class,
                        'options' => [
                            'pattern' => "/^https:\/\/(www\.)?instagram\.com\/(.+)$/",
                            'messageTemplates' => [
                                 'regexNotMatch' => 'Zadaná Instagram URL není ve správném formátu.'
                             ],
                        ],
                    ],
                ],
                'filters' => [
                    [
                        'name' => StringTrim::class,
                    ],
                    [
                        'name' => StripTags::class,
                    ],
                ],
            ])
            ->add([
                'name' => 'twitter',
                'required' => false,
                'validators' => [
                    [
                        'name' => Regex::class,
                        'options' => [
                            'pattern' => "/^https:\/\/(www\.)?twitter\.com\/(.+)$/",
                            'messageTemplates' => [
                                 'regexNotMatch' => 'Zadaná Twitter URL není ve správném formátu.'
                             ],
                        ],
                    ],
                ],
                'filters' => [
                    [
                        'name' => StringTrim::class,
                    ],
                    [
                        'name' => StripTags::class,
                    ],
                ],
            ])
            ->add([
                'name' => 'linkedin',
                'required' => false,
                'validators' => [
                    [
                        'name' => Regex::class,
                        'options' => [
                            'pattern' => "/^https:\/\/(?:www|.*\.)?linkedin\.com\/(.+)$/",
                            'messageTemplates' => [
                                 'regexNotMatch' => 'Zadaná LinkedIn URL není ve správném formátu.'
                             ],
                        ],
                    ],
                ],
                'filters' => [
                    [
                        'name' => StringTrim::class,
                    ],
                    [
                        'name' => StripTags::class,
                    ],
                ],
            ])
            ->add([
                'name' => 'youtube',
                'required' => false,
                'validators' => [
                    [
                        'name' => Regex::class,
                        'options' => [
                            'pattern' => "/^https:\/\/(www\.)?youtube\.com\/(.+)$/",
                            'messageTemplates' => [
                                 'regexNotMatch' => 'Zadaná YouTube URL není ve správném formátu.'
                             ],
                        ],
                    ],
                ],
                'filters' => [
                    [
                        'name' => StringTrim::class,
                    ],
                    [
                        'name' => StripTags::class,
                    ],
                ],
            ])
            ->add([
                'name' => 'phone',
                'required' => true,
                'filters' => [
                    [
                        'name' => StringTrim::class,
                    ],
                    [
                        'name' => StripTags::class,
                    ],
                ],
                'validators' => [
                    [
                        'name' => NotEmpty::class,
                        'options' => [
                            'messages' => [
                                NotEmpty::IS_EMPTY => 'Vyplňte telefonní číslo.'
                            ],
                        ],
                    ]
                ],
            ])
            ->add([
                'name' => 'email',
                'required' => true,
                'filters' => [
                    [
                        'name' => StringTrim::class,
                    ],
                    [
                        'name' => StripTags::class,
                    ],
                ],
                'validators' => [
                    [
                        'name' => NotEmpty::class,
                        'options' => [
                            'messages' => [
                                NotEmpty::IS_EMPTY => 'Vyplňte e-mail.'
                            ],
                        ],
                    ],
                ],
            ])
            ->add([
                'name' => 'locationLat',
                'required' => true,
                'filters' => [
                    [
                        'name' => StringTrim::class,
                    ],
                    [
                        'name' => StripTags::class,
                    ],
                ],
                'validators' => [
                    [
                        'name' => NotEmpty::class,
                        'options' => [
                            'messages' => [
                                NotEmpty::IS_EMPTY => 'Umístěte bod na mapě dle vašeho sídla.'
                            ],
                        ],
                    ]
                ],
            ])
            ->add([
                'name' => 'locationLng',
                'required' => true,
                'filters' => [
                    [
                        'name' => StringTrim::class,
                    ],
                    [
                        'name' => StripTags::class,
                    ],
                ],
            ])
            ->add([
                'name' => 'officialName',
                'required' => true,
                'filters' => [
                    [
                        'name' => StringTrim::class,
                    ],
                    [
                        'name' => StripTags::class,
                    ],
                ],
                'validators' => [
                    [
                        'name' => NotEmpty::class,
                        'options' => [
                            'messages' => [
                                NotEmpty::IS_EMPTY => 'Vyplňte oficiální název organizace.'
                            ],
                        ],
                    ]
                ],
            ])
            ->add([
                'name' => 'companyId',
                'required' => true,
                'filters' => [
                    [
                        'name' => StringTrim::class,
                    ],
                    [
                        'name' => StripTags::class,
                    ],
                ],
                'validators' => [
                    [
                        'name' => NotEmpty::class,
                        'options' => [
                            'messages' => [
                                NotEmpty::IS_EMPTY => 'Vyplňte IČO organizace.'
                            ],
                        ],
                    ]
                ],
            ])
            ->add([
                'name' => 'street',
                'required' => true,
                'filters' => [
                    [
                        'name' => StringTrim::class,
                    ],
                    [
                        'name' => StripTags::class,
                    ],
                ],
                'validators' => [
                    [
                        'name' => NotEmpty::class,
                        'options' => [
                            'messages' => [
                                NotEmpty::IS_EMPTY => 'Vyplňte ulici.'
                            ],
                        ],
                    ]
                ],
            ])
            ->add([
                'name' => 'city',
                'required' => true,
                'filters' => [
                    [
                        'name' => StringTrim::class,
                    ],
                    [
                        'name' => StripTags::class,
                    ],
                ],
                'validators' => [
                    [
                        'name' => NotEmpty::class,
                        'options' => [
                            'messages' => [
                                NotEmpty::IS_EMPTY => 'Vyplňte obec.'
                            ],
                        ],
                    ]
                ],
            ])
            ->add([
                'name' => 'zip',
                'required' => true,
                'filters' => [
                    [
                        'name' => StringTrim::class,
                    ],
                    [
                        'name' => StripTags::class,
                    ],
                ],
                'validators' => [
                    [
                        'name' => NotEmpty::class,
                        'options' => [
                            'messages' => [
                                NotEmpty::IS_EMPTY => 'Vyplňte PSČ.'
                            ],
                        ],
                    ]
                ],
            ])
            ->add([
                'name' => 'operatingArea',
                'required' => true,
                'validators' => [
                    [
                        'name' => NotEmpty::class,
                        'options' => [
                            'messages' => [
                                NotEmpty::IS_EMPTY => 'Zvolte jednu z možností.'
                            ],
                        ],
                    ],
                    [
                        'name' => InArray::class,
                        'options' => [
                            'haystack' => array_keys(OrganizationOperatingArea::__VALUE_OPTIONS),
                            'messages' => [
                                InArray::NOT_IN_ARRAY => 'Zvolte jednu z možností.',
                            ],
                        ],
                    ]
                ],
            ])
            ->add([
                'name' => 'moreVenues',
                'required' => false,
            ])
            ->add([
                'name' => 'notes',
                'required' => false,
                'filters' => [
                    [
                        'name' => StringTrim::class,
                    ],
                    [
                        'name' => StripTags::class,
                    ],
                ],
            ])
        ;
    }
}