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
     * @inheritdoc
     */
    public function displayError($code, $message, $file, $line)
    {
        $response = new Response();
        $data = array(
            'type' => 'PHP Error',
            'code' => $code,
            'status' => 500,
        );
        $data['message'] = YII_DEBUG ? "{$message} ({$file}:{$line})" : $message;
        $response->data = $data;
        $response->setStatusCode($data['status']);
        $response->send();
    }

    /**
     * @inheritdoc
     */
    public function displayException($exception)
    {
        $response = new Response();
        $data = array(
            'type' => get_class($exception),
            'code' => $exception->getCode(),
            'status' => ($exception instanceof \CHttpException) ? $exception->statusCode : 500,
        );
        $data['message'] = YII_DEBUG
            ? "{$exception->getMessage()} ({$exception->getFile()}:{$exception->getLine()})"
            : $exception->getMessage();
        $response->data = $data;
        $response->setStatusCode($data['status']);
        $response->send();
    }
}