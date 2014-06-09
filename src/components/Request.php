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
     * @var array the parsed content types for the HTTP Accept header.
     */
    private $_contentTypes;

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

    /**
     * Returns the content types acceptable by the client.
     * These are parsed from the HTTP Accept header.
     * @return array the content types.
     */
    public function getAcceptableContentTypes()
    {
        if ($this->_contentTypes === null) {
            $this->_contentTypes = isset($_SERVER['HTTP_ACCEPT'])
                ? $this->parseHttpAcceptHeader($_SERVER['HTTP_ACCEPT'])
                : array();
        }
        return $this->_contentTypes;
    }

    /**
     * Parses the HTTP Accept header.
     *
     * This method will return the acceptable values with their quality scores and the corresponding parameters
     * as specified in the given `Accept` header. The array keys of the return value are the acceptable values,
     * while the array values consisting of the corresponding quality scores and parameters. The acceptable
     * values with the highest quality scores will be returned first.
     *
     * For example:
     *
     * $header = 'text/plain; q=0.5, application/json; version=1.0, application/xml; version=2.0;';
     * array (
     *     'application/json' => ['q' => 1, 'version' => '1.0'],
     *     'application/xml' => ['q' => 1, 'version' => '2.0'],
     *     'text/plain' => ['q' => 0.5],
     * )
     *
     * @param string $header the header to be parsed.
     * @return array the acceptable values ordered by their quality score.
     */
    public function parseHttpAcceptHeader($header)
    {
        $accepts = array();
        foreach (explode(',', $header) as $i => $part) {
            $params = preg_split('/\s*;\s*/', trim($part), -1, PREG_SPLIT_NO_EMPTY);
            if (empty($params)) {
                continue;
            }
            $values = [
                'q' => array($i, array_shift($params), 1),
            ];
            foreach ($params as $param) {
                if (strpos($param, '=') !== false) {
                    list ($key, $value) = explode('=', $param, 2);
                    if ($key === 'q') {
                        $values['q'][2] = (double) $value;
                    } else {
                        $values[$key] = $value;
                    }
                } else {
                    $values[] = $param;
                }
            }
            $accepts[] = $values;
        }

        usort($accepts, function ($a, $b) {
                $a = $a['q']; // index, name, q
                $b = $b['q'];
                if ($a[2] > $b[2]) {
                    return -1;
                } elseif ($a[2] < $b[2]) {
                    return 1;
                } elseif ($a[1] === $b[1]) {
                    return $a[0] > $b[0] ? 1 : -1;
                } elseif ($a[1] === '*/*') {
                    return 1;
                } elseif ($b[1] === '*/*') {
                    return -1;
                } else {
                    $wa = $a[1][strlen($a[1]) - 1] === '*';
                    $wb = $b[1][strlen($b[1]) - 1] === '*';
                    if ($wa xor $wb) {
                        return $wa ? 1 : -1;
                    } else {
                        return $a[0] > $b[0] ? 1 : -1;
                    }
                }
            });

        $result = array();
        foreach ($accepts as $accept) {
            $name = $accept['q'][1];
            $accept['q'] = $accept['q'][2];
            $result[$name] = $accept;
        }

        return $result;
    }
} 