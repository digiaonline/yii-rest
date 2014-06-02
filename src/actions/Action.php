<?php
/**
 * Action class file.
 * @author Christoffer Lindqvist <christoffer.lindqvist@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software 2014-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-rest.actions
 */

namespace nordsoftware\yii_rest\actions;

use nordsoftware\yii_rest\components\Controller;

/**
 * Action base class all REST API action classes.
 *
 * @property Controller $controller The controller who owns this action.
 */
abstract class Action extends \CAction
{
    /**
     * @var string class name of the model which will be handled by this action.
     */
    public $modelClass;

    /**
     * Returns the data model based on the primary key given.
     * @param string $id the ID of the model to be loaded.
     * @return \CActiveRecord the model.
     * @throws \CHttpException if the model cannot be found.
     */
    public function findModel($id)
    {
        $model = \CActiveRecord::model($this->modelClass)->findByPk($id);
        if ($model === null) {
            throw new \CHttpException(404, \Yii::t('\nordsoftware\yii_rest\components', 'Not Found'));
        }
        return $model;
    }

    /**
     * Sends the API response to the client.
     * @param mixed $data the data to send as the response body.
     * @param int $statusCode the status code of the response.
     */
    public function sendResponse($data, $statusCode = 200)
    {
        $this->controller->sendResponse($data, $statusCode);
    }
} 