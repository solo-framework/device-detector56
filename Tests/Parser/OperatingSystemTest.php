<?php

/**
 * Device Detector - The Universal Device Detection library for parsing User Agents
 *
 * @link https://matomo.org
 *
 * @license http://www.gnu.org/licenses/lgpl.html LGPL v3 or later
 */


namespace DeviceDetector\Tests\Parser;

use DeviceDetector\ClientHints;
use DeviceDetector\Parser\OperatingSystem;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Spyc;

class OperatingSystemTest extends TestCase
{
    protected static $osTested = [];

    /**
     * @dataProvider getFixtures
     */
    public function testParse($useragent, array $os, array $headers = null)
    {
        $osParser = new OperatingSystem();
        $osParser->setUserAgent($useragent);

        if (null !== $headers) {
            $osParser->setClientHints(ClientHints::factory($headers));
        }

        $this->assertEquals($os, $osParser->parse(), "UserAgent: {$useragent}");
        self::$osTested[] = $os['name'];
    }

    public static function getFixtures()
    {
        $fixtureData = Spyc::YAMLLoad(\realpath(__DIR__) . '/fixtures/oss.yml');

        $fixtureData = \array_map(static function (array $item) {
            return ['useragent' => $item['user_agent'], 'os' => $item['os'], 'headers' => isset($item['headers']) ? $item['headers'] : null];
        }, $fixtureData);

        return $fixtureData;
    }

    /**
     * @dataProvider getAllOs
     */
    public function testOSInGroup($os)
    {
        $familyOs = \call_user_func_array('array_merge', \array_values(OperatingSystem::getAvailableOperatingSystemFamilies()));
        $this->assertContains($os, $familyOs);
    }

    public static function getAllOs()
    {
        $allOs = \array_keys(OperatingSystem::getAvailableOperatingSystems());

        return \array_map(static function ($os) {
            return [$os];
        }, $allOs);
    }

    /**
     * @dataProvider getAllFamilyOs
     */
    public function testFamilyOSExists($os)
    {
        $allOs = \array_keys(OperatingSystem::getAvailableOperatingSystems());
        $this->assertContains($os, $allOs);
    }

    public static function getAllFamilyOs()
    {
        $allFamilyOs = \call_user_func_array('array_merge', \array_values(OperatingSystem::getAvailableOperatingSystemFamilies()));

        return \array_map(static function ($os) {
            return [$os];
        }, $allFamilyOs);
    }

    public function testGetAvailableOperatingSystems()
    {
        $this->assertGreaterThan(70, OperatingSystem::getAvailableOperatingSystems());
    }

    /**
     * @dataProvider getNameFromIds
     */
    public function testGetNameFromId($os, $version, $expected)
    {
        $this->assertEquals($expected, OperatingSystem::getNameFromId($os, $version));
    }

    public static function getNameFromIds()
    {
        return [
            ['DEB', '4.5', 'Debian 4.5'],
            ['WRT', '', 'Windows RT'],
            ['WIN', '98', 'Windows 98'],
            ['XXX', '4.5', null],
        ];
    }

    public function testAllOperatingSystemsTested()
    {
        $allBrowsers = OperatingSystem::getAvailableOperatingSystems();
        $osNotTested = \array_diff($allBrowsers, self::$osTested);
        $this->assertEmpty($osNotTested, 'Following oss are not tested: ' . \implode(', ', $osNotTested));
    }
}
