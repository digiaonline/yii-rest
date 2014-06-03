<?php
/**
 * ResponseData class file.
 * @author Christoffer Lindqvist <christoffer.lindqvist@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software 2014-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-rest.components
 */

namespace nordsoftware\yii_rest\components;

/**
 * ResponseData base class that can be used for the data returned by the REST API.
 * Extends from \CModel and thus provides error handling and data validation if necessary.
 */
abstract class ResponseData extends \CModel
{
    /**
     * @inheritdoc
     */
    public function attributeNames()
    {
        $names = array();
        $class = new \ReflectionClass($this);
        foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            $names[] = $property->name;
        }
        return $names;
    }
}