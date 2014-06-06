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
     * @inheritdoc
     */
    public function displayError($code, $message, $file, $line)
    {
        $errorResponse = new ErrorResponseData($code, $message, $file, $line);
        $this->sendResponse($errorResponse, $errorResponse->status);
    }

    /**
     * @inheritdoc
     */
    public function displayException($exception)
    {
        $exceptionResponse = new ExceptionResponseData($exception);
        $this->sendResponse($exceptionResponse, $exceptionResponse->status);
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