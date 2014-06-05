<?php
/**
 * ErrorHandler class file.
 * @author Christoffer Lindqvist <christoffer.lindqvist@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software 2014-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-rest.components
 */

namespace nordsoftware\yii_rest\components;

/**
 * ErrorHandler class for the REST application.
 * Overrides error and exception handling methods so that a proper REST response is always returned by the API.
 * The error handler can be used without the WebApplication included the the yii-rest extension.
 */
class ErrorHandler extends \CErrorHandler
{
    /**
     * @var string the response data serializer class to use.
     */
    public $serializer = 'nordsoftware\yii_rest\components\Serializer';

    /**
     * @inheritdoc
     */
    protected function handleError($event)
    {
        $this->sendResponse(new ErrorResponseData($event->code, $event->message, $event->file, $event->line));
    }

    /**
     * @inheritdoc
     */
    protected function handleException($exception)
    {
        $this->sendResponse(new ExceptionResponseData($exception));
    }

    /**
     * Sends an error response to the client.
     * @param ExceptionResponseData|ErrorResponseData $data the response data.
     */
    protected function sendResponse($data)
    {
        $response = new Response();
        $response->setStatusCode($data->status);
        $response->data = \Yii::createComponent(array(
                'class' => $this->serializer,
                'response' => $response,
            ))->serialize($data);
        $response->send();
    }
}