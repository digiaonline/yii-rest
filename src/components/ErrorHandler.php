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
     * @var array error/exception response object component configs.
     */
    public $responseDataObjects = array(
        'error' => array(
            'class' => 'nordsoftware\yii_rest\components\ErrorResponseData'
        ),
        'exception' => array(
            'class' => 'nordsoftware\yii_rest\components\ExceptionResponseData'
        ),
    );

    /**
     * @inheritdoc
     */
    protected function handleError($event)
    {
        $object = $this->getResponseDataObject('error');
        $object->init($event->code, $event->message, $event->file, $event->line);
        $this->sendResponse($object, $object->status);
    }

    /**
     * @inheritdoc
     */
    protected function handleException($exception)
    {
        $object = $this->getResponseDataObject('exception');
        $object->init($exception);
        $this->sendResponse($object, $object->status);
    }

    /**
     * Creates the response data object specified by given name.
     * @param string $name the response data object name.
     * @return ErrorResponseData|ExceptionResponseData the response data object.
     * @throws \CException if the response data object cannot be created.
     */
    protected function getResponseDataObject($name)
    {
        if (!isset($this->responseDataObjects[$name])) {
            throw new \CException(sprintf('Failed to create response data object %s.', $name));
        }
        return \Yii::createComponent($this->responseDataObjects[$name]);
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