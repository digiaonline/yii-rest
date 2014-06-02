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
class JsonResponseFormatter extends \CComponent
{
    /**
     * Formats the specified response.
     * @param Response $response the response to be formatted.
     */
    public function format(Response $response)
    {
        $response->getHeaders()->set('Content-Type', 'application/json; charset=UTF-8');
        $response->content = \CJSON::encode($response->data);
    }
}