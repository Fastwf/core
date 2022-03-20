<?php

namespace Fastwf\Core\Router\Parser;

/**
 * The route parser is a tool that help to parse the REQUEST_URI.
 *
 * The parser works like an iterator, when each methods are called directly use:
 *  rewind()
 *  valid()
 *  current() and/or key()
 *  next()
 *  valid()
 *  ...
 */
class RouteParser implements \Iterator {

    protected $path;

    protected $index = 0;
    protected $length;

    protected $isValid = true;
    protected $segment;
    protected $segmentIndex = -1;

    public function __construct($path) {
        $this->path = $path;

        $this->length = strlen($path);
    }

    /// Implement methods

    public function current() {
        return $this->segment;
    }

    public function key() {
        return $this->segmentIndex;
    }

    public function next(): void {
        // Search for the next "/" char or the end of the sequence
        if ($this->isValid = $this->index < $this->length) {
            $this->nextSegment();
        }
    }

    public function rewind(): void {
        $this->index = 0;

        $this->isValid = $this->index < $this->length;

        $this->segment = '';
        $this->segmentIndex = -1;

        $this->nextSegment();
    }

    public function valid(): bool {
        return $this->isValid;
    }

    /// Private methods

    /**
     * Try to read the next segment in the path according to the current segment index
     *
     * @return string the next segment in the path
     */
    protected function nextSegment() {
        $this->segment = '';

        // while the end is not reached and the current char is not a '/'
        while ($this->index < $this->length && ($char = $this->path[$this->index]) !== "/") {
            // Append to the segment
            $this->segment .= $char;

            $this->index++;
        }
        // Skeep the "/"
        $this->index++;

        $this->segmentIndex++;
    }

    /// Public methods

    /**
     * Allows to obtain the next part of the path according to the current segment index.
     *
     * @return string the path under the current() path excluding the leading '/'
     */
    public function getNextPath() {
        $nextLength = $this->length - $this->index;

        return $nextLength >= 0 ? \substr($this->path, $this->index, $nextLength) : '';
    }

}
