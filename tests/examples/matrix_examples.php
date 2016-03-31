<?php
/**
 * Example of using the Matrix class
 */

// Path to /matrix/model/Matrix.php
require_once(realpath(dirname(__FILE__) . "/../" . "/../" . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR
    . "matrix" . DIRECTORY_SEPARATOR . "Matrix.php"));

$data = array(
    array(1.0, 2.0, 3.0, 4.0),
    array(5.0, 6.0, 7.0, 8.0),
    array(1.0, 4.0, 5.0, 7.0),
    array(2.0, 3.0, -3.0, 4.0)
);

$m = new Math_Matrix($data);
echo $m->toHTML() . "<br>";
$det = $m->determinant();
echo "Determinant = $det<br>";
echo "Trace = " . $m->trace() . "<br>";
echo "Euclidean Norm = " . $m->norm() . "<br>";
echo "Normalized Determinant = " . $m->normalizedDeterminant() . "<br>";

echo "<br>Submatrix(1,1,2,2)<br>";
$n = $m->getSubMatrix(1, 1, 2, 2);
echo $n->toHTML("Submatrix") . "<br>";
$det = $n->determinant();
echo "Determinant = $det<br>";
echo "Euclidean Norm = " . $n->norm() . "<br>";
echo "Normalized Determinant = " . $n->normalizedDeterminant() . "<br>";

echo $m->toHTML() . "<br>";
echo "Product of matrix<br>";
$q = $m->cloneMatrix();
$q->scale(5);
$q->multiply($m);
echo $q->toHTML() . "<br>";

$Adata = array(
    array(1, 1, 2),
    array(2, 3, 4)
);

$Bdata = array(
    array(-1, 3),
    array(-3, 4),
    array(-5, 2)
);

$A = new Math_Matrix($Adata);
$A1 = $A->cloneMatrix();
$B = new Math_Matrix($Bdata);
$B1 = $B->cloneMatrix();

$A1->multiply($B1);
$B->multiply($A);
echo $A1->toHTML() . "<br>";
echo $B->toHTML() . "<br>";

echo "<br>Solving Ax = b<br>";
$a = Math_Matrix::readFromFile('data.mat', 'csv');
echo "<br>A<br>" . $a->toHTML("Matrix A") . "<br>";

$b = new Math_Vector(range(1, 9));
$x = Math_Matrix::solve($a, $b);
echo "<br>A^-1<br>" . $a->toHTML("Matrix A^-1") . "<br>";
echo "B " . $b->toString() . "<br>";
echo "Solution " . $x->toString() . "<br>";

echo "<br>Solving with error correction<br>";
$x = Math_Matrix::solveEC($a, $b);
echo "EC Solution " . $x->toString() . "<br>";
