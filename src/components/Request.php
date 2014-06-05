<?php
/**
 * Request class file.
 * @author Christoffer Lindqvist <christoffer.lindqvist@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software 2014-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-rest.components
 */

namespace nordsoftware\yii_rest\components;

/**
 * Request class that extends \CHttpRequest with functionality to parse and return body params.
 */
class Request extends \CHttpRequest
{
    /**
     * @var array the parsed body params.
     */
    private $_bodyParams;

    /**
     * Returns the named request body parameter value.
     * @param string $name the parameter name.
     * @param mixed $defaultValue the default parameter value if the parameter does not exist.
     * @return mixed the parameter value.
     */
    public function getBodyParam($name, $defaultValue = null)
    {
        $params = $this->getBodyParams();
        return isset($params[$name]) ? $params[$name] : $defaultValue;
    }

    /**
     * Returns the request parameters given in the request body.
     * @return array the request parameters given in the request body.
     */
    public function getBodyParams()
    {
        if ($this->_bodyParams === null) {
            $contentType = $this->getContentType();
            switch ($contentType) {
                case 'application/json':
                    $this->_bodyParams = \CJSON::decode($this->getRawBody());
                    break;

                default:
                    $this->_bodyParams = array();
                    mb_parse_str($this->getRawBody(), $this->_bodyParams);
                    break;
            }
        }
        return $this->_bodyParams;
    }

    /**
     * Returns request content-type.
     * The Content-Type header field indicates the MIME type of the data contained in the raw body or,
     * in the case of the HEAD method, the media type that would have been sent had the request been a GET.
     * @return string request content-type. Null is returned if this information is not available.
     * @link http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.17
     */
    public function getContentType()
    {
        $contentType = null;
        if (isset($_SERVER['CONTENT_TYPE'])) {
            $contentType = $_SERVER['CONTENT_TYPE'];
        } elseif (isset($_SERVER['HTTP_CONTENT_TYPE'])) {
            // fix bug https://bugs.php.net/bug.php?id=66606
            $contentType = $_SERVER['HTTP_CONTENT_TYPE'];
        }
        if ($contentType !== null && ($pos = strpos($contentType, ';')) !== false) {
            // e.g. application/json; charset=UTF-8
            $contentType = substr($contentType, 0, $pos);
        }
        return $contentType;
    }
} 