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
 * It is not required, but still recommended, to use this WebApplication for the REST API.
 *
 * @property Response $response
 */
class WebApplication extends \CWebApplication
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
    public function displayError($code, $message, $file, $line)
    {
        $object = $this->getResponseDataObject('error');
        $object->init($code, $message, $file, $line);
        $this->sendResponse($object, $object->status);
    }

    /**
     * @inheritdoc
     */
    public function displayException($exception)
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
    public function getResponseDataObject($name)
    {
        if (!isset($this->responseDataObjects[$name])) {
            throw new \CException(sprintf('Failed to create response data object %s.', $name));
        }
        return \Yii::createComponent($this->responseDataObjects[$name]);
    }

    /**
     * Getter for the REST response object.
     * @return Response
     */
    public function getResponse()
    {
        return $this->getComponent('response');
    }

    /**
     * Sends the API response to the client.
     * @param mixed $data the data to send as the response body.
     * @param int $statusCode the status code of the response.
     * @throws \CHttpException if response component cannot be found.
     */
    public function sendResponse($data, $statusCode = 200)
    {
        $this->getResponse()->send($data, $statusCode);
    }
}