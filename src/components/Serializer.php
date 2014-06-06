<?php
/**
 * Serializer class file.
 * @author Christoffer Lindqvist <christoffer.lindqvist@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software 2014-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-rest.components
 */

namespace nordsoftware\yii_rest\components;

/**
 * Serializer class that turns REST API response data into an array representation.
 */
class Serializer extends \CComponent
{
    /**
     * Serializes the response data.
     * @param Response $response the response object.
     */
    public function serialize(Response $response)
    {
        if ($response->data instanceof \CModel) {
            $response->data = $this->serializeModel($response->data, $response);
        } elseif ($response->data instanceof \CDataProvider) {
            $response->data = $this->serializeDataProvider($response->data);
        } else {
            $response->data = $this->toArray($response->data);
        }
    }

    /**
     * Serializes a model.
     * @param \CModel $model the model.
     * @param Response $response the response object.
     * @return \CModel|array the model or an array containing the model errors.
     */
    protected function serializeModel(\CModel $model, Response $response)
    {
        if ($model->hasErrors()) {
            $result = array();
            $response->setStatusCode(422);
            foreach ($model->getErrors() as $attribute => $errors) {
                $result[] = array(
                    'field' => $attribute,
                    'errors' => $errors,
                );
            }
            return $result;
        }
        return $this->toArray($model);
    }

    /**
     * Serializes a data provider.
     * @param \CDataProvider $dataProvider the provider.
     * @return array the data.
     */
    protected function serializeDataProvider(\CDataProvider $dataProvider)
    {
        $result = array();
        foreach ($dataProvider->getData() as $item) {
            $result[] = $this->toArray($item);
        }
        return $result;
    }

    /**
     * Convert the given object to an array.
     * Note that this relies on the behavior of the json encode/decode functions, hence it will only return the public
     * properties of any object passed to the function.
     * @param mixed $object the object to convert.
     * @return array the converted data as an array.
     */
    protected function toArray($object)
    {
        return json_decode(json_encode($object), true);
    }
}