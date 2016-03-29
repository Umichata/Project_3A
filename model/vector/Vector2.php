<?php

require_once "Vector.php";

/**
 * 2D Vector class
 *
 * @access  public
 */
class Math_Vector2 extends Math_Vector
{

    /**
     * Constructor for Math_Vector2
     *
     * @access  public
     * @param   mixed $arg an array of values, a Math_Tuple object or a Math_Vector2 object
     */
    public function __construct($arg)
    {
        if (is_array($arg) && count($arg) != 2) {
            $this->tuple = null;
        } elseif (is_object($arg) && (strtolower(get_class($arg)) != "math_vector2"
                && strtolower(get_class($arg)) != "math_tuple")
        ) {
            $this->tuple = null;
        } elseif (is_object($arg) && strtolower(get_class($arg)) == "math_tuple"
            && $arg->getSize() != 2
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
     * @return  mixed   true on success
     * @throws InvalidArgumentException
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
     * @return  mixed   true on success
     * @throws InvalidArgumentException
     */
    function setY($val)
    {
        return $this->set(1, $val);
    }
}
