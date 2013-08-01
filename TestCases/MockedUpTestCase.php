<?php
/**
 * TODO add a file description
 */
namespace Belsym\TestBundle\TestCases;

use \Mockery as m;

/**
 * Provides a usable base class that can build a completely configurable
 * concrete (mock) instance of the Symfony ContainerInterface.
 *
 * Use this as the base class for your test cases when you need some services
 * but don't need to fire up the entire application to obtain them.
 *
 * For detailed documentation about PHP Mockery, see {@link https://github.com/padraic/mockery}
 *
 * h4. Behaviour Modifiers
 *
 * For behaviour modifiers, simply pass null as the value of the command item:
 *
 *     array(
 *         // ...
 *         'shouldIgnoreMissing'  => null,
 *         'makePartial'          => null
 *         // ...
 *     );
 *
 * h4. Expectations
 *
 * For expectations, an array should be built with key => value pairs where
 * the key is the name of the Expectation you are setting and the value is the
 * argument (or arguments) you are passing to that expectation.
 *
 *      array(
 *         'shouldReceive' => method_name,
 *         'with'          => arguments,
 *         'andReturn'     => return_value
 *      )
 *
 * For full details of Expectation Decleration see {@link https://github.com/padraic/mockery#expectation-declarations}
 *
 * __Note!__ The following are NOT possible to use
 *
 * * shouldReceive syntax `shouldReceive(value1, value2, ...)
 * * andReturn syntaxt `andReturn(value1, value2, ...)
 *
 * @example
 *
 *      // how to build pre-configured mock objects using this class where the
 *      // $commands parameter is an array of commands already constructed
 *      $service = m::mock('\Namespace\For\The\Desired\ServiceOrInterface', function(mock) use($commands) {
 *          MockedUpTestCase::configureMock($mock, commands)
 *      });
 *
 * @package Belsym\TestBundle\TestCases
 * @author:    Matt Keeble <matt.keeble@gmail.com>
 * @copyright: (c) 2013 Beldougie Ltd
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class MockedUpTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Configures a mock based on an array of commands.
     *
     * The array can either simply contain a list of commands in key => value
     * pairs __OR__  an array of arrays containing lists of commands.
     *
     * The first option is obviously only usable if all your commands are
     * unique but more complex configurations can be achieved by using the
     * second option.
     *
     *      array(
     *          'shouldReceive' => method_name,
     *          'with'          => arguments,
     *          'andReturn'     => return_value
     *      );
     *
     * __OR__
     *      array(
     *          array(
     *              'shouldReceive' => method_name,
     *              'with'          => arguments,
     *              'andReturn'     => return_value
     *          ),
     *          array(
     *              'shouldReceive' => method_name,
     *              'with'          => arguments,
     *              'andReturn'     => return_value
     *          )
     *      )
     *
     * If you want to use inline arguments for the expectations that support
     * them, you should provide a zero-indexed array of arguments. The values
     * of this array can be anything the method you are calling supports.
     *
     * E.g. if you had a method `count($a, $b)` on a mock object, you could
     * provide the following commands array:
     *
     *      array(
     *          'shouldReceive' => 'count',
     *          'with'          => array(2, 2),
     *          'andReturn'     => 4
     *      )
     *
     * __Note!__ If you wish to use inline arguments with _shouldReceive_ and
     * _andReturn_, you need to suffix the command names with `-inline`.
     * Otherwise the array will be passed as is rather than an argument list.
     *
     * @param \Mockery\MockInterface $mock
     * @param array $commands
     * @return \Mockery\MockInterface
     */
    public static function configureMock(\Mockery\MockInterface $mock, array $commands)
    {
        foreach ($commands as $method => $args)
        {
            if(is_numeric($method))
            {

                if(!is_array($args))
                {
                    throw new \InvalidArgumentException("You must pass only pass an array of commands with a numeric key");
                }

                foreach($args as $func => $params)
                {
                   $mock = $mock->$func($params);
                }

                continue;
            }

            switch($method)
            {
                case 'shouldReceive-inline':
                case 'andReturn-inline':
                case 'andReturnUsing':
                case 'with':
                    if(is_array($args) && is_numeric(key($args)))
                    {
                        $method = str_replace('-inline', '', $method);
                        $mock = call_user_func_array(array($mock, $method), $args);
                        continue 2;
                    }

                    if(strpos($method, '-inline') !== false)
                        throw new \InvalidArgumentException('To use "'.$method.'" with inline arguments, you must use a zero-indexed array for the arguments. Please check the api documentation');
            }

            $mock = $mock->$method($args);
        }

        return $mock;
    }

    /**
     * Wrapped for configureMock() that uses \Mockery to build the mock from
     * the classname or object passed as the 1st argument.
     *
     * @param string|\Object$object
     * @param array $config
     * @return \Mockery\MockInterface|\Yay_MockObject
     */
    public static function getConfiguredMock($object, $config = array())
    {
        return m::mock($object, function($mock) use ($config) {
             MockedUpTestCase::configureMock($mock, $config);
        });
    }

    /**
     * {@inheritDoc}
     */
    public function tearDown()
    {
        m::close();
    }
}
