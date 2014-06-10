<?php
/**
 * DeleteAction class file.
 * @author Christoffer Lindqvist <christoffer.lindqvist@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software 2014-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-rest.actions
 */

namespace nordsoftware\yii_rest\actions;

/**
 * DeleteAction implements the REST API endpoint for deleting a model.
 */
class DeleteAction extends Action
{
    /**
     * Deletes a model.
     * @param int $id the model id.
     */
    public function run($id)
    {
        $model = $this->findModel($id);
        $model->delete();
        $this->sendResponse(null, 204);
    }
}