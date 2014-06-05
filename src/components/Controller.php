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
 *
 * @property Response $response the response object.
 */
abstract class Controller extends \CController
{
    /**
     * @var string the response data serializer class to use.
     */
    public $serializer = 'nordsoftware\yii_rest\components\Serializer';

    /**
     * @var array list of accepted request content types for this controllers actions.
     */
    public $acceptedContentTypes = array(
        'application/json'
    );

    /**
     * @var Response the response object.
     */
    private $_response;

    /**
     * @inheritdoc
     */
    public function filters()
    {
        return array(
            'ensureContentType'
        );
    }

    /**
     * Filter for ensuring that the request has a specific content type.
     * @param \CFilterChain $filterChain
     * @throws \CHttpException if the content type does not match one of the configured ones.
     */
    public function filterEnsureContentType($filterChain)
    {
        $contentType = \Yii::app()->getRequest()->getContentType();
        if (!in_array($contentType, $this->acceptedContentTypes)) {
            throw new \CHttpException(400, \Yii::t('api', 'Bad Request'));
        }
        $filterChain->run();
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        if ($this->_response === null) {
            $this->_response = new Response();
        }
        return $this->_response;
    }

    /**
     * Sends the API response to the client.
     * @param mixed $data the data to send as the response body.
     * @param int $statusCode the status code of the response.
     */
    public function sendResponse($data, $statusCode = 200)
    {
        $this->response->setStatusCode($statusCode);
        $this->response->data = \Yii::createComponent(array(
                'class' => $this->serializer,
                'response' => $this->response,
            ))->serialize($data);
        $this->response->send();
    }
}