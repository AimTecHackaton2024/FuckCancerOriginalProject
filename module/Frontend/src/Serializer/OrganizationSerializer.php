<?php declare(strict_types=1);


namespace Frontend\Serializer;

use Adminaut\Manager\FileManager;
use Core\Entity\Organization;
use Core\Entity\OrganizationCategory;

class OrganizationSerializer
{
    /**
     * @var FileManager
     */
    private $fileManager;

    public function __construct(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    /**
     * @param Organization[] $organizations
     */
    public function serializeAll(array $organizations): array
    {
        $output = [];
        foreach ($organizations as $organization) {
            $output[$organization->getId()] = $this->serialize($organization);
        }

        return $output;
    }

    public function serialize(Organization $organization): array
    {
        return [
            'id' => $organization->getId(),
            'title' => $organization->getTitle(),
            'perex' => $organization->getPerex(),
            'category' => $this->serializeCategory($organization->getCategory()),
            'tags' => [], // TODO
            'article' => $organization->getArticle(),
            'main_photo' => $organization->getPhotoMain() ? $this->fileManager->getThumbImage($organization->getPhotoMain(), 1200) : null,
            'main_photo_thumbnail' => $organization->getPhotoMain() ? $this->fileManager->getThumbImage($organization->getPhotoMain(), 640, 360, 'thumbnail') : null,
            'photos' => $this->serializePhotos($organization),
            'homepage' => $organization->getHomepage(),
            'social_links' => [
                'facebook' => $organization->getFacebook(),
                'instagram' => $organization->getInstagram(),
                'twitter' => $organization->getTwitter(),
                'linkedin' => $organization->getLinkedin(),
                'youtube' => $organization->getYoutube(),
            ],
            'phone' => $organization->getPhone(),
            'email' => $organization->getEmail(),
            'location' => [
                'lat' => (float) $organization->getLocationLat(),
                'lng' => (float) $organization->getLocationLng(),
            ],
            'official_name' => $organization->getOfficialName(),
            'street' => $organization->getStreet(),
            'city' => $organization->getCity(),
            'zip' => $organization->getZip(),
        ];
    }

    private function serializeCategory(?OrganizationCategory $category): ?array
    {
        if (null === $category) {
            return null;
        }

        return [
            'title' => $category->getTitle(),
            'icon' => $category->getIcon(),
        ];
    }

    private function serializePhotos(Organization $organization): array
    {
        $photos = [
            $organization->getPhoto1(),
            $organization->getPhoto2(),
            $organization->getPhoto3(),
            $organization->getPhoto4(),
        ];

        return array_filter(array_map(function($photo) {
            if (null === $photo) {
                return null;
            }

            return [
                'url' => $this->fileManager->getThumbImage($photo, 1280),
                'thumbnail' => $this->fileManager->getThumbImage($photo, 640, 640, 'thumbnail')
            ];
        }, $photos));
    }
}