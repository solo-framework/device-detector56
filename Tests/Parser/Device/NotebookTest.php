<?php

/**
 * Device Detector - The Universal Device Detection library for parsing User Agents
 *
 * @link https://matomo.org
 *
 * @license http://www.gnu.org/licenses/lgpl.html LGPL v3 or later
 */


namespace DeviceDetector\Tests\Parser\Device;

use DeviceDetector\Parser\Device\Notebook;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Spyc;

class NotebookTest extends TestCase
{
    /**
     * @dataProvider getFixtures
     */
    public function testParse($useragent, array $device)
    {
        $notebookParser = new Notebook();
        $notebookParser->setUserAgent($useragent);
        $this->assertNotNull($notebookParser->parse());
        $this->assertEquals($device['type'], Notebook::getDeviceName($notebookParser->getDeviceType()));
        $this->assertEquals($device['brand'], $notebookParser->getBrand());
        $this->assertEquals($device['model'], $notebookParser->getModel());
    }

    public static function getFixtures()
    {
        $fixtureData = Spyc::YAMLLoad(\realpath(__DIR__) . '/fixtures/notebook.yml');

        $fixtureData = \array_map(static function (array $item) {
            return ['useragent' => $item['user_agent'], 'device' => $item['device']];
        }, $fixtureData);

        return $fixtureData;
    }
}
