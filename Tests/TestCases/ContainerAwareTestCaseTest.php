<?php
/**
 * TODO add a file description
 */
use Belsym\TestBundle\TestCases\ContainerAwareTestCase;

require_once __DIR__.'/../../../../../app/AppKernel.php';

/**
 * Class ContainerAwareTestCaseTest
 * 
 * Tests the functionality of the ContainerAwareTestCase abstract class
 * 
 * @author:    Matt Keeble <matt.keeble@gmail.com>
 * @copyright: (c) 2013 Beldougie Ltd
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class ContainerAwareTestCaseTest extends \PHPUnit_Framework_TestCase
{
    public function testKernelIsCreatedBeforeClass()
    {
        $foo = new FakeContainerAwareTestCase();
        $foo::setUpBeforeClass();

        $this->assertInstanceOf('\AppKernel', $foo->getKernel(), 'Kernel is not an instance of "\AppKernel"');
    }

    public function testKernelIsOnlyActiveWhileTestsAreRunning()
    {
        $foo = new FakeContainerAwareTestCase();
        $foo::setUpBeforeClass();

        $this->assertEquals(null, $foo->getContainer(), 'result of getContainer() should be null between tests');

        $foo->setUp();

        $this->assertInstanceOf(
            '\Symfony\Component\DependencyInjection\ContainerInterface',
            $foo->getContainer(),
            'result of getContainer() should be an instance of "\Symfony\Component\DependencyInjection\ContainerInterface" during each test');

        $foo->tearDown();

        $this->assertEquals(null, $foo->getContainer(), 'result of getContainer() should be null between tests');

    }

    public function testEntityManagerIsAvailable()
    {
        $foo = new FakeContainerAwareTestCase();
        $foo::setUpBeforeClass();
        $foo->setUp();

        $this->assertInstanceOf('\Doctrine\ORM\EntityManager', $foo->getEntityManager(), 'result of getEntityManager() should be an instance of "\Doctrine\ORM\EntityManager" during tests');

        $foo->tearDown();

    }
}

/**
 * Class FakeContainerAwareTestCase
 *
 * Empty, functionless class to enable instantiation of an abstract class
 *
 * @author:    Matt Keeble <matt.keeble@gmail.com>
 * @copyright: (c) ${YEAR} Beldougie Ltd
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class FakeContainerAwareTestCase extends ContainerAwareTestCase
{
    public function getKernel()
    {
        return static::$kernel;
    }
}