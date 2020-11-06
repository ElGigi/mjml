<?php
/**
 * @license   https://opensource.org/licenses/MIT MIT License
 * @copyright 2018 Ronan GIRON
 * @author    Ronan GIRON <https://github.com/ElGigi>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code, to the root.
 */

namespace ElGigi\Mjml\Tests;

use ElGigi\Mjml\Mjml;
use PHPUnit\Framework\TestCase;

class MjmlTest extends TestCase
{
    public function testStrToHtml()
    {
        $mjml = new Mjml('node ' . __DIR__ . '/../node_modules/mjml/bin/mjml');
        $output = $mjml->strToHtml(file_get_contents(__DIR__ . '/test.mjml'));

        $this->assertEquals(file_get_contents(__DIR__ . '/test.html'), $output);
    }
}
