<?php
/**
 * ExceptionResponseData class file.
 * @author Christoffer Lindqvist <christoffer.lindqvist@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software 2014-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-rest.components
 */

namespace nordsoftware\yii_rest\components;

/**
 * ExceptionResponseData for handling exception responses in the REST API.
 */
class ExceptionResponseData extends ResponseData
{
    /**
     * @var string the type of exception, i.e. the exception class name.
     */
    public $type;

    /**
     * @var int the exception code.
     * @link http://php.net/manual/en/exception.getcode.php
     */
    public $code;

    /**
     * @var int the exception http status code.
     */
    public $status;

    /**
     * @var string the exception message (will include file and line if YII_DEBUG is true).
     * @link http://php.net/manual/en/exception.getmessage.php
     */
    public $message;

    /**
     * @var array the exception trace stack (only when YII_DEBUG is true).
     * @link http://php.net/manual/en/exception.gettrace.php
     */
    public $trace;

    /**
     * Applies the data from the given exception instance to this response.
     * @param \Exception $e the exception instance.
     */
    public function init(\Exception $e)
    {
        $this->type = get_class($e);
        $this->code = $e->getCode();
        $this->status = ($e instanceof \CHttpException) ? $e->statusCode : 500;
        $this->message = $e->getMessage();
        if (YII_DEBUG) {
            $this->message .= " ({$e->getFile()}:{$e->getLine()})";
            $this->trace = $e->getTrace();
        }
    }
}