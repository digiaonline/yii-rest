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
 * This error handler can be used without the WebApplication included in the extension.
 */
class ErrorHandler extends \CErrorHandler
{
    /**
     * @inheritdoc
     */
    protected function handleError($event)
    {
        $errorResponse = new ErrorResponseData($event->code, $event->message, $event->file, $event->line);
        $this->sendResponse($errorResponse, $errorResponse->status);
    }

    /**
     * @inheritdoc
     */
    protected function handleException($exception)
    {
        $exceptionResponse = new ExceptionResponseData($exception);
        $this->sendResponse($exceptionResponse, $exceptionResponse->status);
    }

    /**
     * Sends the API response to the client.
     * @param mixed $data the data to send as the response body.
     * @param int $statusCode the status code of the response.
     * @throws \CHttpException if response component cannot be found.
     */
    protected function sendResponse($data, $statusCode = 200)
    {
        \Yii::app()->getComponent('response')->send($data, $statusCode);
    }
}