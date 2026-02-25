<?php

/**
 * Device Detector - The Universal Device Detection library for parsing User Agents
 *
 * @link https://matomo.org
 *
 * @license http://www.gnu.org/licenses/lgpl.html LGPL v3 or later
 */


namespace DeviceDetector\Tests\Parser\Device;

use DeviceDetector\Parser\Device\CarBrowser;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Spyc;

class CarBrowserTest extends TestCase
{
    /**
     * @dataProvider getFixtures
     */
    public function testParse($useragent, array $device)
    {
        $consoleParser = new CarBrowser();
        $consoleParser->setUserAgent($useragent);
        $this->assertIsArray($consoleParser->parse());
        $this->assertEquals($device['type'], CarBrowser::getDeviceName($consoleParser->getDeviceType()));
        $this->assertEquals($device['brand'], $consoleParser->getBrand());
        $this->assertEquals($device['model'], $consoleParser->getModel());
    }

    public static function getFixtures()
    {
        $fixtureData = Spyc::YAMLLoad(\realpath(__DIR__) . '/fixtures/car_browser.yml');

        $fixtureData = \array_map(static function (array $item) {
            return ['useragent' => $item['user_agent'], 'device' => $item['device']];
        }, $fixtureData);

        return $fixtureData;
    }
}
