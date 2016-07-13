<?php

namespace AppBundle\FilenameGenerator;

use Gedmo\Uploadable\FilenameGenerator\FilenameGeneratorInterface;

/**
 * Class ImageFilenameGenerator
 *
 * This class generates custom name for uploaded Image.
 */

class ImageFilenameGenerator implements FilenameGeneratorInterface
{
    /**
     * @inheritDoc
     */
    public static function generate($filename, $extension, $object = null)
    {
        $filteredFilename = preg_replace('/[^a-z0-9]+/', '-', strtolower($filename));

        if ($object) {
            $resultFilename = $object->getCreated()->getTimestamp() . '_' . $filteredFilename . $extension;
        } else {
            throw new \Exception("Unable to generate proper filename: \$object is null.");
        }

        return $resultFilename;
    }
}
