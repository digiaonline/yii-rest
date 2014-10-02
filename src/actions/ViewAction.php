<?php
/**
 * ViewAction class file.
 * @author Christoffer Lindqvist <christoffer.lindqvist@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software 2014-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-rest.actions
 */

namespace nordsoftware\yii_rest\actions;

/**
 * ViewAction implements the REST API endpoint for viewing a model.
 */
class ViewAction extends Action
{
    /**
     * Views a model.
     * @param int $id the model id.
     */
    public function run($id)
    {
        $model = $this->findModel($id);
        $this->sendResponse($model);
    }
}