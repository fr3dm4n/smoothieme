<?php

/**
 * Class Smoothieme_Thumbnail
 */
class Smoothieme_Thumbnail {
    private $imagePath = null;
    const DEFAULT_BACKGROUND_COLOR= "white";
    /**
     * @var Imagick
     */
    private $image = null;

    /**
     * Validiert ob Datei eine Bilddatei jpg, gif oder png-Datei ist
     * @param $filePath
     * @return bool
     */
    public static function isImage($filePath) {
        $info = getimagesize($filePath);
        $image_type = $info[2];
        return in_array($image_type, array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP));
    }

    /**
     * konst
     * @param $imageFilePath
     */
    public function __construct($imageFilePath) {
        $this->imagePath = $imageFilePath;

        if (!is_file($imageFilePath)) {
            throw new InvalidArgumentException("Path to ressource Image is invalid");
        }

        if (!self::isImage($imageFilePath)) {
            throw new InvalidArgumentException("File ist not an Image");
        }
        $image = new Imagick();
        $image->setBackgroundColor(self::DEFAULT_BACKGROUND_COLOR);
        $image->readImage($imageFilePath);
        $this->image = $image;
    }

    /**
     * Lädt Original-Datei und mach Änderungen rückgängig, wenn noch nicht geschrieben
     */
    public function loadOriginal() {
        $this->image->readImage($this->imagePath);
    }

    /**
     * Ändere Bildgröße
     * @param $width int Breite des neuen Bildes
     * @param $height int Height des neuen Bildes
     * @param bool $crop Beschneidet das Bild um die längsten überstehenden Kanten
     */
    public function resize($width, $height, $crop = true) {

        $info = getimagesize($this->imagePath);
        $imgWidth = isset($info['width']) ? $info['width'] : $info[0];
        $imgHeight = isset($info['height']) ? $info['height'] : $info[1];

        // Calculate aspect ratio
        $wRatio = $width / $imgWidth;
        $hRatio = $height / $imgHeight;


        // Calculate a proportional width and height no larger than the max size.
        if (($imgWidth <= $width) && ($imgHeight <= $height)) {
            // Input is smaller than thumbnail, do nothing

        } elseif ($crop && $imgHeight > $imgWidth) {
            // Image crops vertical
            $resizeWidth = $width;
            $resizeHeight = ceil($wRatio * $imgHeight);

            $this->image->resizeImage($resizeWidth, $resizeHeight, imagick::FILTER_LANCZOS, 1, true);
            if ($crop) {
                $this->image->cropImage($width, $height, 0, ceil(($this->image->getImageHeight() - $height) / 2));
            }


        } elseif ($crop) {
            // Image crops horizontal
            $resizeWidth = ceil($hRatio * $imgWidth);
            $resizeHeight = $height;

            $this->image->resizeImage($resizeWidth, $resizeHeight, imagick::FILTER_LANCZOS, 1, true);
            if ($crop) {
                $this->image->cropImage($width, $height, ceil(($this->image->getImageWidth() - $width) / 2), 0);
            }


        } elseif (($wRatio * $imgHeight) < $width) {
            // Image fits horizontal
            $resizeWidth = $width;
            $resizeHeight = ceil($wRatio * $imgHeight);
            $this->image->resizeImage($resizeWidth, $resizeHeight, imagick::FILTER_LANCZOS, 1, true);

        } else {
            // Image fits vertical
            $resizeWidth = ceil($hRatio * $imgWidth);
            $resizeHeight = $height;
            $this->image->resizeImage($resizeWidth, $resizeHeight, imagick::FILTER_LANCZOS, 1, true);
        }


    }

    /**
     * Save the image to a file.
     * @param $fileName
     * @param int $quality
     */
    public function writeJPEG($fileName, $quality = 90) {
        if (file_exists($fileName)) {
            throw new InvalidArgumentException($fileName . " already exists");
        }
        // Set to use jpeg compression
        $this->image->setImageCompression(Imagick::COMPRESSION_JPEG);
        $this->image->setImageCompressionQuality($quality);
        // Strip out unneeded meta data
        $this->image->stripImage();

        $white=new Imagick();
        $white->newImage($this->image->getImageWidth(), $this->image->getImageHeight(), self::DEFAULT_BACKGROUND_COLOR);
        $white->compositeimage($this->image, Imagick::COMPOSITE_OVER, 0, 0);
        $white->setImageFormat('jpg');
        $white->writeImage($fileName);
    }

    /**
     * Löscht ressourcen
     */
    public function __destruct() {
        $this->image->clear();
        $this->image->destroy();
    }


}
