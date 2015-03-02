<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <dev.team@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Media;

use FFMpeg\Driver\FFMpegDriver;
<<<<<<< HEAD
=======
use FFMpeg\Exception\InvalidArgumentException;
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
use FFMpeg\FFProbe;
use FFMpeg\Filters\FiltersCollection;
use FFMpeg\Media\MediaTypeInterface;

abstract class AbstractMediaType implements MediaTypeInterface
{
    /** @var string */
    protected $pathfile;
    /** @var FFMpegDriver */
    protected $driver;
    /** @var FFProbe */
    protected $ffprobe;
    /** @var FiltersCollection */
    protected $filters;

    public function __construct($pathfile, FFMpegDriver $driver, FFProbe $ffprobe)
    {
<<<<<<< HEAD
=======
        $this->ensureFileIsPresent($pathfile);

>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
        $this->pathfile = $pathfile;
        $this->driver = $driver;
        $this->ffprobe = $ffprobe;
        $this->filters = new FiltersCollection();
    }

    /**
     * @return FFMpegDriver
     */
    public function getFFMpegDriver()
    {
        return $this->driver;
    }

    /**
     * @param FFMpegDriver $driver
     *
     * @return MediaTypeInterface
     */
    public function setFFMpegDriver(FFMpegDriver $driver)
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * @return FFProbe
     */
    public function getFFProbe()
    {
        return $this->ffprobe;
    }

    /**
     * @param FFProbe $ffprobe
     *
     * @return MediaTypeInterface
     */
    public function setFFProbe(FFProbe $ffprobe)
    {
        $this->ffprobe = $ffprobe;

        return $this;
    }

    /**
     * @return string
     */
    public function getPathfile()
    {
        return $this->pathfile;
    }

    /**
     * @param FiltersCollection $filters
     *
     * @return MediaTypeInterface
     */
    public function setFiltersCollection(FiltersCollection $filters)
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * @return MediaTypeInterface
     */
    public function getFiltersCollection()
    {
        return $this->filters;
    }

<<<<<<< HEAD
=======
    protected function ensureFileIsPresent($filename)
    {
        if (!is_file($filename) || !is_readable($filename)) {
            throw new InvalidArgumentException(sprintf(
                '%s is not present or not readable', $filename
            ));
        }
    }

>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    protected function cleanupTemporaryFile($filename)
    {
        if (file_exists($filename) && is_writable($filename)) {
            unlink($filename);
        }

        return $this;
    }
}
