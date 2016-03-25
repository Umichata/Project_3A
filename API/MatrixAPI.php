<?php

require_once("API.php");
require_once(realpath(dirname(__FILE__) . "/../" . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "Matrix.php"));

/**
 * Matrix API class computes the tasks and sends back a
 * response with a computed result.
 */
class MatrixAPI extends API {

    //--------------------------------------------------------
    // Constructors.
    //--------------------------------------------------------

    public function __construct($request, $origin) {
        parent::__construct($request);
    }

    //--------------------------------------------------------
    // Endpoints.
    //--------------------------------------------------------

    protected function example() {
        if ($this->method == 'GET') {
            return "This is work!!!";
        } else {
            return "Only accepts GET requests";
        }
    }

    protected function sum() {
        if ($this->method == 'POST') {
            $body = json_decode($this->file, true);

            $first_array  = (array) $body["first_matrix"];
            $second_array = (array) $body["second_matrix"];

            $first_matrix  = new Matrix($first_array);
            $second_matrix = new Matrix($second_array);

            $first_matrix->add($second_matrix);

            $result = array(
                "result" => $first_matrix->toString(),
            );

            return json_encode($result);
        } else {
            return false;
        }
    }

}

?>