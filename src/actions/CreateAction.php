<?php
/**
 * CreateAction class file.
 * @author Christoffer Lindqvist <christoffer.lindqvist@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software 2014-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-rest.actions
 */

namespace nordsoftware\yii_rest\actions;

/**
 * CreateAction implements the REST API endpoint for creating a new model from the given data.
 */
class CreateAction extends Action
{
    /**
     * Creates a new model.
     */
    public function run()
    {
        /** @var \CActiveRecord $model */
        $model = new $this->modelClass();
        $model->attributes = $_POST;
        $model->save();
        $this->sendResponse($model);
    }
}