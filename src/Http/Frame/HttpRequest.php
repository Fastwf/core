<?php

namespace Fastwf\Core\Http\Frame;

use Fastwf\Api\Utils\ArrayProxy;
use Fastwf\Core\Http\Frame\Headers;
use Fastwf\Core\Exceptions\AttributeError;
use Fastwf\Api\Utils\Files\UploadedFileUtil;

/**
 * The object representation of the http request.
 * 
 * @property-read string $path The path corresponding to the REQUEST_URI.
 * @property-read string $method The request method corresponding to the REQUEST_METHOD.
 * @property-read ArrayProxy $query the array of query parameters ($_GET)
 * @property-read ArrayProxy $form the array that contains the parsed form data ($_POST).
 * @property-read string $body the sequence read from body request.
 * @property-read array $json the json as array association extracted from body request.
 * @property-read resource $stream the stream of the body request.
 * @property-read Headers $headers the request headers.
 * @property-read ArrayProxy $cookie the request cookies.
 */
class HttpRequest {

    const BUFFER = 2**16;

    protected const QUERY = 'query';
    protected const FORM = 'form';
    protected const STREAM = 'stream';
    protected const BODY = 'body';
    protected const JSON = 'json';
    protected const FILES = 'files';
    protected const PATH = 'path';
    protected const METHOD = 'method';
    protected const HEADERS = 'headers';
    protected const COOKIE = 'cookie';

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
            case self::QUERY:
                $value = $this->get;
                break;
            case self::FORM:
                $value = $this->post;
                break;
            case self::STREAM:
                $value = $this->getStream();
                break;
            case self::BODY:
                $value = $this->getBody();
                break;
            case self::JSON:
                $value = $this->getJson();
                break;
            case self::FILES:
                $value = $this->getFiles();
                break;
            case self::PATH:
            case self::METHOD:
            case self::HEADERS:
            case self::COOKIE:
                $value = $this->{"_$name"};
                break;
            default:
                throw new AttributeError($name);
        }

        return $value;
    }

    public function __isset($name)
    {
        return \in_array(
            $name,
            [
                self::QUERY,
                self::FORM,
                self::BODY,
                self::STREAM,
                self::JSON,
                self::FILES,
                self::PATH,
                self::METHOD,
                self::HEADERS,
                self::COOKIE
            ],
        );
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
     * @return ArrayProxy the array proxy of uploaded files
     */
    protected function getFiles() {
        if ($this->_files === null) {
            $this->_files = new ArrayProxy(UploadedFileUtil::fromSuperGlobal($_FILES));
        }

        return $this->_files;
    }

}
