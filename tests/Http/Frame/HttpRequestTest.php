<?php

namespace Fastwf\Tests\Http\Frame;

use PHPUnit\Framework\TestCase;

use Fastwf\Core\Http\Frame\HttpRequest;
use Fastwf\Core\Exceptions\AttributeError;


class HttpRequestTest extends TestCase {

    /**
     * @covers Fastwf\Core\Http\Frame\Headers
     * @covers Fastwf\Core\Http\Frame\HttpRequest
     */
    public function testConstruct() {
        $req = new HttpRequest("/hello-world", "GET");

        $this->assertEquals("/hello-world", $req->path);
        $this->assertEquals("GET", $req->method);
        $this->assertNotNull($req->headers);
        $this->assertNotNull($req->cookie);
    }

    /**
     * @covers Fastwf\Core\Http\Frame\Headers
     * @covers Fastwf\Core\Http\Frame\HttpRequest
     */
    public function testGetFiles() {
        $req = new HttpRequest("/", "GET");

        $this->assertNotNull($req->files);
    }

    /**
     * @covers Fastwf\Core\Http\Frame\Headers
     * @covers Fastwf\Core\Http\Frame\HttpRequest
     */
    public function testGetStream() {
        $req = new HttpRequest("/", "GET");

        $stream  =$req->stream;
        $this->assertNotNull($stream);
        fclose($stream);
    }

    /**
     * @covers Fastwf\Core\Http\Frame\Headers
     * @covers Fastwf\Core\Http\Frame\HttpRequest
     */
    public function testGetBody() {
        $path = __DIR__ . "/../../../resources/php-input-body.json";
        $req = new HttpRequest("/", "GET", $path);

        $this->assertEquals(file_get_contents($path), $req->body);
    }

    /**
     * @covers Fastwf\Core\Http\Frame\Headers
     * @covers Fastwf\Core\Http\Frame\HttpRequest
     */
    public function testGetJson() {
        $path = __DIR__ . "/../../../resources/php-input-body.json";
        $req = new HttpRequest("/", "GET", $path);

        $this->assertEquals(
            ["username" => "user", "password" => "pass"],
            $req->json
        );
    }

    /**
     * @covers Fastwf\Core\Http\Frame\Headers
     * @covers Fastwf\Core\Http\Frame\HttpRequest
     */
    public function testQueryAndForm() {
        $_GET['date'] = "2021-07-25";

        $_POST['token'] = "validToken";

        $req = new HttpRequest("/", "POST");

        $this->assertEquals($_GET['date'], $req->query->get('date'));
        $this->assertEquals($_POST['token'], $req->form->get('token'));
    }

    /**
     * @covers Fastwf\Core\Http\Frame\Headers
     * @covers Fastwf\Core\Http\Frame\HttpRequest
     */
    public function testParametersAndName() {        
        $req = new HttpRequest("/", "GET");
        
        $req->name = 'get_user';
        $req->parameters = ['id' => 10];

        $this->assertEquals(['id' => 10], $req->parameters);
        $this->assertEquals('get_user', $req->name);
    }

    /**
     * @covers Fastwf\Core\Http\Frame\Headers
     * @covers Fastwf\Core\Http\Frame\HttpRequest
     * @covers Fastwf\Core\Exceptions\AttributeError
     */
    public function testNoAttribute() {
        $this->expectException(AttributeError::class);

        $req = new HttpRequest("/", "GET");
        $req->notFound;
    }

    /**
     * @covers Fastwf\Core\Http\Frame\Headers
     * @covers Fastwf\Core\Http\Frame\HttpRequest
     */
    public function testIsset() {
        $req = new HttpRequest("/", "GET");

        $this->assertTrue(isset($req->form));
        $this->assertFalse(isset($req->notFound));
    }

}
