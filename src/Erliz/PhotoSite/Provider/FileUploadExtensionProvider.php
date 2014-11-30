<?php

/**
 * @author Stanislav Vetlovskiy
 * @date 30.11.2014
 */

namespace Erliz\PhotoSite\Provider;


use Erliz\PhotoSite\Service\JqueryFileUploadService;
use Silex\Application;
use Silex\ServiceProviderInterface;

class FileUploadExtensionProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Application $app An Application instance
     */
    public function register(Application $app)
    {
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     */
    public function boot(Application $app)
    {
        $options = $app['fileupload.options'];

        $app['fileupload'] = function () use ($options) {
            return new JqueryFileUploadService($options);
        };
    }
}