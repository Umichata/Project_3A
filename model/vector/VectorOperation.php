<?php

/**
 * Vector operation class.
 * A static class implementing methods to operate on Vector objects.
 *
 * @access  public
 */
class Math_VectorOp {
    /**
     * Checks if object is of Math_Vector class (or a subclass of Math_Vector)
     *
     * @param   object $obj
     * @return  boolean true on success
     */
    public static function isVector(Math_Vector $obj) {
        if (function_exists("is_a")) {
            return (is_object($obj) && is_a($obj, "Math_Vector"));
        }
        return (is_object($obj) && (strtolower(get_class($obj)) == "math_vector" ||
                is_subclass_of($obj, "Math_Vector")));
    }

    /**
     * Checks if object is of Math_Vector2 class (or a subclass of Math_Vector2)
     *
     * @param   object $obj
     * @return  boolean true on success
     */
    public static function isVector2(Math_Vector $obj) {
        if (function_exists("is_a")) {
            return (is_object($obj) && is_a($obj, "Math_Vector2"));
        }
        return (is_object($obj) && (strtolower(get_class($obj)) == "math_vector2" ||
                is_subclass_of($obj, "Math_Vector2")));
    }

    /**
     * Checks if object is of Math_Vector3 class (or a subclass of Math_Vector3)
     *
     * @param   object $obj
     *
     * @return  boolean true on success
     */
    public static function isVector3(Math_Vector $obj) {
        if (function_exists("is_a")) {
            return (is_object($obj) && is_a($obj, "Math_Vector3"));
        }
        return (is_object($obj) && (strtolower(get_class($obj)) == "math_vector3" ||
                is_subclass_of($obj, "Math_Vector3")));
    }

    /**
     * Creates a vector of a given size in which all elements have the same value
     *
     * @param   int $size vector size
     * @param   numeric $value value to assign to the elements
     * @return  object  if ($size == 2) Math_Vector2 elseif ($size == 3) Math_Vector3 else Math_Vector
     */
    public static function create($size, $value) {
        if ($size == 2) {
            $VClass = "Math_Vector2";
        } elseif ($size == 3) {
            $VClass = "Math_Vector3";
        } else {
            $VClass = "Math_Vector";
        }
        return new $VClass(Math_VectorOp::_fill(0, $size, $value));
    }

    /**
     * Creates a zero-filled vector of the given size
     *
     * @param   int $size vector size
     * @return  object  if ($size == 2) Math_Vector2 elseif ($size == 3) Math_Vector3 else Math_Vector
     *
     * @see create()
     */
    public static function createZero($size) {
        return Math_VectorOp::create($size, 0);
    }

    /**
     * Creates a one-filled vector of the given size
     *
     * @param   int $size vector size
     * @return  object  if ($size == 2) Math_Vector2 elseif ($size == 3) Math_Vector3 else Math_Vector
     *
     * @see create()
     */
    public static function createOne($size) {
        return Math_VectorOp::create($size, 1);
    }

    /**
     * Creates a basis vector of the given size
     * A basis vector of size n, has n - 1 elements equal to 0
     * and one element equal to 1
     *
     * @param   int $size vector size
     * @param   int $index element to be set at 1
     * @return  object  if ($size == 2) Math_Vector2 elseif ($size == 3) Math_Vector3 else Math_Vector
     * @throws InvalidArgumentException
     *
     * @see createZero()
     */
    public static function createBasis($size, $index) {
        if ($index >= $size) {
            throw new InvalidArgumentException("Incorrect index for size: $index >= $size");
        }
        $v = Math_VectorOp::createZero($size);
        $res = $v->set($index, 1);
        return $v;
    }

    /**
     * Vector addition
     * v + w = <v1 + w1, v2 + w2, ..., vk + wk>
     *
     * @param   Math_Vector $v1
     * @param   Math_Vector $v2
     * @return  Math_Vector on success
     *
     * @throws InvalidArgumentException
     * @see     isVector()
     */
    public static function add(Math_Vector $v1, Math_Vector $v2) {
        if (Math_VectorOp::isVector($v1) && Math_VectorOp::isVector($v2)) {
            $n = $v1->size();
            if ($v2->size() != $n) {
                throw new InvalidArgumentException("Vectors must of the same size");
            }
            for ($i = 0; $i < $n; $i++) {
                $arr[$i] = $v1->get($i) + $v2->get($i);
            }
            return new Math_Vector($arr);
        }
        throw new InvalidArgumentException("V1 and V2 must be Math_Vector objects");
    }

    /**
     * Vector substraction
     * v - w = <v1 - w1, v2 - w2, ..., vk - wk>
     *
     * @param   Math_Vector (or subclass) $v1
     * @param   Math_Vector (or subclass) $v2
     * @return  Math_Vector (or subclass) on success
     * @throws InvalidArgumentException
     *
     * @see     isVector()
     */
    public static function substract($v1, $v2) {
        if (Math_VectorOp::isVector($v1) && Math_VectorOp::isVector($v2)) {
            $n = $v1->size();
            if ($v2->size() != $n) {
                throw new InvalidArgumentException("Vectors must of the same size");
            }

            $arr = array();
            for ($i = 0; $i < $n; $i++) {
                $arr[$i] = $v1->get($i) - $v2->get($i);
            }

            return new Math_Vector($arr);
        }

        throw new InvalidArgumentException("V1 and V2 must be Math_Vector objects");
    }

    /**
     * Vector multiplication
     * v * w = <v1 * w1, v2 * w2, ..., vk * wk>
     *
     * @param   Math_Vector $v1
     * @param   Math_Vector $v2
     * @return  object  Math_Vector (or subclass) on success
     * @throws InvalidArgumentException
     *
     * @see     isVector()
     */
    public function multiply(Math_Vector $v1, Math_Vector $v2) {
        if (Math_VectorOp::isVector($v1) && Math_VectorOp::isVector($v2)) {
            $n = $v1->size();
            if ($v2->size() != $n) {
                throw new InvalidArgumentException("Vectors must of the same size");
            }

            $arr = array();
            for ($i = 0; $i < $n; $i++) {
                $arr[$i] = $v1->get($i) * $v2->get($i);
            }

            return new Math_Vector($arr);
        }

        throw new InvalidArgumentException("V1 and V2 must be Math_Vector objects");
    }

    /**
     * Vector scaling
     * f * w = <f * w1, f * w2, ..., f * wk>
     *
     * @param   numeric $f scaling factor
     * @param   object  Math_Vector (or subclass)   $v
     * @return  object  Math_Vector (or subclass) on success
     * @throws InvalidArgumentException
     *
     * @see     isVector()
     */
    public static function scale($f, $v) {
        if (is_numeric($f) && Math_VectorOp::isVector($v)) {
            $arr = array();
            $n = $v->size();

            for ($i = 0; $i < $n; $i++) {
                $arr[$i] = $v->get($i) * $f;
            }

            return new Math_Vector($arr);
        } else {
            throw new InvalidArgumentException("Requires a numeric factor and a Math_Vector object");
        }
    }

    /**
     * Vector division
     * v / w = <v1 / w1, v2 / w2, ..., vk / wk>
     *
     * @param   object  Math_Vector (or subclass)   $v1
     * @param   object  Math_Vector (or subclass)   $v2
     * @return  object  Math_Vector (or subclass) on success
     * @throws InvalidArgumentException
     *
     * @see     isVector()
     */
    public static function divide($v1, $v2) {
        if (Math_VectorOp::isVector($v1) && Math_VectorOp::isVector($v2)) {
            $n = $v1->size();
            if ($v2->size() != $n) {
                throw new InvalidArgumentException("Vectors must of the same size");
            }

            $arr = array();
            for ($i = 0; $i < $n; $i++) {
                $d = $v2->get($i);
                if ($d == 0) {
                    throw new Math_Vector_Exception("Division by zero: Element $i in V2 is zero");
                }
                $arr[$i] = $v1->get($i) / $d;
            }

            return new Math_Vector($arr);
        } else {
            throw new InvalidArgumentException("V1 and V2 must be Math_Vector objects");
        }
    }

    /**
     * Vector dot product = v . w = |v| |w| cos(theta)
     *
     * @param   object  Math_Vector2 or MathVector3 (or subclass)   $v1
     * @param   object  Math_Vector2 or MathVector3 (or subclass)   $v2
     * @return  mixed   the dot product (float) on success
     * @throws InvalidArgumentException
     *
     * @see     isVector2()
     * @see     isVector3()
     */
    public static function dotProduct($v1, $v2) {
        if (Math_VectorOp::isVector2($v1) && Math_VectorOp::isVector2($v2)) {
            return ($v1->getX() * $v2->getX() +
                $v1->getY() * $v2->getY());
        } elseif (Math_VectorOp::isVector3($v1) && Math_VectorOp::isVector3($v2)) {
            return ($v1->getX() * $v2->getX() +
                $v1->getY() * $v2->getY() +
                $v1->getZ() * $v2->getZ());
        }
        throw new InvalidArgumentException("Vectors must be both of the same type");
    }

    /**
     * Vector cross product = v x w
     *
     * @param   object  Math_Vector3 (or subclass)  $v1
     * @param   object  Math_Vector3 (or subclass)  $v2
     * @return  object  the cross product vector (Math_Vector3) on success
     * @throws InvalidArgumentException
     *
     * @see     isVector3()
     */
    public static function crossProduct($v1, $v2) {
        if (Math_VectorOp::isVector3($v1) && Math_VectorOp::isVector3($v2)) {
            $arr[0] = $v1->getY() * $v2->getZ() - $v1->getZ() * $v2->getY();
            $arr[1] = $v1->getZ() * $v2->getX() - $v1->getX() * $v2->getZ();
            $arr[2] = $v1->getX() * $v2->getY() - $v1->getY() * $v2->getX();

            return new Math_Vector3($arr);
        } else {
            throw new InvalidArgumentException("Vectors must be both of the same type");
        }
    }

    /**
     * Vector triple scalar product =  v1 . (v2 x v3)
     *
     * @param   object  Math_Vector3 (or subclass)  $v1
     * @param   object  Math_Vector3 (or subclass)  $v2
     * @param   object  Math_Vector3 (or subclass)  $v3
     * @return  mixed   the triple scalar product (float) on success
     * @throws InvalidArgumentException
     *
     * @see     isVector3()
     * @see     dotProduct()
     * @see     crossProduct()
     */
    public static function tripleScalarProduct($v1, $v2, $v3) {
        if (Math_VectorOp::isVector3($v1)
            && Math_VectorOp::isVector3($v2)
            && Math_VectorOp::isVector3($v3)
        ) {
            return Math_VectorOp::dotProduct($v1, Math_VectorOp::crossProduct($v2, $v3));
        }
        throw new InvalidArgumentException("All three vectors must be of the same type");
    }

    /**
     * Angle between vectors, using the equation: v . w = |v| |w| cos(theta)
     *
     * @param   object  Math_Vector2 or MathVector3 (or subclass)   $v1
     * @param   object  Math_Vector2 or MathVector3 (or subclass)   $v2
     * @return  mixed   the angle between vectors (float, in radians) on success
     * @throws InvalidArgumentException
     *
     * @see     isVector2()
     * @see     isVector3()
     * @see     dotProduct()
     */
    public static function angleBetween($v1, $v2) {
        if ((Math_VectorOp::isVector2($v1) && Math_VectorOp::isVector2($v2)) ||
            (Math_VectorOp::isVector3($v1) && Math_VectorOp::isVector3($v2))
        ) {
            $v1->normalize();
            $v2->normalize();

            return acos(Math_VectorOp::dotProduct($v1, $v2));
        }

        throw new InvalidArgumentException("Vectors must be both of the same type");
    }

    /**
     * To generate an array of a given size filled with a single value
     * If available uses array_fill()
     *
     * @access  private
     * @param   int $index starting index
     * @param   int $size size of the array
     * @param   numeric $value value to use for filling the array
     * @return  array
     */
    static function _fill($index, $size, $value) {
        if (function_exists("array_fill")) {
            return array_fill($index, $size, $value);
        }

        $arr = array();
        for ($i = $index; $i < ($index + $size); $i++) {
            $arr[$i] = $value;
        }

        return $arr;
    }
}
