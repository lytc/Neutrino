<?php

class Neutrino_Http_Response
{
    const CONTENT_TYPE_TEXT_HTML            = 'text/html';
    const CONTENT_TYPE_TEXT_XML             = 'text/xml';
    const CONTENT_TYPE_TEXT_JAVASCRIPT      = 'text/javascript';
    const CONTENT_TYPE_APPLICATION_JSON     = 'application/json';

    /**
     * @var Neutrino
     */
    protected $_app;

    /**
     * @var array
     */
    protected $_headers = [];

    /**
     * @var int
     */
    protected $_code = 200;

    /**
     * @var string
     */
    protected $_message = 'OK';

    /**
     * @var string
     */
    protected $_body = '';

    /**
     * @param Neutrino $app
     */
    public function __construct(Neutrino $app)
    {
        $this->_app = $app;
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function setHeader($name, $value = null)
    {
        $this->_headers[$name] = $value;
    }

    /**
     * @param array $headers
     * @return Neutrino_Http_Response
     */
    public function setHeaders(array $headers)
    {
        foreach ($headers as $name => $value) {
            $this->setHeader($name, $value);
        }
        return $this;
    }

    /**
     * @param int $code
     * @return Neutrino_Http_Response
     */
    public function setCode($code)
    {
        $this->_code = (int) $code;
        return $this;
    }

    /**
     * @param $message
     * @return Neutrino_Http_Response
     */
    public function setMessage($message)
    {
        $this->_message = $message;
        return $this;
    }

    /**
     * @param string $contentType
     * @return Neutrino_Http_Response
     */
    public function setContentType($contentType)
    {
        return $this->setHeader('Content-Type', $contentType);
    }

    /**
     * @param $content
     */
    public function setBody($content)
    {
        $this->_body = $content;
    }

    /**
     * @return Neutrino_Http_Response
     */
    public function send()
    {
        if (false === headers_sent()) {
            $codeSent = false;

            foreach ($this->_headers as $name => $value) {
                $header = null === $value? $name : "$name: $value";
                if (!$codeSent) {
                    header($header, true, $this->_code);
                    $codeSent = true;
                } else {
                    header($header);
                }
            }

            if (!$codeSent) {
                header("HTTP/1.1 {$this->_code} {$this->_message}");
            }

        }

        echo $this->_body;
        return $this;
    }
}