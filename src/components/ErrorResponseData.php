<?php
/**
 * ErrorResponseData class file.
 * @author Christoffer Lindqvist <christoffer.lindqvist@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software 2014-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-rest.components
 */

namespace nordsoftware\yii_rest\components;

/**
 * ErrorResponseData for handling PHP error responses in the REST API.
 */
class ErrorResponseData extends ResponseData
{
    /**
     * @var string the type of error, e.g. "PHP Error".
     */
    public $type = 'PHP Error';

    /**
     * @var int the error code.
     */
    public $code;

    /**
     * @var int the error http status code, e.g. 500 (Internal Server Error).
     */
    public $status = 500;

    /**
     * @var string the message (will include file and line if YII_DEBUG is true).
     */
    public $message;

    /**
     * Constructor.
     * Applies the error data.
     * @param int $code the error code.
     * @param string $message the error message.
     * @param string $file the file the error occurred in.
     * @param int $line the line in the file the error occurred in.
     */
    public function __construct($code, $message, $file, $line)
    {
        $this->code = $code;
        $this->message = YII_DEBUG ? "{$message} ({$file}:{$line})" : $message;
    }
}