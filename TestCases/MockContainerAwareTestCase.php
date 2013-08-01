<?php
/**
 * TODO add a file description
 */
namespace Belsym\TestBundle\TestCases;

use \Mockery as m;

/**
 * Provides methods that enable simple mocking of the symfony container object
 * and other, commonly used services.
 *
 * @package Belsym\TestBundle\TestCases
 * @author:    Matt Keeble <matt.keeble@gmail.com>
 * @copyright: (c) 2013 Beldougie Ltd
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class MockContainerAwareTestCase extends MockedUpTestCase
{
    /**
     * Build a mock instance of Doctrine's ObjectManager
     *
     * Can be pre-configured by passing an array of commands as the only
     * parameter
     *
     * @see configureMock()
     * @see \Doctrine\ORM\EntityManager
     *
     * @param array $commands
     * @return \Mockery\MockInterface|\Yay_MockObject
     */
    public function buildEntityManager($commands = array())
    {
        $em = m::mock('\Doctrine\ORM\EntityManager', function ($mock) use ($commands) {
            MockedUpTestCase::configureMock($mock, $commands);
        });
        return $em;
    }

    /**
     * Build a mock instance of Symfony's ContainerInterface
     *
     * Can be pre-configured by passing an array of commands as the only
     * parameter
     *
     * @see configureMock()
     * @see \Symfony\Component\DependencyInjection\ContainerInterface
     *
     * @param array $commands
     * @return \Mockery\MockInterface|\Yay_MockObject
     */
    public function buildMockContainer($commands = array())
    {
        $container = m::mock('\Symfony\Component\DependencyInjection\ContainerInterface', function ($mock) use ($commands) {
            MockedUpTestCase::configureMock($mock, $commands);
        });
        return $container;
    }

    /**
     * {@inheritDoc}
     */
    public function tearDown()
    {
        m::close();
    }


}
