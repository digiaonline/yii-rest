<?php
/**
 * UpdateAction class file.
 * @author Christoffer Lindqvist <christoffer.lindqvist@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software 2014-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-rest.actions
 */

namespace nordsoftware\yii_rest\actions;

/**
 * UpdateAction implements the REST API endpoint for updating a model.
 */
class UpdateAction extends Action
{
    /**
     * Updates an existing model.
     * @param int $id the model id.
     */
    public function run($id)
    {
        $model = $this->findModel($id);
        $model->attributes = $_POST;
        $model->save();
        $this->sendResponse($model);
    }
}