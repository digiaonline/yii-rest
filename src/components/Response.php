<?php
/**
 * Response class file.
 * @author Christoffer Lindqvist <christoffer.lindqvist@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software 2014-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-rest.components
 */

namespace nordsoftware\yii_rest\components;

/**
 * Response object that represents an HTTP response.
 * It holds the headers and content that is to be sent to the client and also controls the HTTP status code.
 */
class Response extends \CComponent
{
    const FORMAT_RAW = 'raw';
    const FORMAT_JSON = 'json';

    /**
     * @var string the response format. This determines how to convert the response before sending it to the client.
     */
    public $format = self::FORMAT_JSON;

    /**
     * @var mixed the original response data.
     */
    public $data;

    /**
     * @var string the response content.
     */
    public $content;

    /**
     * @var string the charset of the text response.
     */
    public $charset;

    /**
     * @var string the version of the HTTP protocol to use.
     */
    public $version;

    /**
     * @var string the HTTP status description that comes together with the status code.
     */
    protected $statusText = 'OK';

    /**
     * @var int the HTTP status code to send with the response.
     */
    protected $statusCode = 200;

    /**
     * @var HeaderCollection
     */
    private $_headers;

    /**
     * var array the formatters for converting data into the response content of the specified $this->format.
     */
    public static $formatters = array(
        self::FORMAT_JSON => 'nordsoftware\yii_rest\components\JsonResponseFormatter',
    );

    /**
     * @var array list of HTTP status codes and the corresponding texts.
     */
    public static $httpStatuses = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        118 => 'Connection timed out',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        208 => 'Already Reported',
        210 => 'Content Different',
        226 => 'IM Used',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Reserved',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        310 => 'Too many Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested range unsatisfiable',
        417 => 'Expectation failed',
        418 => 'I\'m a teapot',
        422 => 'Unprocessable entity',
        423 => 'Locked',
        424 => 'Method failure',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        449 => 'Retry With',
        450 => 'Blocked by Windows Parental Controls',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway ou Proxy Error',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported',
        507 => 'Insufficient storage',
        508 => 'Loop Detected',
        509 => 'Bandwidth Limit Exceeded',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
    );

    /**
     * @return integer the HTTP status code to send with the response.
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Sets the response status code.
     * This method will set the corresponding status text if `$text` is null.
     * @param integer $value the status code
     * @param string $text the status text. If not set, it will be set automatically based on the status code.
     * @throws \CException if the status code is invalid.
     */
    public function setStatusCode($value, $text = null)
    {
        if ($value === null) {
            $value = 200;
        }
        $this->statusCode = (int)$value;
        if ($this->getIsInvalid()) {
            throw new \CException(sprintf('The HTTP status code is invalid: %s', $value));
        }
        if ($text === null) {
            $this->statusText = isset(static::$httpStatuses[$this->statusCode])
                ? static::$httpStatuses[$this->statusCode]
                : '';
        } else {
            $this->statusText = $text;
        }
    }

    /**
     * Returns the header collection.
     * The header collection contains the currently registered HTTP headers.
     * @return HeaderCollection the header collection
     */
    public function getHeaders()
    {
        if ($this->_headers === null) {
            $this->_headers = new HeaderCollection();
        }
        return $this->_headers;
    }

    /**
     * Sends the response to the client.
     */
    public function send()
    {
        $this->prepare();
        $this->sendHeaders();
        $this->sendContent();
    }

    /**
     * Prepares the response before it is sent.
     * @throws \CException
     */
    protected function prepare()
    {
        if ($this->version === null) {
            if (isset($_SERVER['SERVER_PROTOCOL']) && $_SERVER['SERVER_PROTOCOL'] === 'HTTP/1.0') {
                $this->version = '1.0';
            } else {
                $this->version = '1.1';
            }
        }

        if ($this->charset === null) {
            $this->charset = \Yii::app()->charset;
        }

        if (isset(static::$formatters[$this->format])) {
            $formatter = \Yii::createComponent(array('class' => static::$formatters[$this->format]));
            $formatter->format($this);
        } elseif ($this->format === self::FORMAT_RAW) {
            $this->content = $this->data;
        } else {
            throw new \CException(sprintf('Invalid response format "%s".', $this->format));
        }

        if (is_array($this->content)) {
            throw new \CException('Response content must not be an array.');
        } elseif (is_object($this->content)) {
            if (method_exists($this->content, '__toString')) {
                $this->content = $this->content->__toString();
            } else {
                throw new \CException('Response content must be a string or an object implementing __toString().');
            }
        }
    }

    /**
     * Sends the response headers to the client.
     */
    protected function sendHeaders()
    {
        if (headers_sent()) {
            return;
        }
        header("HTTP/{$this->version} {$this->statusCode} {$this->statusText}");
        if ($this->_headers) {
            foreach ($this->_headers as $name => $values) {
                $name = str_replace(' ', '-', ucwords(str_replace('-', ' ', $name)));
                foreach ($values as $value) {
                    header("$name: $value", false);
                }
            }
        }
    }

    /**
     * Sends the response content to the client.
     */
    protected function sendContent()
    {
        echo $this->content;
    }

    /**
     * @return boolean whether this response has a valid status code.
     */
    public function getIsInvalid()
    {
        return $this->getStatusCode() < 100 || $this->getStatusCode() >= 600;
    }
}