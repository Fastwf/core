<?php

namespace Fastwf\Tests;

use Fastwf\Core\Configuration;

use PHPUnit\Framework\TestCase;
use Fastwf\Core\Exceptions\IOException;
use Fastwf\Core\Exceptions\ValueError;

class ConfigurationTest extends TestCase {

    /**
     * @covers Fastwf\Core\Configuration
     * @covers Fastwf\Core\Utils\ArrayProxy
     */
    public function testIOException() {
        $this->expectException(IOException::class);

        new Configuration(__DIR__ . '/../resources/not-found.ini');
    }

    /**
     * @covers Fastwf\Core\Configuration
     * @covers Fastwf\Core\Utils\ArrayProxy
     */
    public function testAloneConfigurationFile() {
        $configuration = new Configuration(__DIR__ . '/../resources/config-alone.ini');

        $this->assertEquals(7.4, $configuration->get('app.version'));
    }

    /**
     * @covers Fastwf\Core\Configuration
     * @covers Fastwf\Core\Utils\ArrayProxy
     */
    public function testOverrideConfigurationFile() {
        $_ENV['APP_DEFAULT_LANG'] = 'fr';
        $_ENV['APP_PORT'] = '9000';
        $_ENV['APP_DEBUG'] = 'no';
        $_ENV['CACHE_ENABLED'] = 'yes';
        $_ENV['SECURITY_AUTHENTICATION_WAIT_RETRY'] = '30.0';
        $_ENV['SECURITY_HOST_ALLOWED'] = ['app1.local'];

        $configuration = new Configuration(__DIR__ . '/../resources/config.ini');

        $this->assertEquals(7.4, $configuration->get('app.version'));
        $this->assertEquals('application.local', $configuration->get('app.dns'));
        $this->assertEquals('fr', $configuration->get('app.default_lang'));
        $this->assertEquals(9000, $configuration->get('app.port'));
        $this->assertFalse($configuration->get('app.debug'));
        $this->assertTrue($configuration->get('cache.enabled'));
        $this->assertEquals(30.0, $configuration->get('security.authentication_wait_retry'));
    }

    /**
     * @covers Fastwf\Core\Configuration
     */
    public function testParseBoolean() {
        $this->expectException(ValueError::class);

        Configuration::parseBoolean('failed');
    }

}
