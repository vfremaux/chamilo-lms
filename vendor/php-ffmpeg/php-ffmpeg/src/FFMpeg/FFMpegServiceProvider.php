<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg;

use Doctrine\Common\Cache\ArrayCache;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use Silex\Application;
use Silex\ServiceProviderInterface;

class FFMpegServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['ffmpeg.configuration'] = array();
        $app['ffmpeg.default.configuration'] = array(
            'ffmpeg.threads'   => 4,
            'ffmpeg.timeout'   => 300,
            'ffmpeg.binaries'  => array('avconv', 'ffmpeg'),
            'ffprobe.timeout'  => 30,
            'ffprobe.binaries' => array('avprobe', 'ffprobe'),
        );
        $app['ffmpeg.logger'] = null;

        $app['ffmpeg.configuration.build'] = $app->share(function (Application $app) {
            return array_replace($app['ffmpeg.default.configuration'], $app['ffmpeg.configuration']);
        });

<<<<<<< HEAD
        $app['ffmpeg'] = $app['ffmpeg.ffmpeg'] = $app->share(function (Application $app) {
=======
        $app['ffmpeg'] = $app['ffmpeg.ffmpeg'] = $app->share(function(Application $app) {
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
            $configuration = $app['ffmpeg.configuration.build'];

            if (isset($configuration['ffmpeg.timeout'])) {
                $configuration['timeout'] = $configuration['ffmpeg.timeout'];
            }

            return FFMpeg::create($configuration, $app['ffmpeg.logger'], $app['ffmpeg.ffprobe']);
        });

        $app['ffprobe.cache'] = $app->share(function () {
            return new ArrayCache();
        });

<<<<<<< HEAD
        $app['ffmpeg.ffprobe'] = $app->share(function (Application $app) {
=======
        $app['ffmpeg.ffprobe'] = $app->share(function(Application $app) {
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
            $configuration = $app['ffmpeg.configuration.build'];

            if (isset($configuration['ffmpeg.timeout'])) {
                $configuration['timeout'] = $configuration['ffprobe.timeout'];
            }

            return FFProbe::create($configuration, $app['ffmpeg.logger'], $app['ffprobe.cache']);
        });
    }

    public function boot(Application $app)
    {
    }
}
