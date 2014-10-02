<?php
/**
 * JsonResponseFormatter class file.
 * @author Christoffer Lindqvist <christoffer.lindqvist@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software 2014-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-rest.components
 */

namespace nordsoftware\yii_rest\components;

/**
 * JsonResponseFormatter formats the given data into a JSON response content.
 */
class JsonResponseFormatter extends ResponseFormatter
{
    /**
     * @var string the response content type.
     */
    public $contentType = 'application/json';

    /**
     * @var string the response charset.
     */
    public $charset = 'UTF-8';

    /**
     * @inheritdoc
     */
    public function format(Response $response)
    {
        $response->headers->set('Content-Type', "{$this->contentType}; charset={$this->charset}");
        $response->content = \CJSON::encode($response->data);
    }
}