<?php
/**
 * TODO add a file description
 */
namespace Belsym\TestBundle\Test\TestCases;

use Belsym\TestBundle\TestCases\MockContainerAwareTestCase;
use \Mockery as m;

/**
 * Class MockContainerAwareTestCaseTest
 *
 * TODO add a class description
 *
 * @package Bel
 * @author:    Matt Keeble <matt.keeble@gmail.com>
 * @copyright: (c) 2013 Beldougie Ltd
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class MockContainerAwareTestCaseTest extends \PHPUnit_Framework_TestCase
{
    public function testBuildMockContainer()
    {
        $testCase = new MockContainerAwareTestCase();

        $container = $testCase->buildMockContainer();
        $this->assertInstanceOf('\Symfony\Component\DependencyInjection\ContainerInterface', $container);
    }

    public function mockCommands()
    {
        $em = m::mock('Doctrine\ORM\EntityManager');
        return array(
            array(
                array(
                    'shouldReceive' => 'get',
                    'with'          => 'doctrine',
                    'andReturn'     => 'Whoop',
                ),
            ),
            array(
                array(
                    'shouldReceive' => 'get',
                    'with'          => 'doctrine',
                    'andReturn'     => $em
                )
            ),
        );
    }
}
