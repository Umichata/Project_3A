<?php

require_once("API.php");

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

}

?>