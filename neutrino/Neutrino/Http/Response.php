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
    public function __construct(Neutrino_App $app)
    {
        $this->_app = $app;
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function setHeader($name, $value = null, $replace = true, $code = null)
    {
        $this->_headers[] = ['name' => $name, 'value' => $value, 'replace' => $replace, 'code' => $code];
    }

    /**
     * @param array $headers
     * @return Neutrino_Http_Response
     */
    public function setHeaders(array $headers)
    {
        foreach ($headers as $name => $value) {
            if (is_array($value)) {
                $header = ['name' => $name];
                $header['value'] = $value[0];
                $header['replace'] = isset($value[1])? $value[1] : true;
                $header['code'] = is_numeric($value[2])? $value[2] : null;
            } else {
                $header = ['name' => $name, 'value' => $value];
            }
            call_user_func_array(array($this, 'setHeader'), $header);
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->_headers;
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
     * @return int
     */
    public function getCode()
    {
        return $this->_code;
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
     * @return string
     */
    public function getMessage()
    {
        return $this->_message;
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
     * @param $url
     * @param int $code
     * @return Neutrino_Http_Response
     * @throws Neutrino_Http_Response_Exception
     */
    public function redirect($url, $code = 302)
    {
        if ($code < 300 || $code > 307) {
            throw new Neutrino_Http_Response_Exception('Redirect code must be >= 300 and <= 307');
        }

        return $this->setHeader('Location', $url, true, $code);
    }

    /**
     * @param $content
     */
    public function setBody($content)
    {
        $this->_body = $content;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->_body;
    }

    /**
     * @return Neutrino_Http_Response
     */
    public function send()
    {
        if (false === headers_sent()) {
            $codeSent = false;

            foreach ($this->_headers as $header) {
                if ($header['value']) {
                    $header['name'] .= ": {$header['value']}";
                }

                if ($header['code']) {
                    header($header['name'], $header['replace'], $header['code']);
                    $codeSent = true;
                } else {
                    header($header['name'], $header['replace']);
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