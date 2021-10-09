<?php

namespace Fastwf\Tests\Router\Formatter;

use Fastwf\Core\Router\Mount;
use PHPUnit\Framework\TestCase;
use Fastwf\Core\Exceptions\KeyError;
use Fastwf\Core\Router\Formatter\PathFormatter;
use Fastwf\Core\Router\Formatter\RouteGenerator;
use Fastwf\Core\Router\Exception\FormatException;
use Fastwf\Core\Router\Formatter\PartialPathFormatter;
use Fastwf\Tests\Router\Formatter\PartialPathFormatterTest;

class RouteGeneratorTest extends TestCase
{

    private $router;

    protected function setUp(): void
    {
        $this->router = new Mount([
            'path' => '',
            'routes' => [
                new Mount([
                    'path' => 'articles',
                    'name' => 'article-mount',
                    'routes' => [
                        PartialPathFormatterTest::createBaseRouteWith('', 'allArticles'),
                        PartialPathFormatterTest::createBaseRouteWith('{string:slug}', 'getArticle'),
                        PartialPathFormatterTest::createBaseRouteWith('{string:slug}/comments', 'getArticleComments'),
                    ]
                ]),
                new Mount([
                    'path' => 'pages',
                    'name' => 'page-mount',
                    'routes' => [
                        PartialPathFormatterTest::createBaseRouteWith('', 'allPages'),
                        PartialPathFormatterTest::createBaseRouteWith('{string:slug}', 'getPage'),
                        PartialPathFormatterTest::createBaseRouteWith('{string:slug}/comments', 'getPageComments'),
                    ]
                ]),
                new Mount([
                    'path' => 'admin',
                    'name' => 'admin-mount',
                    'routes' => [
                        PartialPathFormatterTest::createBaseRouteWith('', 'adminDashboard'),
                        PartialPathFormatterTest::createBaseRouteWith('users', 'getUsers'),
                        PartialPathFormatterTest::createBaseRouteWith('users/{id}', 'getUserDetails'),
                    ]
                ]),
            ],
            'name' => 'root'
        ]);
    }

    /**
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\Mount
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers Fastwf\Core\Router\Formatter\PartialPathFormatter
     * @covers Fastwf\Core\Router\Formatter\PathFormatter
     * @covers Fastwf\Core\Utils\ArrayUtil
     * @covers Fastwf\Core\Utils\AsyncProperty
     * @covers Fastwf\Core\Utils\StringUtil
     * @covers Fastwf\Core\Router\Formatter\RouteGenerator
     */
    public function testGeneratePath()
    {
        $generator = new RouteGenerator($this->router);

        $this->assertEquals(
            '/admin/users/20',
            $generator->generate('getUserDetails', ['getUserDetails/id' => 20])
        );
    }

    /**
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\Mount
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers Fastwf\Core\Router\Formatter\PartialPathFormatter
     * @covers Fastwf\Core\Router\Formatter\PathFormatter
     * @covers Fastwf\Core\Utils\ArrayUtil
     * @covers Fastwf\Core\Utils\AsyncProperty
     * @covers Fastwf\Core\Utils\StringUtil
     * @covers Fastwf\Core\Router\Formatter\RouteGenerator
     */
    public function testGenerateRoute()
    {
        $generator = new RouteGenerator($this->router);

        $this->assertEquals(
            '/admin?foo=bar#id',
            $generator->generate('adminDashboard', [], ['foo' => 'bar'], 'id')
        );
    }

    /**
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\Mount
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers Fastwf\Core\Router\Formatter\PartialPathFormatter
     * @covers Fastwf\Core\Router\Formatter\PathFormatter
     * @covers Fastwf\Core\Utils\ArrayUtil
     * @covers Fastwf\Core\Utils\AsyncProperty
     * @covers Fastwf\Core\Utils\StringUtil
     * @covers Fastwf\Core\Router\Formatter\RouteGenerator
     */
    public function testGenerateIndex()
    {
        $generator = new RouteGenerator(new Mount([
            'path' => '',
            'routes' => [
                PartialPathFormatterTest::createBaseRouteWith('', 'index'),
            ],
        ]));

        $this->assertEquals(
            '/',
            $generator->generate('index')
        );
    }

    /**
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\Mount
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers Fastwf\Core\Router\Formatter\PartialPathFormatter
     * @covers Fastwf\Core\Router\Formatter\PathFormatter
     * @covers Fastwf\Core\Utils\ArrayUtil
     * @covers Fastwf\Core\Utils\AsyncProperty
     * @covers Fastwf\Core\Utils\StringUtil
     * @covers Fastwf\Core\Router\Formatter\RouteGenerator
     */
    public function testGenerateRouteUsingCache()
    {
        $generator = new RouteGenerator($this->router);

        $startTime = microtime(true);
        $generator->generate('getUserDetails', ['getUserDetails/id' => 20]);
        $noCacheDuration = microtime(true) - $startTime;

        $startTime = microtime(true);
        $generator->generate('getUserDetails', ['getUserDetails/id' => 20]);
        $cacheDuration = microtime(true) - $startTime;

        $this->assertTrue($cacheDuration < $noCacheDuration);
    }

    /**
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\Mount
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers Fastwf\Core\Router\Formatter\PartialPathFormatter
     * @covers Fastwf\Core\Router\Formatter\PathFormatter
     * @covers Fastwf\Core\Utils\ArrayUtil
     * @covers Fastwf\Core\Utils\AsyncProperty
     * @covers Fastwf\Core\Utils\StringUtil
     * @covers Fastwf\Core\Router\Formatter\RouteGenerator
     */
    public function testGenerateNoRouteFormatException()
    {
        $this->expectException(FormatException::class);

        $generator = new RouteGenerator($this->router);
        $generator->generate('test', []);
    }

    /**
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\Mount
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers Fastwf\Core\Router\Formatter\PartialPathFormatter
     * @covers Fastwf\Core\Router\Formatter\PathFormatter
     * @covers Fastwf\Core\Utils\ArrayUtil
     * @covers Fastwf\Core\Utils\AsyncProperty
     * @covers Fastwf\Core\Utils\StringUtil
     * @covers Fastwf\Core\Router\Formatter\RouteGenerator
     */
    public function testGenerateCacheFailed()
    {
        $this->expectException(KeyError::class);

        $generator = new RouteGenerator(new Mount([
            'path' => '',
            'routes' => [
                PartialPathFormatterTest::createBaseRouteWith('', 'dashboard'),
                PartialPathFormatterTest::createBaseRouteWith('dashboard', 'dashboard'),
                PartialPathFormatterTest::createBaseRouteWith('admin', 'adminDashboard'),
            ]
        ]));
        $generator->generate('adminDashboard', []);
    }

    /**
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\Mount
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers Fastwf\Core\Router\Formatter\PartialPathFormatter
     * @covers Fastwf\Core\Router\Formatter\PathFormatter
     * @covers Fastwf\Core\Utils\ArrayUtil
     * @covers Fastwf\Core\Utils\AsyncProperty
     * @covers Fastwf\Core\Utils\StringUtil
     * @covers Fastwf\Core\Router\Formatter\RouteGenerator
     */
    public function testDumpState()
    {
        $generator = new RouteGenerator(new Mount([
            'path' => '',
            'routes' => [
                new Mount([
                    'path' => 'admin',
                    'routes' => [
                        PartialPathFormatterTest::createBaseRouteWith('dashboard', 'adminDashboard'),
                        PartialPathFormatterTest::createBaseRouteWith('other', 'adminOther'),
                    ],
                ]),
                PartialPathFormatterTest::createBaseRouteWith('test', 'second'),
            ],
        ]));
        $generator->generate('adminDashboard', []);

        $baseMount = new PartialPathFormatter(PartialPathFormatterTest::createBaseRouteWith('', null));
        $adminMount = new PartialPathFormatter(PartialPathFormatterTest::createBaseRouteWith('admin', null));

        $this->assertEquals(
            [
                'hasNext' => true,
                'baseRoutes' => [$baseMount, $adminMount],
                'generators' => [
                    'adminDashboard' => new PathFormatter([
                        $baseMount,
                        $adminMount,
                        new PartialPathFormatter(PartialPathFormatterTest::createBaseRouteWith('dashboard', 'adminDashboard')),
                    ]),
                ],
                'stackOffsets' => [1],
                'routeIteratorOffset' => 1,
            ],
            $generator->dumpState()
        );
    }

    /**
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\Mount
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers Fastwf\Core\Router\Formatter\PartialPathFormatter
     * @covers Fastwf\Core\Router\Formatter\PathFormatter
     * @covers Fastwf\Core\Utils\ArrayUtil
     * @covers Fastwf\Core\Utils\AsyncProperty
     * @covers Fastwf\Core\Utils\StringUtil
     * @covers Fastwf\Core\Router\Formatter\RouteGenerator
     */
    public function testConstructorUsingState()
    {
        // Create the state
        $baseMount = new PartialPathFormatter(PartialPathFormatterTest::createBaseRouteWith('', null));
        $adminMount = new PartialPathFormatter(PartialPathFormatterTest::createBaseRouteWith('admin', null));

        $state = [
            'hasNext' => true,
            'baseRoutes' => [$baseMount, $adminMount],
            'generators' => [
                'adminDashboard' => new PathFormatter([
                    $baseMount,
                    $adminMount,
                    new PartialPathFormatter(PartialPathFormatterTest::createBaseRouteWith('dashboard', 'adminDashboard')),
                ]),
            ],
            'stackOffsets' => [1],
            'routeIteratorOffset' => 1,
        ];

        $generator = new RouteGenerator(
            new Mount([
                'path' => '',
                'routes' => [
                    new Mount([
                        'path' => 'admin',
                        'routes' => [
                            PartialPathFormatterTest::createBaseRouteWith('dashboard', 'adminDashboard'),
                            PartialPathFormatterTest::createBaseRouteWith('other', 'adminOther'),
                        ],
                    ]),
                    PartialPathFormatterTest::createBaseRouteWith('test', 'second'),
                ],
            ]),
            $state
        );

        $this->assertEquals(
            $state,
            $generator->dumpState()
        );
    }

}
