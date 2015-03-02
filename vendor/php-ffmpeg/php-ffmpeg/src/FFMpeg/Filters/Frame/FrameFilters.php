<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <dev.team@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Filters\Frame;

use FFMpeg\Media\Frame;

class FrameFilters
{
    private $frame;

    public function __construct(Frame $frame)
    {
        $this->frame = $frame;
    }

    /**
     * Fixes the display ratio of the output frame.
<<<<<<< HEAD
     *
     * In case the sample ratio and display ratio are different, image may be
     * anamorphozed. This filter fixes this by specifying the output size.
     *
=======
     * 
     * In case the sample ratio and display ratio are different, image may be
     * anamorphozed. This filter fixes this by specifying the output size.
     * 
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
     * @return FrameFilters
     */
    public function fixDisplayRatio()
    {
        $this->frame->addFilter(new DisplayRatioFixerFilter());

        return $this;
    }
}
