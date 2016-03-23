<?php

require_once("MatrixException.php");

/**
 * Defines a matrix object.
 *
 * A matrix is implemented as an array of arrays such that:
 *
 * <pre>
 * [0][0] [0][1] [0][2] ... [0][M]
 * [1][0] [1][1] [1][2] ... [1][M]
 * ...
 * [N][0] [n][1] [n][2] ... [n][M]
 * </pre>
 *
 * i.e. N rows, M colums
 */
class Matrix {

    //--------------------------------------------------------
    // Properties.
    //--------------------------------------------------------

    /**#@+
     * @access private
     */

    /**
     * Contains the array of arrays defining the matrix
     *
     * @var     array
     * @see     getData()
     */
    var $_data = null;

    /**
     * The number of rows in the matrix
     *
     * @var     integer
     * @see     getSize()
     */
    var $_num_rows = null;

    /**
     * The number of columns in the matrix
     *
     * @var     integer
     * @see     getSize()
     */
    var $_num_cols = null;

    /**
     * A flag indicating if the matrix is square
     * i.e. if $this->_num_cols == $this->_num_rows
     *
     * @var     boolean
     * @see     isSquare()
     */
    var $_square = false;

    /**#@+
     * @access private
     * @var    float
     */
    /**
     * The smallest value of all matrix cells
     *
     * @see     getMin()
     * @see     getMinMax()
     */
    var $_min = null;

    /**
     * The biggest value of all matrix cells
     *
     * @see     getMax()
     * @see     getMinMax()
     */
    var $_max = null;

    /**
     * The Euclidean norm for the matrix: sqrt(sum(e[i][j]^2))
     *
     * @see norm()
     */
    var $_norm = null;

    /**
     * The matrix determinant
     *
     * @see determinant()
     */
    var $_det = null;
    
    /**
     * Cutoff error used to test for singular or ill-conditioned matrices
     *
     * @see determinant();
     * @see invert()
     */
    var $_epsilon = 1E-18;

    //--------------------------------------------------------
    // Constructors.
    //--------------------------------------------------------

    /**#@+
     * @access  public
     */
    /**
     * Constructor for the matrix object
     *
     * @param   array|Matrix   $data a numeric array of arrays of a Matrix object
     * @return  object  Matrix
     * @see     $_data
     * @see     setData()
     */
    function Matrix($data = null) {
        if (!is_null($data)) {
            $this->setData($data);
        }
    }

    // Getters and Setters.

    /**
     * Returns the array of arrays.
     *
     * @return array
     * @throws MatrixException
     */
    function getData () {
        if ($this->isEmpty()) {
            throw new MatrixException('Matrix has not been populated');
        } else {
            return $this->_data;
        }
    }

    /**
     * Validates the data and initializes the internal variables (except for the determinant).
     *
     * The validation is performed by by checking that
     * each row (first dimension in the array of arrays)
     * contains the same number of colums (e.g. arrays of the
     * same size)
     *
     * @param   array   $data array of arrays of numbers or a valid Matrix object
     * @return  boolean
     * @throws InvalidArgumentException
     */
    function setData($data) {
        if (Matrix::isMatrix($data)) {
            if (!$data->isEmpty()) {
                $this->_data = $data->getData();
            } else {
                return false;
            }
        } elseif (is_array($data) || is_array($data[0])) {
            // check that we got a numeric bidimensional array
            // and that all rows are of the same size
            $nc = 0;
            if (!empty($data[0])) {
                $nc = count($data[0]);
            }

            $nr = count($data);
            $eucnorm = 0;
            $tmp = array();

            for ($i = 0; $i < $nr; $i++) {
                if (count($data[$i]) != $nc) {
                    throw new InvalidArgumentException('Invalid data, cannot create/modify matrix.'.
                        ' Expecting an array of arrays or an initialized Matrix object');
                }
                for ($j = 0; $j < $nc; $j++) {
                    if (!is_numeric($data[$i][$j])) {
                        throw new InvalidArgumentException('Invalid data, cannot create/modify matrix.'.
                            ' Expecting an array of arrays or an initialized Matrix object');
                    }

                    $data[$i][$j] = (float)$data[$i][$j];
                    $tmp[] = $data[$i][$j];
                    $eucnorm += $data[$i][$j] * $data[$i][$j];
                }
            }

            $this->_num_rows = $nr;
            $this->_num_cols = $nc;
            $this->_square = ($nr == $nc);
            $this->_min = !empty($tmp)? min($tmp) : null;
            $this->_max = !empty($tmp)? max($tmp) : null;
            $this->_norm = sqrt($eucnorm);
            $this->_data = $data;
            $this->_det = null; // lazy initialization

            return true;
        } else {
            throw new InvalidArgumentException('Invalid data, cannot create/modify matrix.'.
                ' Expecting an array of arrays or an initialized Matrix object');
        }
    }

    /**
     * Checks if the matrix has been initialized.
     *
     * @return boolean TRUE on success, FALSE otherwise
     */
    function isEmpty() {
        return (empty($this->_data) || is_null($this->_data));
    }

    /**
     * Returns an array with the number of rows and columns in the matrix
     *
     * @return  array
     * @throws MatrixException
     */
    function getSize() {
        if ($this->isEmpty())
            throw new MatrixException('Matrix has not been populated');
        else
            return array($this->_num_rows, $this->_num_cols);
    }

    /**
     * Checks if it is a square matrix (i.e. num rows == num cols)
     *
     * @return boolean TRUE on success, FALSE otherwise
     * @throws MatrixException
     */
    function isSquare () {
        if ($this->isEmpty()) {
            throw new MatrixException('Matrix has not been populated');
        } else {
            return $this->_square;
        }
    }

    /**
     * Returns the Euclidean norm of the matrix.
     *
     * Euclidean norm = sqrt( sum( e[i][j]^2 ) )
     *
     * @return float
     * @throws MatrixException
     */
    function norm() {
        if (!is_null($this->_norm)) {
            return $this->_norm;
        } else {
            throw new MatrixException('Uninitialized Matrix object');
        }
    }

    /**
     * Returns the value of the element at (row,col)
     *
     * @param integer $row
     * @param integer $col
     * @return number
     * @throws MatrixException
     * @throws InvalidArgumentException
     */
    function getElement($row, $col) {
        if ($this->isEmpty()) {
            throw new MatrixException('Matrix has not been populated');
        } elseif ($row >= $this->_num_rows && $col >= $this->_num_cols) {
            throw new InvalidArgumentException('Incorrect row and column values');
        }

        return $this->_data[$row][$col];
    }


    /**
     * Sets the value of the element at (row,col)
     *
     * @param integer $row
     * @param integer $col
     * @param numeric $value
     * @return boolean
     * @throws MatrixException
     * @throws InvalidArgumentException
     */
    function setElement($row, $col, $value) {
        if ($this->isEmpty()) {
            throw new MatrixException('Matrix has not been populated');
        } elseif ($row >= $this->_num_rows && $col >= $this->_num_cols) {
            throw new InvalidArgumentException('Incorrect row and column values');
        } elseif (!is_numeric($value)) {
            throw new InvalidArgumentException('Incorrect value, expecting a number');
        }

        $this->_data[$row][$col] = $value;

        return true;
    }

    /**
     * Returns the row with the given index
     *
     * This method checks that matrix has been initialized and that the
     * row requested is not outside the range of rows.
     *
     * @param integer $row
     * @return array
     * @throws MatrixException
     * @throws InvalidArgumentException
     */
    function getRow ($row) {
        if ($this->isEmpty()) {
            throw new MatrixException('Matrix has not been populated');
        } elseif (is_integer($row) && $row >= $this->_num_rows) {
            throw new InvalidArgumentException('Incorrect row value');
        }

        return $this->_data[$row];
    }

    /**
     * Sets the row with the given index to the array
     *
     * This method checks that the row is less than the size of the matrix
     * rows, and that the array size equals the number of columns in the
     * matrix.
     *
     * @param integer $row index of the row
     * @param array $arr array of numbers
     * @return boolean
     *
     * @throws MatrixException
     * @throws InvalidArgumentException
     */
    function setRow ($row, $arr) {
        if ($this->isEmpty()) {
            throw new MatrixException('Matrix has not been populated');
        } elseif ($row >= $this->_num_rows) {
            throw new InvalidArgumentException('Row index out of bounds');
        } elseif (count($arr) != $this->_num_cols) {
            throw new InvalidArgumentException('Incorrect size for matrix row: expecting '.$this->_num_cols
                .' columns, got '.count($arr).' columns');
        }

        for ($i = 0; $i < $this->_num_cols; $i++) {
            if (!is_numeric($arr[$i])) {
                throw new InvalidArgumentException('Incorrect values, expecting numbers');
            }
        }

        $this->_data[$row] = $arr;

        return true;
    }

    /**
     * Returns the column with the given index
     *
     * This method checks that matrix has been initialized and that the
     * column requested is not outside the range of column.
     *
     * @param integer $col
     * @return array
     * @throws MatrixException
     * @throws InvalidArgumentException
     */
    function getCol ($col) {
        if ($this->isEmpty()) {
            throw new MatrixException('Matrix has not been populated');
        } elseif (is_integer($col) && $col >= $this->_num_cols) {
            throw new InvalidArgumentException('Incorrect column value');
        }

        $ret = array();

        for ($i = 0; $i < $this->_num_rows; $i++) {
            $ret[$i] = $this->getElement($i,$col);
        }

        return $ret;
    }

    /**
     * Sets the column with the given index to the array
     *
     * This method checks that the column is less than the size of the matrix
     * columns, and that the array size equals the number of rows in the
     * matrix.
     *
     * @param integer $col index of the column
     * @param array $arr array of numbers
     * @return boolean
     * @throws MatrixException
     * @throws InvalidArgumentException
     */
    function setCol ($col, $arr) {
        if ($this->isEmpty()) {
            throw new MatrixException('Matrix has not been populated');
        } elseif ($col >= $this->_num_cols) {
            throw new InvalidArgumentException('Incorrect column value');
        } elseif (count($arr) != $this->_num_cols) {
            throw new InvalidArgumentException('Incorrect size for matrix column');
        }

        for ($i = 0; $i < $this->_num_rows; $i++) {
            if (!is_numeric($arr[$i])) {
                throw new InvalidArgumentException('Incorrect values, expecting numbers');
            } else {
                $this->setElement($i, $col, $arr[$i]);
            }
        }

        return true;
    }

    /**
     * Checks if the object is a Math_Matrix instance
     *
     * @param object Matrix $matrix
     * @return boolean TRUE on success, FALSE otherwise
     */
    public static function isMatrix (&$matrix) {
        if (function_exists("is_a")) {
            return is_object($matrix) && is_a($matrix, "Matrix");
        } else {
            return is_object($matrix) && (strtolower(get_class($matrix)) == "matrix");
        }
    }

    //--------------------------------------------------------
    // Behavior.
    //--------------------------------------------------------

    /**
     * Returns a new Matrix object with the same data as the current one
     *
     * @return object Matrix
     * @throws MatrixException
     */
    function cloneMatrix() {
        if ($this->isEmpty()) {
            throw new MatrixException('Matrix has not been populated');
        } else {
            return new Matrix($this->_data);
        }
    }

    //--------------------------------------------------------
    // Binary operations.
    //--------------------------------------------------------

    /**#@+
     * @access public
     */
    /**
     * Adds a matrix to this one
     *
     * @param object Matrix $m1
     * @return boolean TRUE on success
     * @see getSize()
     * @see getElement()
     * @see setData()
     * @throws InvalidArgumentException
     * @throws MatrixException
     */
    function add(Matrix $m1) {
        if (!Matrix::isMatrix($m1)) {
            throw new InvalidArgumentException("Parameter must be a Matrix object");
        } elseif ($this->getSize() != $m1->getSize()) {
            throw new InvalidArgumentException("Matrices must have the same dimensions");
        }

        list($nr, $nc) = $m1->getSize();
        $data = array();

        for ($i = 0; $i < $nr; $i++) {
            for ($j = 0; $j < $nc; $j++) {
                $el1 = $m1->getElement($i,$j);
                $el = $this->getElement($i,$j);
                $data[$i][$j] = $el + $el1;
            }
        }

        if (!empty($data)) {
            return $this->setData($data);
        } else {
            throw new MatrixException('Undefined error');
        }
    }

    /**
     * Substracts a matrix from this one
     *
     * @param object Matrix $m1
     * @return boolean TRUE on success otherwise
     * @see getSize()
     * @see getElement()
     * @see setData()
     * @throws MatrixException
     * @throws InvalidArgumentException
     */
    function sub(Matrix &$m1) {
        if (!Matrix::isMatrix($m1)) {
            throw new InvalidArgumentException("Parameter must be a Matrix object");
        } elseif ($this->getSize() != $m1->getSize()) {
            throw new InvalidArgumentException("Matrices must have the same dimensions");
        }

        list($nr, $nc) = $m1->getSize();
        $data = array();

        for ($i = 0; $i < $nr; $i++) {
            for ($j = 0; $j < $nc; $j++) {
                $el1 = $m1->getElement($i,$j);
                $el = $this->getElement($i,$j);
                $data[$i][$j] = $el - $el1;
            }
        }

        if (!empty($data)) {
            return $this->setData($data);
        } else {
            throw new MatrixException('Undefined error');
        }
    }

    /**
     * Scales the matrix by a given factor
     *
     * @param numeric $scale the scaling factor
     * @return boolean TRUE on success
     * @throws MatrixException
     * @throws InvalidArgumentException
     * @see getSize()
     * @see getElement()
     * @see setData()
     */
    function scale($scale) {
        if (!is_numeric($scale)) {
            throw new InvalidArgumentException("Parameter must be a number");
        }

        list($nr, $nc) = $this->getSize();
        $data = array();

        for ($i = 0; $i < $nr; $i++) {
            for ($j = 0; $j < $nc; $j++) {
                $data[$i][$j] = $scale * $this->getElement($i,$j);
            }
        }

        if (!empty($data)) {
            return $this->setData($data);
        } else {
            throw new MatrixException('Undefined error');
        }
    }

    /**
     * Multiplies (scales) a row by the given factor
     *
     * @param integer $row the row index
     * @param numeric $factor the scaling factor
     * @return boolean TRUE on success
     * @throws MatrixException
     * @throws InvalidArgumentException
     * @see invert()
     */
    function scaleRow($row, $factor) {
        if ($this->isEmpty()) {
            throw new MatrixException('Uninitialized Matrix object');
        } elseif (!is_integer($row) || !is_numeric($factor)) {
            throw new InvalidArgumentException('Row index must be an integer, and factor a valid number');
        } elseif ($row >= $this->_num_rows) {
            throw new InvalidArgumentException('Row index out of bounds');
        }

        $r = $this->getRow($row);
        $nr = count($r);
        for ($i=0; $i<$nr; $i++) {
            $r[$i] *= $factor;
        }
        return $this->setRow($row, $r);
    }

    /**
     * Multiplies this matrix (A) by another one (B), and stores
     * the result back in A
     *
     * @param object Matrix $B
     * @return boolean TRUE on success
     * @see getSize()
     * @see getRow()
     * @see getCol()
     * @see setData()
     * @see setZeroThreshold()
     * @throws MatrixException
     * @throws InvalidArgumentException
     */
    function multiply(Matrix &$B) {
        if (!Matrix::isMatrix($B)) {
            throw new InvalidArgumentException('Wrong parameter, expected a Matrix object');
        }

        list($nrA, $ncA) = $this->getSize();
        list($nrB, $ncB) = $B->getSize();

        if ($ncA != $nrB) {
            throw new InvalidArgumentException('Incompatible sizes columns in matrix must be the same as rows in
            parameter matrix');
        }

        $data = array();
        for ($i = 0; $i < $nrA; $i++) {
            $data[$i] = array();
            for ($j = 0; $j < $ncB; $j++) {
                $rctot = 0;
                for ($k = 0; $k < $ncA; $k++) {
                    $rctot += $this->getElement($i,$k) * $B->getElement($k, $j);
                }

                // take care of some round-off errors
                if (abs($rctot) <= $this->_epsilon) {
                    $rctot = 0.0;
                }

                $data[$i][$j] = $rctot;
            }
        }

        if (!empty($data)) {
            return $this->setData($data);
        } else {
            throw new MatrixException('Undefined error');
        }
    }

    /**
     * Convenience static method to multiply matrices and returning the result
     * as a new matrix
     *
     * @param Matrix $m1 a Math_Matrix object
     * @param Matrix $m2 a Math_Matrix object
     * @return object Math_Matrix Math_Matrix instance on success
     * @see multiply()
     * @throws MatrixException
     * @throws InvalidArgumentException
     */
    public static function multiplyMatrices(&$m1, &$m2) {
        $mres = $m1->cloneMatrix();
        $mres->multiply($m2);

        return $mres;
    }

    //--------------------------------------------------------
    // Algorithms.
    //--------------------------------------------------------

    /**
     * Transpose the matrix rows and columns
     */
    function transpose () {
        list($nr, $nc) = $this->getSize();
        $data = array();

        for ($i = 0; $i < $nc; $i++) {
            $col = $this->getCol($i);
            $data[] = $col;
        }

        return $this->setData($data);
    }

    /**
     * Calculates the matrix determinant using Gaussian elimination with partial pivoting.
     *
     * At each step of the pivoting proccess, it checks that the normalized
     * determinant calculated so far is less than 10^-18, trying to detect
     * singular or ill-conditioned matrices
     *
     * @return number a number on success
     * @throws MatrixException
     */
    function determinant() {
        if (!is_null($this->_det) && is_numeric($this->_det)) {
            return $this->_det;
        } elseif ($this->isEmpty()) {
            throw new MatrixException('Matrix has not been populated');
        } elseif (!$this->isSquare()) {
            throw new MatrixException('Determinant undefined for non-square matrices');
        }

        $norm = $this->norm();
        $det = 1.0;
        $sign = 1;

        // Work on a copy.
        $m = $this->cloneMatrix();
        list($nr, $nc) = $m->getSize();

        for ($r = 0; $r < $nr; $r++) {
            // Find the maximum element in the column under the current diagonal element.
            $ridx = $m->_maxElementIndex($r);

            if ($ridx != $r) {
                $sign = -$sign;
                $e = $m->swapRows($r, $ridx);
            }

            // Pivoting element.
            $pelement = $m->getElement($r, $r);
            $det *= $pelement;

            // Is this an singular or ill-conditioned matrix?
            // i.e. is the normalized determinant << 1 and -> 0?
            if ((abs($det)/$norm) < $this->_epsilon) {
                throw new MatrixException('Probable singular or ill-conditioned matrix, normalized determinant = '
                    .(abs($det)/$norm));
            } elseif ($pelement == 0) {
                throw new MatrixException('Cannot continue, pivoting element is zero');
            }

            // Zero all elements in column below the pivoting element.
            for ($i = $r + 1; $i < $nr; $i++) {
                $factor = $m->getElement($i, $r) / $pelement;
                for ($j = $r; $j < $nc; $j++) {
                    $val = $m->getElement($i, $j) - $factor*$m->getElement($r, $j);
                    $e = $m->setElement($i, $j, $val);
                }
            }
        }

        unset($m);

        if ($sign < 0) {
            $det = -$det;
        }

        // Save the value.
        $this->_det = $det;

        return $det;
    }

    /**
     * Returns the normalized determinant = abs(determinant)/(euclidean norm)
     *
     * @return number a positive number on success
     * @throws MatrixException
     */
    function normalizedDeterminant() {
        $det = $this->determinant();
        $norm = $this->norm();

        if ($norm == 0) {
            throw new MatrixException('Undefined normalized determinant, euclidean norm is zero');
        }

        return abs($det / $norm);
    }

}

?>