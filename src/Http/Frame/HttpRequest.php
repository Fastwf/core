<?php

namespace Fastwf\Core\Http\Frame;

use Fastwf\Core\Utils\ArrayProxy;
use Fastwf\Core\Http\Frame\Headers;
use Fastwf\Core\Utils\Files\UploadedFile;
use Fastwf\Core\Exceptions\AttributeError;

/**
 * The object representation of the http request.
 * 
 * @property-read string $path The path corresponding to the REQUEST_URI.
 * @property-read string $method The request method corresponding to the REQUEST_METHOD.
 * @property-read Fastwf\Core\Utils\ArrayProxy $query the array of query parameters ($_GET)
 * @property-read Fastwf\Core\Utils\ArrayProxy $form the array that contains the parsed form data ($_POST).
 * @property-read string $body the sequence read from body request.
 * @property-read array $json the json as array association extracted from body request.
 * @property-read resource $stream the stream of the body request.
 * @property-read Fastwf\Core\Http\Frame\Headers $headers the request headers.
 * @property-read Fastwf\Core\Utils\ArrayProxy $cookie the request cookies.
 */
class HttpRequest {

    const BUFFER = 2**16;

    private $_files = null;
    
    protected $_path;
    protected $_method;

    protected $_headers;
    protected $_cookie;

    protected $get;
    protected $post;

    private $input;

    /**
     * Parameters extracted from the route and mount points
     *
     * @var array
     */
    public $parameters;
    /**
     * The name of the route that match the client request
     *
     * @var string|null
     */
    public $name;

    public function __construct($path, $method, $bodyFilename = "php://input") {
        $this->_path = $path;
        $this->_method = $method;
        $this->input = $bodyFilename;

        $this->_headers = new Headers(\apache_request_headers());

        $this->get = new ArrayProxy($_GET, true);
        $this->post = new ArrayProxy($_POST, true);
        $this->_cookie = new ArrayProxy($_COOKIE, true);
    }

    public function __get($name) {
        switch ($name) {
            case 'query':
                $value = $this->get;
                break;
            case 'form':
                $value = $this->post;
                break;
            case 'stream':
                $value = $this->getStream();
                break;
            case 'body':
                $value = $this->getBody();
                break;
            case 'json':
                $value = $this->getJson();
                break;
            case 'files':
                $value = $this->getFiles();
                break;
            case 'path':
            case 'method':
            case 'headers':
            case 'cookie':
                $value = $this->{"_$name"};
                break;
            default:
                throw new AttributeError($name);
        }

        return $value;
    }

    /**
     * Access to the body of the request thanks to resource.
     *
     * @return resource The resource bound to the request body stream.
     */
    protected function getStream() {
        return \fopen($this->input, 'r');
    }

    /**
     * Read the content from the request body without parsing logic.
     *
     * @return string The sequence read from request body.
     */
    protected function getBody() {
        // Read the stream and return the content as byte sequence
        $resource = $this->getStream();

        $content = "";
        while (!\feof($resource)) {
            $content .= \fread($resource, self::BUFFER);
        }
        \fclose($resource);

        return $content;
    }

    /**
     * Try to read the request body and parse it as json object.
     *
     * @return array the json as array association.
     */
    protected function getJson() {
        // Try to read the json from the stream and parse it
        return \json_decode($this->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Allows to obtain an array proxy of $_FILES array with simplified access system.
     *
     * @return Fastwf\Core\Utils\ArrayProxy the array proxy of uploaded files
     */
    protected function getFiles() {
        if ($this->_files === null) {
            $this->_files = new ArrayProxy(UploadedFile::fromSuperGlobal($_FILES));
        }

        return $this->_files;
    }

}
