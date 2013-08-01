<?php
/**
 * TODO add a file description
 */

namespace {

    use Belsym\TestBundle\BelsymTestBundle;
    use Symfony\Component\Config\Loader\LoaderInterface;
    use Symfony\Component\HttpKernel\Kernel;

    $loader = require_once __DIR__.'/vendor/autoload.php';

    class AppKernel extends Kernel
    {
        /**
         * Loads the container configuration
         *
         * @param LoaderInterface $loader A LoaderInterface instance
         *
         * @api
         */
        public function registerContainerConfiguration(LoaderInterface $loader)
        {

        }

        public function registerBundles()
        {
            return array(
                new BelsymTestBundle(),
            );
        }


    }
}