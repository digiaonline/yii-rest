<?php
/**
 * WebApplication class file.
 * @author Christoffer Lindqvist <christoffer.lindqvist@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software 2014-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-rest.components
 */

namespace nordsoftware\yii_rest\components;

/**
 * WebApplication class for the REST application.
 * Overrides error and exception handling methods so that a proper REST response is always returned by the API.
 */
class WebApplication extends \CWebApplication
{
    /**
     * @var string the response data serializer class to use.
     */
    public $serializer = 'nordsoftware\yii_rest\components\Serializer';

    /**
     * @inheritdoc
     */
    public function displayError($code, $message, $file, $line)
    {
        $this->sendErrorResponse(new ErrorResponseData($code, $message, $file, $line));
    }

    /**
     * @inheritdoc
     */
    public function displayException($exception)
    {
        $this->sendErrorResponse(new ExceptionResponseData($exception));
    }

    /**
     * Sends an error response to the client.
     * @param ExceptionResponseData|ErrorResponseData $data the response data.
     */
    public function sendErrorResponse($data)
    {
        $response = new Response();
        $response->setStatusCode($data->status);
        $response->data = \Yii::createComponent(array('class' => $this->serializer))->serialize($data);
        $response->send();
    }
}