<?php

require_once("API.php");
require_once(realpath(dirname(__FILE__) . "/../" . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR
    . "matrix" . DIRECTORY_SEPARATOR . "Matrix.php"));

/**
 * Matrix API class computes the tasks and sends back a
 * response with a computed result.
 */
class MatrixAPI extends API {

    //--------------------------------------------------------
    // Properties.
    //--------------------------------------------------------

    const STATUS_MESSAGE_KEY = "status_message";
    const STATUS_CODE_KEY = "status_code";

    const LEFT_MATRIX_KEY = "left";
    const RIGHT_MATRIX_KEY = "right";
    const OPERATION_RESULT_KEY = "result";

    //--------------------------------------------------------
    // Constructors.
    //--------------------------------------------------------

    public function __construct($request, $origin) {
        parent::__construct($request);
    }

    //--------------------------------------------------------
    // Endpoints.
    //--------------------------------------------------------

    /**
     * Computes addition of the matrices passed in POST body.
     * Returns the result of that addition operation.
     *
     * @return array
     * @throws MatrixException
     */
    protected function add() {
        if ($this->isPostMethod()) {
            $json = json_decode($this->file, true);

            $lftArray = (array) $json[MatrixAPI::LEFT_MATRIX_KEY];
            $rghArray = (array) $json[MatrixAPI::RIGHT_MATRIX_KEY];

            $leftMatrix  = new Math_Matrix($lftArray);
            $rightMatrix = new Math_Matrix($rghArray);

            $leftMatrix->add($rightMatrix);

            $result = array(
                MatrixAPI::OPERATION_RESULT_KEY => $leftMatrix->getData()
            );

            return $result;
        } else {
            return $this->wrongMethodError();
        }
    }

    //--------------------------------------------------------
    // Helpers.
    //--------------------------------------------------------

    /**
     * @return bool, success if requested method is POST type.
     */
    private function isPostMethod() {
        return $this->method == 'POST';
    }

    /**
     * @return array with error information.
     */
    private function wrongMethodError() {
        return array(
            MatrixAPI::STATUS_MESSAGE_KEY => "Supports only POST methods",
            MatrixAPI::STATUS_CODE_KEY => 777
        );
    }

}
