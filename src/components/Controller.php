<?php
/**
 * Controller class file.
 * @author Christoffer Lindqvist <christoffer.lindqvist@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software 2014-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-rest.components
 */

namespace nordsoftware\yii_rest\components;

/**
 * Controller base class for all REST API controller classes.
 */
abstract class Controller extends \CController
{
    /**
     * @inheritdoc
     */
    public function filters()
    {
        return array(
            array(
                'nordsoftware\yii_rest\filters\ResponseFormatNegotiator',
                'formats' => array(
                    'application/json' => Response::FORMAT_JSON,
                ),
            ),
            array(
                'nordsoftware\yii_rest\filters\ContentTypeValidator',
                'types' => array(
                    'application/json',
                ),
            ),
        );
    }

    /**
     * Sends the API response to the client.
     * @param mixed $data the data to send as the response body.
     * @param int $statusCode the status code of the response.
     * @throws \CHttpException if response component cannot be found.
     */
    public function sendResponse($data, $statusCode = 200)
    {
        \Yii::app()->getComponent('response')->send($data, $statusCode);
    }
}