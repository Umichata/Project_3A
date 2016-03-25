<?php
/**
 * Example of using the Matrix class
 */

// Path to /matrix/model/Matrix.php
require_once (realpath(dirname(__FILE__). "/../". "/../". DIRECTORY_SEPARATOR. "model". DIRECTORY_SEPARATOR. "Matrix.php"));

$data = array(
    array(1.0,2.0,3.0,4.0),
    array(5.0,6.0,7.0,8.0),
    array(1.0,4.0,5.0,7.0),
    array(2.0,3.0,-3.0,4.0)
);

$m = new Matrix($data);
echo $m->toString()."<br>";
$det = $m->determinant();
echo "Determinant = $det<br>";
echo "Euclidean Norm = ".$m->norm()."<br>";
echo "Normalized Determinant = ".$m->normalizedDeterminant()."<br>";

echo $m->toString()."<br>";
echo "Product of matrix<br>";
$q = $m->cloneMatrix();
$q->scale(5);
$q->multiply($m);
echo $q->toString('%4.3f')."<br>";

$Adata = array(
    array(1,1,2),
    array(2,3,4)
);

$Bdata = array(
    array(-1,3),
    array(-3,4),
    array(-5,2)
);

$A = new Matrix($Adata);
$A1 = $A->cloneMatrix();
$B = new Matrix($Bdata);
$B1 = $B->cloneMatrix();

$A1->multiply($B1);
$B->multiply($A);
echo $A1->toString()."<br>";
echo $B->toString()."<br>";

?>