<?php

require_once("API.php");
require_once(realpath(dirname(__FILE__) . "/../" . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR
    . "matrix" . DIRECTORY_SEPARATOR . "Matrix.php"));

/**
 * Matrix API class computes the tasks and sends back a
 * response with a computed result.
 */
class MatrixAPI extends API
{

    //--------------------------------------------------------
    // Properties.
    //--------------------------------------------------------

    const STATUS_MESSAGE_KEY = "status_message";
    const STATUS_CODE_KEY = "status_code";

    const LEFT_MATRIX_KEY = "left";
    const RIGHT_MATRIX_KEY = "right";
    const MATRIX_KEY = "matrix";
    const VECTOR_OF_VALUES = "vector";
    const OPERATION_RESULT_KEY = "result";

    //--------------------------------------------------------
    // Constructors.
    //--------------------------------------------------------

    public function __construct($request, $origin)
    {
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
    protected function add()
    {
        if ($this->isPostMethod()) {
            $matrices = $this->matricesFromBinaryOperation();

            $leftMatrix = $matrices[0];
            $rightMatrix = $matrices[1];

            $leftMatrix->add($rightMatrix);

            $result = array(
                MatrixAPI::OPERATION_RESULT_KEY => $leftMatrix->getData()
            );

            return $result;
        } else {
            return $this->wrongMethodError();
        }
    }

    /**
     * Computes subtract matrix from this ones.
     *
     * @return array
     * @throws MatrixException
     */
    protected function sub()
    {
        if ($this->isPostMethod()) {
            $matrices = $this->matricesFromBinaryOperation();

            $leftMatrix = $matrices[0];
            $rightMatrix = $matrices[1];

            $leftMatrix->sub($rightMatrix);

            $result = array(
                MatrixAPI::OPERATION_RESULT_KEY => $leftMatrix->getData()
            );

            return $result;
        } else {
            return $this->wrongMethodError();
        }
    }

    /**
     * Multiply matrices and returning the result as a new matrix.
     *
     * @return array
     */
    protected function multiply()
    {
        if ($this->isPostMethod()) {
            $matrices = $this->matricesFromBinaryOperation();

            $leftMatrix = $matrices[0];
            $rightMatrix = $matrices[1];

            $multiplyMatrix = Math_Matrix::multiplyMatrices($leftMatrix, $rightMatrix);

            $result = array(
                MatrixAPI::OPERATION_RESULT_KEY => $multiplyMatrix->getData()
            );

            return $result;
        } else {
            return $this->wrongMethodError();
        }
    }

    /**
     * Transpose the matrix rows and columns.
     *
     * @return array|string
     * @throws MatrixException
     */
    protected function transpose()
    {
        if ($this->isPostMethod()) {
            $matrix = $this->matrixFromUnaryOperation();

            if ($matrix->transpose()) {
                $result = array(
                    MatrixAPI::OPERATION_RESULT_KEY => $matrix->getData()
                );

                return $result;
            } else {
                return "Failed to transpose the matrix";
            }
        } else {
            return $this->wrongMethodError();
        }
    }

    /**
     * Calculates the matrix determinant.
     *
     * @return array
     * @throws MatrixException
     */
    protected function determinant()
    {
        if ($this->isPostMethod()) {
            $matrix = $this->matrixFromUnaryOperation();
            $det = $matrix->determinant();

            $result = array(
                MatrixAPI::OPERATION_RESULT_KEY => $det
            );

            return $result;
        } else {
            return $this->wrongMethodError();
        }
    }

    /**
     * Inverts a matrix.
     *
     * @return array
     * @throws MatrixException
     */
    protected function invert()
    {
        if ($this->isPostMethod()) {
            $matrix = $this->matrixFromUnaryOperation();
            $matrix->invert();

            $result = array(
                MatrixAPI::OPERATION_RESULT_KEY => $matrix->getData()
            );

            return $result;
        } else {
            return $this->wrongMethodError();
        }
    }

    /**
     * Solves a system of linear equations: Ax = b.
     *
     * @return array
     */
    protected function solve()
    {
        if ($this->isPostMethod()) {
            $json = json_decode($this->file, true);

            $coefficientsArray = (array)$json[MatrixAPI::MATRIX_KEY];
            $matrix = new Math_Matrix($coefficientsArray);

            $vectorArray = $json[MatrixAPI::VECTOR_OF_VALUES];
            $vector = new Math_Vector($vectorArray);

            $solutionVector = Math_Matrix::solve($matrix, $vector);

            return array(
                MatrixAPI::OPERATION_RESULT_KEY => $solutionVector->getData
            );
        } else {
            return $this->wrongMethodError();
        }
    }

    /**
     * Solves a system of linear equations: Ax = b, using an iterative error correction algorithm.
     *
     * @return array
     */
    protected function solveec()
    {
        if ($this->isPostMethod()) {
            $json = json_decode($this->file, true);

            $coefficientsArray = (array)$json[MatrixAPI::MATRIX_KEY];
            $matrix = new Math_Matrix($coefficientsArray);

            $vectorArray = $json[MatrixAPI::VECTOR_OF_VALUES];
            $vector = new Math_Vector($vectorArray);

            $solutionVector = Math_Matrix::solveEC($matrix, $vector);

            return array(
                MatrixAPI::OPERATION_RESULT_KEY => $solutionVector->getData
            );
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
    private function isPostMethod()
    {
        return $this->method == 'POST';
    }

    /**
     * @return array with error information.
     */
    private function wrongMethodError()
    {
        return array(
            MatrixAPI::STATUS_MESSAGE_KEY => "Supports only POST methods",
            MatrixAPI::STATUS_CODE_KEY => 777
        );
    }

    /**
     * Returns an array of Math_Matrix objects.
     *
     * @return array
     */
    private function matricesFromBinaryOperation()
    {
        $json = json_decode($this->file, true);

        $lftArray = (array)$json[MatrixAPI::LEFT_MATRIX_KEY];
        $rghArray = (array)$json[MatrixAPI::RIGHT_MATRIX_KEY];

        $leftMatrix = new Math_Matrix($lftArray);
        $rightMatrix = new Math_Matrix($rghArray);

        return array($leftMatrix, $rightMatrix);
    }

    /**
     * Returns matrix from unary operation request body.
     *
     * @return Math_Matrix
     */
    private function matrixFromUnaryOperation()
    {
        $json = json_decode($this->file, true);

        $arr = (array)$json[MatrixAPI::MATRIX_KEY];
        $matrix = new Math_Matrix($arr);

        return $matrix;
    }

}
