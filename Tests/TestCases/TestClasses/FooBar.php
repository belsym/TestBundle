<?php
/**
 * TODO add a file description
 */
namespace Belsym\TestBundle\Tests\TestCases\TestClasses;

/**
 * Class FooBar
 *
 * TEST PURPOSES ONLY
 *
 * @package Belsym\TestBundle\Test\TestCases
 * @author:    Matt Keeble <matt.keeble@gmail.com>
 * @copyright: (c) ${YEAR} Beldougie Ltd
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class FooBar
{

    public function foo($argument)
    {
        return 'You passed me "' . $argument . '"';
    }

    public function bar()
    {
        return 'I am bar! Hear me ROAR!';
    }

    public function bar2()
    {
        return 'I am son of bar!';
    }

    public function bar3()
    {
        return $this->bar2();
    }

    public function addTwoDigits($a, $b)
    {
        return $a + $b;
    }

}
