<?php
/**
 * ContentTypeValidator class file.
 * @author Christoffer Lindqvist <christoffer.lindqvist@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software 2014-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-rest.components
 */

namespace nordsoftware\yii_rest\filters;

use nordsoftware\yii_rest\components\Request;

/**
 * ContentTypeValidator class for validating the HTTP request content type.
 */
class ContentTypeValidator extends \CFilter
{
    /**
     * @var array list of supported content types.
     */
    public $types = array();

    /**
     * @inheritdoc
     */
    protected function preFilter($filterChain)
    {
        if (!empty($this->types)) {
            /** @var Request $request */
            $request = \Yii::app()->getRequest();
            if (!in_array($request->getContentType(), $this->types)) {
                throw new \CHttpException(415, 'Unsupported Media Type');
            }
        }

        return parent::preFilter($filterChain);
    }
}