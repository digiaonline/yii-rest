<?php
/**
 * ResponseFormatter class file.
 * @author Christoffer Lindqvist <christoffer.lindqvist@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software 2014-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-rest.components
 */

namespace nordsoftware\yii_rest\components;

/**
 * ResponseFormatter base class for all response formatters.
 */
abstract class ResponseFormatter extends \CComponent
{
    /**
     * Formats the specified response.
     * @param Response $response the response to be formatted.
     */
    abstract public function format(Response $response);
}