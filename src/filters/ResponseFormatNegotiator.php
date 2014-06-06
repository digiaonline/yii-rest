<?php
/**
 * ResponseFormatNegotiator class file.
 * @author Christoffer Lindqvist <christoffer.lindqvist@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software 2014-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-rest.components
 */

namespace nordsoftware\yii_rest\filters;

use nordsoftware\yii_rest\components\Request;
use nordsoftware\yii_rest\components\Response;

/**
 * ResponseFormatNegotiator class implements response format negotiation based on the HTTP request Accept header.
 */
class ResponseFormatNegotiator extends \CFilter
{
    /**
     * @var array map of supported content type => response format.
     */
    public $formats = array();

    /**
     * @inheritdoc
     */
    protected function preFilter($filterChain)
    {
        if (!empty($this->formats)) {
            /** @var Request $request */
            $request = \Yii::app()->getRequest();
            /** @var Response $response */
            $response = \Yii::app()->getComponent('response');

            $types = $request->getAcceptableContentTypes();
            if (empty($types)) {
                // any format will do, choose the first one defined.
                foreach ($this->formats as $responseFormat) {
                    $response->format = $responseFormat;
                    return true;
                }
            } else {
                foreach ($types as $contentType => $params) {
                    if (isset($this->formats[$contentType])) {
                        $response->format = $this->formats[$contentType];
                        return true;
                    }
                }
            }

            throw new \CHttpException(415, 'Unsupported Media Type');
        }

        return parent::preFilter($filterChain);
    }
} 