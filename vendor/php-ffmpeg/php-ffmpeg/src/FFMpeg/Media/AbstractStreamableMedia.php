<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Media;

use FFMpeg\FFProbe\DataMapping\Stream;
use FFMpeg\FFProbe\DataMapping\StreamCollection;

abstract class AbstractStreamableMedia extends AbstractMediaType
{
<<<<<<< HEAD
    private $streams;

=======
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    /**
     * @return StreamCollection
     */
    public function getStreams()
    {
<<<<<<< HEAD
        if (null === $this->streams) {
            $this->streams = $this->ffprobe->streams($this->pathfile);
        }

        return $this->streams;
=======
        return $this->ffprobe->streams($this->pathfile);
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    }

    /**
     * @return Stream
     */
    public function getFormat()
    {
        return $this->ffprobe->format($this->pathfile);
    }
}
