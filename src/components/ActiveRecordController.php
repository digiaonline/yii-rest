<?php
/**
 * ActiveRecordController class file.
 * @author Christoffer Lindqvist <christoffer.lindqvist@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software 2014-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-rest.components
 */

namespace nordsoftware\yii_rest\components;

/**
 * ActiveRecordController base class that implements common active record REST actions.
 *
 * The following actions are supported:
 * - "index": list of models
 * - "view": return the details of a model
 * - "create": create a new model
 * - "update": update an existing model
 * - "delete": delete an existing model
 *
 * The defaults can be disabled by overriding the actions() method. If you wish to override a specific action,
 * just add the action method in your controller and it will be used instead of the defaults.
 *
 * Note that all access checking is left up to you.
 */
abstract class ActiveRecordController extends Controller
{
    /**
     * @var string the model class name. This property must be set.
     */
    public $modelClass;

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
    public function actions()
    {
        return array(
            'index' => array(
                'class' => 'nordsoftware\yii_rest\actions\IndexAction',
                'modelClass' => $this->modelClass,
            ),
            'create' => array(
                'class' => 'nordsoftware\yii_rest\actions\CreateAction',
                'modelClass' => $this->modelClass,
            ),
            'view' => array(
                'class' => 'nordsoftware\yii_rest\actions\ViewAction',
                'modelClass' => $this->modelClass,
            ),
            'update' => array(
                'class' => 'nordsoftware\yii_rest\actions\UpdateAction',
                'modelClass' => $this->modelClass,
            ),
            'delete' => array(
                'class' => 'nordsoftware\yii_rest\actions\DeleteAction',
                'modelClass' => $this->modelClass,
            ),
        );
    }
}