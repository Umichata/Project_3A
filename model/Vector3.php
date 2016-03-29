<?php

require_once "Vector.php";

/**
 * 3D vector class
 *
 * @access  public
 */
class Math_Vector3 extends Math_Vector
{

    /**
     * Constructor for Math_Vector3
     *
     * @access  public
     * @param   mixed $arg an array of values, a Math_Tuple object or a Math_Vector3 object
     */
    public function __construct($arg)
    {
        if (is_array($arg) && count($arg) != 3) {
            $this->tuple = null;
        } elseif (is_object($arg) && (strtolower(get_class($arg)) != "math_vector3"
                && strtolower(get_class($arg)) != "math_tuple")
        ) {
            $this->tuple = null;
        } elseif (is_object($arg) && strtolower(get_class($arg)) == "math_tuple"
            && $arg->getSize() != 3
        ) {
            $this->tuple = null;
        } else {
            parent::__construct($arg);
        }
    }

    /**
     * Returns the X component of the vector
     *
     * @access  public
     * @return  numeric
     */
    function getX()
    {
        return $this->get(0);
    }

    /**
     * Sets the X component of the vector
     *
     * @access  public
     * @param   numeric $val the value for the Y component
     * @return  mixed   true on success, PEAR_Error object otherwise
     */
    function setX($val)
    {
        return $this->set(0, $val);
    }

    /**
     * Returns the Y component of the vector
     *
     * @access  public
     * @return  numeric
     */
    function getY()
    {
        return $this->get(1);
    }

    /**
     * Sets the Y component of the vector
     *
     * @access  public
     * @param   numeric $val the value for the Y component
     * @return  mixed   true on success, PEAR_Error object otherwise
     */
    function setY($val)
    {
        return $this->set(1, $val);
    }

    /**
     * Returns the Z component of the vector
     *
     * @access  public
     * @return  numeric
     */
    function getZ()
    {
        return $this->get(2);
    }

    /**
     * Sets the Z component of the vector
     *
     * @access  public
     * @param   numeric $val the value for the Y component
     * @return  mixed   true on success, PEAR_Error object otherwise
     */
    function setZ($val)
    {
        return $this->set(2, $val);
    }
}
