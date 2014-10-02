<?php
/**
 * IndexAction class file.
 * @author Christoffer Lindqvist <christoffer.lindqvist@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software 2014-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-rest.actions
 */

namespace nordsoftware\yii_rest\actions;

/**
 * IndexAction implements the REST API endpoint for listing models.
 */
class IndexAction extends Action
{
    /**
     * Lists existing models.
     */
    public function run()
    {
        $provider = new \CActiveDataProvider($this->modelClass, array(
            'data' => \CActiveRecord::model($this->modelClass)->findAll()
        ));
        $this->sendResponse($provider);
    }
}