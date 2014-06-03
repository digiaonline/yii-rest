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
     * @var string the model class name. This property must be set.
     */
    public $modelClass;

    /**
     * @var Response the response object.
     */
    private $_response;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->modelClass === null) {
            throw new \CException('The "modelClass" property must be set.');
        }
    }

    /**
     * @inheritdoc
     */
    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    /**
     * @inheritdoc
     */
    public function accessRules()
    {
        return array(
            // Logged in users can do whatever they want to.
            array('allow', 'users' => array('@')),
            // Not logged in users can't do anything except above.
            array('deny'),
        );
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return array(
            'index' => array(
                'class' => ' nordsoftware\yii_rest\actions\IndexAction',
                'modelClass' => $this->modelClass,
            ),
            'create' => array(
                'class' => ' nordsoftware\yii_rest\actions\CreateAction',
                'modelClass' => $this->modelClass,
            ),
            'view' => array(
                'class' => ' nordsoftware\yii_rest\actions\ViewAction',
                'modelClass' => $this->modelClass,
            ),
            'update' => array(
                'class' => ' nordsoftware\yii_rest\actions\UpdateAction',
                'modelClass' => $this->modelClass,
            ),
            'delete' => array(
                'class' => ' nordsoftware\yii_rest\actions\DeleteAction',
                'modelClass' => $this->modelClass,
            ),
        );
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
        $this->response->data = $this->serializeData($data);
        $this->response->send();
    }

    /**
     * Serializes the response data.
     * @param mixed $data the data to serialize.
     * @return mixed the serialized data.
     */
    protected function serializeData($data)
    {
        if ($data instanceof \CModel) {
            return $this->serializeModel($data);
        } elseif ($data instanceof \CDataProvider) {
            return $this->serializeDataProvider($data);
        }
        return $data;
    }

    /**
     * Serializes a model.
     * @param \CModel $model the model.
     * @return \CModel|array the model or an array containing the model errors.
     */
    protected function serializeModel(\CModel $model)
    {
        if ($model->hasErrors()) {
            $result = array();
            $this->response->setStatusCode(422);
            foreach ($model->getErrors() as $attribute => $errors) {
                $result[] = array(
                    'field' => $attribute,
                    'errors' => $errors,
                );
            }
            return $result;
        }
        return $model;
    }

    /**
     * Serializes a data provider.
     * @param \CDataProvider $dataProvider the provider.
     * @return array the data.
     */
    protected function serializeDataProvider(\CDataProvider $dataProvider)
    {
        return $dataProvider->getData();
    }
}