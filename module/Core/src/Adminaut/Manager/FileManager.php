<?php

namespace Core\Adminaut\Manager;

use Adminaut\Entity\File as FileEntity;
use Adminaut\Manager\FileManager as AdminautFileManager;
use League\Flysystem\Adapter\Local;
use WideImage\WideImage;

/**
 * Class FileManager
 * @package Adminaut\Manager
 */
class FileManager extends AdminautFileManager
{

    /**
     * @param FileEntity $file
     * @param int $width
     * @param int $height
     * @return mixed
     * @throws \Exception
     */
    public function getThumbImage(FileEntity $image, $width = 'auto', $height = 'auto', $mode = 'clip', $cropAreaX = 'center', $cropAreaY = 'center', $bg = 'ffffff', $alpha = 0)
    {
        if ($mode !== 'thumbnail') {
            return parent::getThumbImage($image, $width, $height, $mode, $cropAreaX, $cropAreaY, $bg, $alpha);
        }

        /** @var Local $publicAdapter */
        $publicAdapter = $this->getPublicFilesystem()->getAdapter();

        /** @var string $sourcePath */
        $sourcePath = $image->getSavePath();

        /** @var string $sourceExtension */
        $sourceExtension = $image->getFileExtension();

        if ($width == 'auto' && $height == 'auto') {
            $resultPath = $sourcePath . '.' . $sourceExtension;
        } else {
            $hash = md5($mode . '&' . $cropAreaX . '&' . $cropAreaY . '&' . $bg . '&' . $alpha);
            $resultPath = $sourcePath . '-' . $width . '-' . $height . '-' . $hash . '.' . $sourceExtension;
        }

        if (!$this->getPublicFilesystem()->has($resultPath)) {
            /** @var Local $privateAdapter */
            $privateAdapter = $this->getPrivateFilesystem()->getAdapter();
            $fullPath = realpath($privateAdapter->applyPathPrefix($sourcePath));

            if($this->getPrivateFilesystem()->getMimetype($sourcePath) === 'image/svg+xml') {
                $resultPath = $sourcePath . '.svg';

                if (!$this->getPublicFilesystem()->has($resultPath)) {
                    $original = $this->getPrivateFilesystem()->read($sourcePath);
                    $this->getPublicFilesystem()->write($resultPath, $original);
                }
            } else {
                $original = WideImage::load($fullPath);
                $result = $original->copy();

                if (function_exists('exif_read_data')) {
                    $exifData = @exif_read_data($fullPath);
                    $orientation = isset($exifData['Orientation']) ? $exifData['Orientation'] : 1;
                    $result = $result->correctExif($orientation);
                }

                if ($width !== 'auto' || $height !== 'auto') {
                    $_w = $width == 'auto' ? '100%' : $width;
                    $_h = $height == 'auto' ? '100%' : $height;
                    $allowedCropAreasX = ['left', 'center', 'right'];
                    $allowedCropAreasY = ['top', 'center', 'middle', 'bottom'];
                    $_cax = in_array($cropAreaX, $allowedCropAreasX) || is_integer($cropAreaX) ? $cropAreaX : 'center';
                    $_cay = in_array($cropAreaY, $allowedCropAreasY) || is_integer($cropAreaY) ? $cropAreaY : 'center';

                    $result = $result->resize($_w, $_h, 'outside');
                    $result = $result->crop($_cax, $_cay, $_w, $_h);
                }

                $this->getPublicFilesystem()->write($resultPath, $result->asString($sourceExtension));
            }
        }

        $publicPath = str_replace(realpath($_SERVER['DOCUMENT_ROOT']), '', realpath($publicAdapter->applyPathPrefix('/')));

        // fix windows directory separators
        $publicPath = str_replace('\\', '/', $publicPath);

        // remove / from string beginning
        $publicPath = ltrim($publicPath, '/');

        return '/' . $publicPath . '/' . $resultPath;
    }
}
