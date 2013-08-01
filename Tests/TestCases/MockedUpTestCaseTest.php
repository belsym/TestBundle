<?php
/**
 * TODO add a file description
 */
namespace Belsym\TestBundle\Test\TestCases;
use Belsym\TestBundle\TestCases\MockedUpTestCase;
use \Mockery as m;

/**
 * Class MockedUpTestCaseTest
 *
 * TODO add a class description
 *
 * @package Belsym\TestBundle\Test\TestCases
 * @author:    Matt Keeble <matt.keeble@gmail.com>
 * @copyright: (c) 2013 Beldougie Ltd
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class MockedUpTestCaseTest extends \PHPUnit_Framework_TestCase
{
    /** @dataProvider simpleCommands */
    public function testConfigureSimpleCommands($commands)
    {
        $mObject = $this->getObject($commands);
        $method = $commands['shouldReceive'];
        $this->assertEquals($commands['andReturn'], $mObject->$method($commands['with']), 'Should receive "'.$commands['andReturn'] .'" when the method "'.$commands['shouldReceive'].'" is called with "'.$commands['with'].'"');
    }

    /** @dataProvider extendedCommands */
    public function testConfigureExtendedCommands($commands)
    {
        $mObject = $this->getObject($commands);

        foreach($commands as $commandSet)
        {
            $method = $commandSet['shouldReceive'];
            if(is_array($commandSet['shouldReceive']))
            {
                $i = 0;
                foreach($commandSet['shouldReceive'] as $method => $value)
                {
                    if($i<count($commandSet['andReturn']))
                    {
                        $i++;
                    }
                    $returnValue = $commandSet['andReturn'][$i];
                    $this->assertEquals(
                        $returnValue,
                        $mObject->$method($value),
                        'Should recieve "'.$returnValue.'" when the method "'.$method.'" is called with "'.$value.'" and i = '.$i);
                }
            }
            else
            {
                $this->assertEquals(
                    $commandSet['andReturn'],
                    $mObject->$method($commandSet['with']),
                    'Should receive "'.$commandSet['andReturn'] .'" when the method "'.$commandSet['shouldReceive'].'" is called with "'.$commandSet['with'].'".');
            }
        }
    }

    /** @dataProvider shouldReceiveChainedCommands */
    public function testShouldReceiveArguments($commands)
    {
        $mObject = $this->getObject($commands);

        if(array_key_exists('shouldReceive', $commands))
        {
            $args = $commands['shouldReceive'];

            if(is_array($args))
            {
                $methods = array_keys($args);
                  $returns = array_values($args);

                foreach($methods as $i => $method)
                {
                    $this->assertEquals($returns[$i], $mObject->$method(), '"'.$method.'" should return "'.$returns[$i].'" on run '.$i);
                }
            }
            elseif(is_string($args))
            {
                $params = isset($commands['with']) ? $commands['with'] : null;
                $expectedResult = $commands['andReturn'];

                if(is_array($params))
                {
                    $executedResult = call_user_func_array(array($mObject, $args), $params);
                }
                else
                {
                    $executedResult = $mObject->$args($params);
                }

                $this->assertEquals($expectedResult, $executedResult, '"'.$args.'" should return "'.$expectedResult.'"');
            }
            else
            {
                $this->markTestSkipped('Not implemented closure handling yet');
            }

        }
        elseif(array_key_exists('shouldReceive-inline', $commands))
        {
            $methods = $commands['shouldReceive-inline'];
            $results = $commands['andReturn-inline'];
            $result = null;

            foreach($methods as $i => $method)
            {
                $result = ($i < count($results) ? $results[$i] : $result);

                $this->assertEquals($result, $mObject->$method(), '"'.$method.'" should return "'.$results[$i].'" on run '.$i);
            }
        }
    }

    public function tearDown()
    {
        m::close();
    }

    /*** DataProviders ***/

    public function shouldReceiveChainedCommands()
    {
        return array(
            // data set 0
            array(
                array(
                    'shouldReceive' => array('bar' => 'I am bar! Hear me ROAR!', 'bar2' => 'I am son of bar!')
                )
            ),
            // data set 1
            array(
                array(
                    'shouldReceive' => 'bar',
                    'andReturn'     => 'I am bar! Hear me ROAR!'
                )
            ),
            // data set 2
            array(
                array(
                    'shouldReceive' => 'foo',
                    'with'          => 'something',
                    'andReturn'     => 'You passed me "something"'
                )
            ),
            // data set 3
            array(
                array(
                    'shouldReceive' => 'addTwoDigits',
                    'with'          => array(2, 2),
                    'andReturn'     => 4
                )
            ),
            // data set 4
            array(
                array(
                    array(
                        'shouldReceive' => array('bar'),
                        'andReturn' => 'I am bar! Hear me ROAR!',
                    ),
                    array(
                        'shouldReceive' => array('bar2'),
                        'andReturn' => 'I am son of bar!'
                    ),
                    array(
                        'shouldReceive' => array('bar3'),
                        'andReturn' => 'I am son of bar!'
                    ),
                    array(
                        'shouldReceive' => 'addTwoDigits',
                        'with'          => array(2,2),
                        'andReturn'     => '4',
                    )

                )
            )
        );
    }

    public function simpleCommands()
    {
        return array(
            // data set 0
            array(
                array(
                    'shouldReceive' => 'get',
                    'with'          => 'doctrine',
                    'andReturn'     => 'WHOOP!',
                ),
            ),
            // data set 1
            array(
                array(
                    'shouldReceive' => 'get',
                    'with'          => 'something else',
                    'andReturn'     => 'Oh! Golly Gosh!'
                )
            ),
        );
    }

    public function extendedCommands()
    {
        return array(
            // data set 0
            array(
                array(
                    array(
                        'shouldReceive' => 'get',
                        'with'          => 'foo',
                        'andReturn'     => 'FOO!',
                    ),
                    array(
                        'shouldReceive' => 'get',
                        'with'          => 'bar',
                        'andReturn'     => 'BAR!'
                    )
                ),
            ),
        );
    }

    private function getObject($commands)
    {
        return m::mock('\Belsym\TestBundle\Tests\TestCases\TestClasses\FooBar', function($mock) use($commands){
            MockedUpTestCase::configureMock($mock, $commands);
        });
    }
}
