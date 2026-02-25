<?php

/**
 * Device Detector - The Universal Device Detection library for parsing User Agents
 *
 * @link https://matomo.org
 *
 * @license http://www.gnu.org/licenses/lgpl.html LGPL v3 or later
 */


namespace DeviceDetector\Tests\Parser\Client;

use DeviceDetector\Parser\Client\MobileApp;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Spyc;

class MobileAppTest extends TestCase
{
    /**
     * @dataProvider getFixtures
     */
    public function testParse($useragent, array $client)
    {
        $mobileAppParser = new MobileApp();
        $mobileAppParser::setVersionTruncation(MobileApp::VERSION_TRUNCATION_NONE);
        $mobileAppParser->setUserAgent($useragent);
        $this->assertEquals($client, $mobileAppParser->parse());
    }

    public static function getFixtures()
    {
        $fixtureData = Spyc::YAMLLoad(\realpath(__DIR__) . '/fixtures/mobile_app.yml');

        $fixtureData = \array_map(static function (array $item) {
            return ['useragent' => $item['user_agent'], 'client' => $item['client']];
        }, $fixtureData);

        return $fixtureData;
    }

    public function testStructureMobileAppYml()
    {
        $ymlDataItems = Spyc::YAMLLoad(__DIR__ . '/../../../regexes/client/mobile_apps.yml');

        foreach ($ymlDataItems as $item) {
            $this->assertTrue(\array_key_exists('regex', $item), 'key "regex" not exist');
            $this->assertTrue(\array_key_exists('name', $item), 'key "name" not exist');
            $this->assertTrue(\array_key_exists('version', $item), 'key "version" not exist');
        }
    }
}
