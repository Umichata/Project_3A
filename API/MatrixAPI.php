<?php

require_once("API.php");
require_once("MatrixAPIConstant.php");
require_once(realpath(dirname(__FILE__) . "/../" . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR
    . "matrix" . DIRECTORY_SEPARATOR . "Matrix.php"));

/**
 * Matrix API class computes the tasks and sends back a
 * response with a computed result.
 */
class MatrixAPI extends API {

    //--------------------------------------------------------
    // Initializer.
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
     */
    protected function add() {
        if ($this->isPostMethod()) {
            $matrices = $this->matrixArrayFromBinaryOperation();

            $leftMatrix = new Math_Matrix($matrices[0]);
            $rightMatrix = new Math_Matrix($matrices[1]);

            $failMessage = "Failed to process the addition operation.";

            try {
                if ($leftMatrix->add($rightMatrix)) {
                    $result = array_merge(
                        $this->successResponseWithMessage("Addition operation is successfully completed."),
                        array(MatrixAPIConstant::OPERATION_RESULT_KEY => $leftMatrix->getData())
                    );

                    return $result;
                } else {
                    return $this->failResponseWithMessage($failMessage);
                }
            } catch (Exception $exception) {
                return $this->failResponseWithMessage($failMessage
                    . " "
                    . $exception->getMessage()
                    . ".");
            }
        } else {
            return $this->sendWrongMethodResponse();
        }
    }

    /**
     * Computes subtract matrix from this ones.
     *
     * @return array
     */
    protected function sub() {
        if ($this->isPostMethod()) {
            $matrices = $this->matrixArrayFromBinaryOperation();

            $leftMatrix = new Math_Matrix($matrices[0]);
            $rightMatrix = new Math_Matrix($matrices[1]);

            $failMessage = "Failed to process the subtract operation.";

            try {
                if ($leftMatrix->sub($rightMatrix)) {
                    $result = array_merge(
                        $this->successResponseWithMessage("Subtract operation is successfully completed."),
                        array(MatrixAPIConstant::OPERATION_RESULT_KEY => $leftMatrix->getData())
                    );

                    return $result;
                } else {
                    return $this->failResponseWithMessage($failMessage);
                }
            } catch (Exception $exception) {
                return $this->failResponseWithMessage($failMessage
                    . " "
                    . $exception->getMessage()
                    . ".");
            }
        } else {
            return $this->sendWrongMethodResponse();
        }
    }

    /**
     * Multiply matrices and returning the result as a new matrix.
     *
     * @return array
     */
    protected function multiply() {
        if ($this->isPostMethod()) {
            $matrices = $this->matrixArrayFromBinaryOperation();

            $leftMatrix = new Math_Matrix($matrices[0]);
            $rightMatrix = new Math_Matrix($matrices[1]);

            try {
                $multiplyMatrix = Math_Matrix::multiplyMatrices($leftMatrix, $rightMatrix);

                $result = array_merge(
                    $this->successResponseWithMessage("Multiply operation is successfully completed."),
                    array(MatrixAPIConstant::OPERATION_RESULT_KEY => $multiplyMatrix->getData())
                );

                return $result;
            } catch (Exception $exception) {
                return $this->failResponseWithMessage("Failed to process the multiply operation."
                    . " "
                    . $exception->getMessage()
                    . ".");
            }
        } else {
            return $this->sendWrongMethodResponse();
        }
    }

    /**
     * Transpose the matrix rows and columns.
     *
     * @return array
     */
    protected function transpose() {
        if ($this->isPostMethod()) {
            $matrix = $this->matrixFromUnaryOperation();

            $failMessage = "Failed to process the transpose operation.";

            try {
                if ($matrix->transpose()) {
                    $result = array_merge(
                        $this->successResponseWithMessage("Transpose operation is successfully completed."),
                        array(MatrixAPIConstant::OPERATION_RESULT_KEY => $matrix->getData())
                    );

                    return $result;
                } else {
                    return $this->failResponseWithMessage($failMessage);
                }
            } catch (Exception $exception) {
                return $this->failResponseWithMessage($failMessage
                    . " "
                    . $exception->getMessage()
                    . ".");
            }
        } else {
            return $this->sendWrongMethodResponse();
        }
    }

    /**
     * Calculates the matrix determinant.
     *
     * @return array
     */
    protected function determinant() {
        if ($this->isPostMethod()) {
            $matrix = $this->matrixFromUnaryOperation();

            try {
                $determinant = $matrix->determinant();

                $result = array_merge(
                    $this->successResponseWithMessage("Determinant operation is successfully completed."),
                    array(MatrixAPIConstant::OPERATION_RESULT_KEY => $determinant)
                );

                return $result;
            } catch (Exception $exception) {
                return $this->failResponseWithMessage("Failed to process the determinant operation."
                    . " "
                    . $exception->getMessage()
                    . ".");
            }
        } else {
            return $this->sendWrongMethodResponse();
        }
    }

    /**
     * Process invert operation on the matrix.
     *
     * @return array
     */
    protected function invert() {
        if ($this->isPostMethod()) {
            $matrix = $this->matrixFromUnaryOperation();

            try {
                $matrix->invert();

                $result = array_merge(
                    $this->successResponseWithMessage("Invert operation is successfully completed."),
                    array(MatrixAPIConstant::OPERATION_RESULT_KEY => $matrix->getData())
                );

                return $result;
            } catch (Exception $exception) {
                return $this->failResponseWithMessage("Failed to process the invert operation."
                    . " "
                    . $exception->getMessage()
                    . ".");
            }
        } else {
            return $this->sendWrongMethodResponse();
        }
    }

    /**
     * Solves a system of linear equations: Ax = b.
     *
     * @return array
     */
    protected function solve() {
        if ($this->isPostMethod()) {
            // Parse the JSON and access the values.
            $json = json_decode($this->file, true);

            // coefficientsMatrix is a matrix (A) of coefficients (aij, i=1..k, j=1..n).
            $coefficientsArray = (array)$json[MatrixAPIConstant::MATRIX_KEY];
            $coefficientsMatrix = new Math_Matrix($coefficientsArray);

            // valuesVector is a vector (b) of values (bi, i=1..k).
            $vectorArray = (array)$json[MatrixAPIConstant::VECTOR_OF_VALUES_KEY];
            $valuesVector = new Math_Vector($vectorArray);

            try {
                // Find the solution vector (x).
                $solutionVector = Math_Matrix::solve($coefficientsMatrix, $valuesVector);

                $result = array_merge(
                    $this->successResponseWithMessage("Solve a system of linear equations is successfully completed."),
                    array(MatrixAPIConstant::OPERATION_RESULT_KEY => $solutionVector->getData())
                );

                return $result;
            } catch (Exception $exception) {
                return $this->failResponseWithMessage("Failed to solve a system of linear equations."
                    . " "
                    . $exception->getMessage()
                    . ".");
            }
        } else {
            return $this->sendWrongMethodResponse();
        }
    }

    /**
     * Solves a system of linear equations: Ax = b, using an iterative error correction algorithm.
     *
     * @return array
     */
    protected function solveec() {
        if ($this->isPostMethod()) {
            // Parse the JSON and access the values.
            $json = json_decode($this->file, true);

            // coefficientsMatrix is a matrix (A) of coefficients (aij, i=1..k, j=1..n).
            $coefficientsArray = (array)$json[MatrixAPIConstant::MATRIX_KEY];
            $coefficientsMatrix = new Math_Matrix($coefficientsArray);

            // valuesVector is a vector (b) of values (bi, i=1..k).
            $vectorArray = (array)$json[MatrixAPIConstant::VECTOR_OF_VALUES_KEY];
            $valuesVector = new Math_Vector($vectorArray);

            try {
                // Find the solution vector (x).
                $solutionVector = Math_Matrix::solveEC($coefficientsMatrix, $valuesVector);

                $result = array_merge(
                    $this->successResponseWithMessage("Solve a system of linear equations, using an iterative error
                    correction algorithm is successfully completed."),
                    array(MatrixAPIConstant::OPERATION_RESULT_KEY => $solutionVector->getData())
                );

                return $result;
            } catch (Exception $exception) {
                return $this->failResponseWithMessage("Failed to solve a system of linear equations,
                using an iterative error correction algorithm."
                    . " "
                    . $exception->getMessage()
                    . ".");
            }
        } else {
            return $this->sendWrongMethodResponse();
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
    private function sendWrongMethodResponse() {

        return $this->failResponseWithMessage("Requested for unsupported method. Expected POST method.");
    }

    /**
     * Returns an array of arrays.
     *
     * @return array
     */
    private function matrixArrayFromBinaryOperation() {
        $json = json_decode($this->file, true);

        $lftArray = (array)$json[MatrixAPIConstant::LEFT_MATRIX_KEY];
        $rghArray = (array)$json[MatrixAPIConstant::RIGHT_MATRIX_KEY];

        return array($lftArray, $rghArray);
    }

    /**
     * Returns matrix from unary operation request body.
     *
     * @return Math_Matrix
     */
    private function matrixFromUnaryOperation() {
        $json = json_decode($this->file, true);

        $arr = (array)$json[MatrixAPIConstant::MATRIX_KEY];
        $matrix = new Math_Matrix($arr);

        return $matrix;
    }

    /**
     * @param string $message
     * @return array
     */
    private function successResponseWithMessage($message) {
        return array(
            MatrixAPIConstant::STATUS_CODE_KEY => MatrixAPIConstant::SUCCESS_STATUS_CODE,
            MatrixAPIConstant::STATUS_MESSAGE_KEY => $message
        );
    }

    private function failResponseWithMessage($message) {
        return array(
            MatrixAPIConstant::STATUS_CODE_KEY => MatrixAPIConstant::FAIL_STATUS_CODE,
            MatrixAPIConstant::STATUS_MESSAGE_KEY => $message
        );
    }

}
