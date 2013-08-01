<?php
/**
 * TODO add a file description
 */
namespace Belsym\TestBundle\TestCases;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Abstract class that enables 'real' container interaction.
 *
 * More useful for Functional tests than Unit tests as it is essentially
 * testing integration into an application.
 *
 * @author:    Matt Keeble <matt.keeble@gmail.com>
 * @copyright: (c) 2013 Beldougie Ltd
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
abstract class ContainerAwareTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Kernel $kernel
     */
    protected static $kernel;

    /**
     * @var bool $dbActive
     */
    protected $dbActive = false;

    public static function setUpBeforeClass()
    {
        if(!class_exists('\AppKernel'))
        {
            throw new \RuntimeException("You must include your project's AppKernel class");
        }
        error_log("ok");
        self::$kernel = new \AppKernel('test', true);
        error_log("out");
    }

    public function setUp()
    {
        self::$kernel->boot();
    }

    public function tearDown()
    {
        self::$kernel->shutdown();
    }

    /**
     * Get an instance of the Symfony ContainerInterface
     *
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return self::$kernel->getContainer();
    }
}