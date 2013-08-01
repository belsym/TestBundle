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
        self::$kernel = new TestKernel('test', true);
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
     * create the database schema
     */
    public function generateSchema()
    {
        $metadata = $this->getMetaData();
        if (!empty($metadata))
        {
            $tool = new SchemaTool($this->getEntityManager());
            $this->dropSchema($tool, $metadata);
            $tool->createSchema($metadata);
            $this->dbActive = true;
        }
    }

    /**
     * drop the database schema. Will exit early if the $dbActive flag is not set
     *
     * @param SchemaTool $tool
     * @param null $metadata
     */
    public function dropSchema(SchemaTool $tool = null, $metadata = null)
    {
        if(!$this->dbActive) return;

        if(null === $metadata)
            $metadata = $this->getMetadata();

        if(!empty($metadata))
        {
            if(null === $tool)
            {
                $tool = new SchemaTool($this->getEntityManager());
            }
            $tool->dropSchema($metadata);
            $this->dbActive = false;
        }
    }

    /**
     * Retrieve the Doctrine Metadata required to build the schema
     *
     * @return array
     */
    public function getMetadata()
    {
        return $this->getEntityManager()->getMetadataFactory()->getAllMetadata();
    }

    /**
     * Get an instance of the Doctrine EntityManager
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->getContainer()->get('doctrine')->getEntityManager();
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

class TestKernel extends Kernel
{
    /**
     * Returns an array of bundles to registers.
     *
     * @return BundleInterface[] An array of bundle instances.
     *
     * @api
     */
    public function registerBundles()
    {
        // TODO: Implement registerBundles() method.
    }

    /**
     * Loads the container configuration
     *
     * @param LoaderInterface $loader A LoaderInterface instance
     *
     * @api
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        // TODO: Implement registerContainerConfiguration() method.
    }

}
