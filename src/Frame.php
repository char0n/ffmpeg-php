<?php

/**
 * Frame represents one frame from the movie
 *
 * @author char0n (Vladimír Gorej, vladimir.gorej@gmail.com)
 * @package FFmpegPHP
 * @license New BSD
 * @version 2.6
 */

namespace Char0n\FFMpegPHP;

class Frame implements \Serializable
{

    protected static $EX_CODE_NO_VALID_RESOURCE = 334563;

    /**
     * GdImage binary data
     *
     * @var string
     */
    protected $gdImageData;
    /**
     * Presentation time stamp
     *
     * @var float
     */
    protected $pts;

    /**
     * Frame width in pixels
     *
     * @var int
     */
    protected $width;

    /**
     * Frame height in pixels
     *
     * @var int
     */
    protected $height;

    /**
     * Create a Frame object from a GD image.
     *
     * @param resource $gdImage image resource of type gd
     * @param float    $pts frame presentation timestamp; OPTIONAL parameter; DEFAULT value 0.0
     *
     * @throws \UnexpectedValueException
     */
    public function __construct($gdImage, $pts = 0.0)
    {
        if (!(is_resource($gdImage) && 'gd' === get_resource_type($gdImage))) {
            throw new \UnexpectedValueException(
                'Param given by constructor is not valid gd resource',
                self::$EX_CODE_NO_VALID_RESOURCE
            );
        }

        $this->gdImageData = $this->gdImageToBinaryData($gdImage);
        $this->width = imagesx($gdImage);
        $this->height = imagesy($gdImage);
        $this->pts = $pts;
    }

    protected function gdImageToBinaryData($gdImage)
    {
        ob_start();
        imagegd2($gdImage);

        return ob_get_clean();
    }

    /**
     * Return the presentation time stamp of the frame.
     *
     * @return float
     */
    public function getPresentationTimestamp()
    {
        return $this->getPTS();
    }

    /**
     * Return the presentation time stamp of the frame; alias $frame->getPresentationTimestamp()
     *
     * @return float
     */
    public function getPTS()
    {
        return $this->pts;
    }

    /**
     * Crop the frame.
     *
     * NOTE: Crop values must be even numbers.
     *
     * @param int $cropTop    Rows of pixels removed from the top of the frame.
     * @param int $cropBottom Rows of pixels removed from the bottom of the frame.
     * @param int $cropLeft   Rows of pixels removed from the left of the frame.
     * @param int $cropRight  Rows of pixels removed from the right of the frame.
     *
     * @return void
     */
    public function crop($cropTop, $cropBottom = 0, $cropLeft = 0, $cropRight = 0)
    {
        $this->resize($this->getWidth(), $this->getHeight(), $cropTop, $cropBottom, $cropLeft, $cropRight);
    }

    /**
     * Resize and optionally crop the frame. (Cropping is built into ffmpeg resizing so I'm providing it here for
     * completeness.)
     *
     * NOTE: Cropping is always applied to the frame before it is resized. Crop values must be even numbers.
     *
     * @param int $width      New width of the frame (must be an even number).
     * @param int $height     New height of the frame (must be an even number).
     * @param int $cropTop    Rows of pixels removed from the top of the frame.
     * @param int $cropBottom Rows of pixels removed from the bottom of the frame.
     * @param int $cropLeft   Rows of pixels removed from the left of the frame.
     * @param int $cropRight  Rows of pixels removed from the right of the frame.
     *
     * @return void
     */
    public function resize($width, $height, $cropTop = 0, $cropBottom = 0, $cropLeft = 0, $cropRight = 0)
    {
        $widthCrop = ($cropLeft + $cropRight);
        $heightCrop = ($cropTop + $cropBottom);
        $width -= $widthCrop;
        $height -= $heightCrop;
        $resizedImage = imagecreatetruecolor($width, $height);
        $gdImage = $this->toGDImage();
        imagecopyresampled(
            $resizedImage,
            $gdImage,
            0,
            0,
            $cropLeft,
            $cropTop,
            $width,
            $height,
            $this->getWidth() - $widthCrop,
            $this->getHeight() - $heightCrop
        );
        imageconvolution(
            $resizedImage,
            [
                [-1, -1, -1],
                [-1, 24, -1],
                [-1, -1, -1],
            ],
            16,
            0
        );

        $this->gdImageData = $this->gdImageToBinaryData($resizedImage);
        $this->width = imagesx($resizedImage);
        $this->height = imagesy($resizedImage);
        imagedestroy($gdImage);
        imagedestroy($resizedImage);
    }

    /**
     * Returns a truecolor GD image of the frame.
     *
     * @return resource resource of type gd
     */
    public function toGDImage()
    {
        return imagecreatefromstring($this->gdImageData);
    }

    /**
     * Return the width of the frame.
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Return the height of the frame.
     *
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * String representation of a Frame
     *
     * @return string the string representation of the object or null
     */
    public function serialize()
    {
        $data = [
            $this->gdImageData,
            $this->pts,
            $this->width,
            $this->height,
        ];

        return serialize($data);
    }

    /**
     * Constructs the Frame
     *
     * @param string $serialized The string representation of the object.
     *
     * @return void
     */
    public function unserialize($serialized)
    {
        list(
            $this->gdImageData,
            $this->pts,
            $this->width,
            $this->height
            ) = unserialize($serialized);
    }
}
