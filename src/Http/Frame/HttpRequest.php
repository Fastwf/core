<?php

namespace Fastwf\Core\Http\Frame;

use Fastwf\Core\Utils\ArrayProxy;
use Fastwf\Core\Utils\Files\UploadedFile;

/**
 * The object representation of the http request.
 * 
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
    
    protected $_headers;
    protected $_cookie;

    protected $get;
    protected $post;

    public function __construct() {
        $this->_headers = new Headers(\apache_request_headers());

        $this->get = new ArrayProxy($_GET, true);
        $this->post = new ArrayProxy($_POST, true);
        $this->_cookie = new ArrayProxy($_COOKIE, true);
    }

    public function __get($name) {
        switch ($name) {
            case 'query':
                return $this->get;
            case 'form':
                return $this->post;
            case 'stream':
                return $this->getStream();
            case 'body':
                return $this->getBody();
            case 'json':
                return $this->getJson();
            case 'files':
                return $this->getFiles();
            case 'headers':
            case 'cookie':
                return $this->{"_$name"};
            default:
                throw new AttributeError();
        }
    }

    /**
     * Access to the body of the request thanks to resource.
     *
     * @return resource The resource bound to the request body stream.
     */
    protected function getStream() {
        return \fopen('php://input', 'r');
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
