<?php

/*
 * This file is part of PHPExifTool.
 *
 * (c) 2012 Romain Neutron <imprec@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPExiftool\Driver\Tag\XMPExif;

use JMS\Serializer\Annotation\ExclusionPolicy;
use PHPExiftool\Driver\AbstractTag;

/**
 * @ExclusionPolicy("all")
 */
class GPSDestDistanceRef extends AbstractTag
{

    protected $Id = 'GPSDestDistanceRef';

    protected $Name = 'GPSDestDistanceRef';

    protected $FullName = 'XMP::exif';

    protected $GroupName = 'XMP-exif';

    protected $g0 = 'XMP';

    protected $g1 = 'XMP-exif';

    protected $g2 = 'Image';

    protected $Type = 'string';

    protected $Writable = true;

    protected $Description = 'GPS Dest Distance Ref';

    protected $local_g2 = 'Location';

    protected $Values = array(
        'K' => array(
            'Id' => 'K',
            'Label' => 'Kilometers',
        ),
        'M' => array(
            'Id' => 'M',
            'Label' => 'Miles',
        ),
        'N' => array(
            'Id' => 'N',
            'Label' => 'Nautical Miles',
        ),
    );

}
