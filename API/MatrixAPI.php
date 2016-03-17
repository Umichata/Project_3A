<?php

require_once("StatusCode.php");

/**
 * Matrix API class computes the tasks and sends back a
 * response with a computed result.
 */
class MatrixAPI {

    /**
     * Main method to compute a task.
     */
    function compute() {
        // TODO: Check fot required paramers in POST method and compute the task.
    }
}

// This is the first thing that gets called when this page is loaded.
// Creates a new instance of the MatrixAPI class and calls the 'compute' method.
$api = new MatrixAPI();
$api->compute();

?>