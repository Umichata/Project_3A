<?php

/**
 * General Tuple class
 *
 * A Tuple represents a general unidimensional list of n numeric elements
 */
class Math_Tuple
{
    /**
     * array of numeric elements
     *
     * @var     array
     * @access  private
     */
    var $data = null;

    /**
     * Constructor of Math_Tuple
     *
     * @param   array $data array of numbers
     */
    public function __construct($data)
    {
        if (is_array($data) || !is_array($data[0])) {
            $this->data = $data;
        } else {
            throw new InvalidArgumentException("An unidimensional array is needed to initialize a Tuple");
        }
    }

    /**
     * Squeezes out holes in the tuple sequence
     *
     * @return  void
     */
    public function squeezeHoles()
    {
        $this->data = array_values($this->data);
    }

    /**
     * Returns the size (number of elements) in the tuple
     *
     * @return  integer
     */
    public function getSize()
    {
        return count($this->data);
    }

    /**
     * Sets the value of an element
     *
     * @param   integer $elindex element index
     * @param   numeric $elvalue element value
     *
     * @return  mixed   true if successful
     * @throws InvalidArgumentException
     */
    public function setElement($elindex, $elvalue)
    {
        if ($elindex >= $this->getSize()) {
            throw new InvalidArgumentException("Wrong index: $elindex for element: $elvalue");
        }
        $this->data[$elindex] = $elvalue;
        return true;
    }

    /**
     * Appends an element to the tuple
     *
     * @param   numeric $elvalue element value
     * @return  mixed   index of appended element on success
     */
    public function addElement($elvalue)
    {
        if (!is_numeric($elvalue)) {
            throw new InvalidArgumentException("Error, a numeric value is needed. You used: $elvalue");
        }
        $this->data[$this->getSize()] = $elvalue;
        return ($this->getSize() - 1);
    }

    /**
     * Remove an element from the tuple
     *
     * @param   integer $elindex element index
     *
     * @return mixed   true on success
     * @throws InvalidArgumentException
     */
    public function delElement($elindex)
    {
        if ($elindex >= $this->getSize()) {
            throw new InvalidArgumentException("Wrong index: $elindex, element not deleted");
        }
        unset($this->data[$elindex]);
        $this->squeezeHoles();
        return true;
    }

    /**
     * Returns the value of an element in the tuple
     *
     * @access  public
     * @param   integer $elindex element index
     * @return  mixed   numeric on success
     * @throws InvalidArgumentException
     */
    public function getElement($elindex)
    {
        if ($elindex >= $this->getSize()) {
            throw new InvalidArgumentException("Wrong index: $elindex, Tuple size is: " . $this->getSize());
        }
        return $this->data[$elindex];
    }

    /**
     * Returns an array with all the elements of the tuple
     *
     * @return  $array
     */
    public function getData()
    {
        $this->squeezeHoles();
        return $this->data;
    }

    /**
     * Returns the minimum value of the tuple
     *
     * @access  public
     * @return  numeric
     */
    public function getMin()
    {
        return min($this->getData());
    }

    /**
     * Returns the maximum value of the tuple
     *
     * @return  numeric
     */
    public function getMax()
    {
        return max($this->getData());
    }

    /**
     * Returns an array of the minimum and maximum values of the tuple
     *
     * @access  public
     * @return  array of the minimum and maximum values
     */
    public function getMinMax()
    {
        return array($this->getMin(), $this->getMax());
    }

    /**
     * Gets the position of the given value in the tuple
     *
     * @param   numeric $val value for which the index is requested
     * @return  integer
     */
    public function getValueIndex($val)
    {
        for ($i = 0; $i < $this->getSize(); $i++) {
            if ($this->data[$i] == $val) {
                return $i;
            }
        }
        return false;
    }

    /**
     * Gets the position of the minimum value in the tuple
     *
     * @return  integer
     */
    public function getMinIndex()
    {
        return $this->getValueIndex($this->getMin());
    }

    /**
     * Gets the position of the maximum value in the tuple
     *
     * @return  integer
     */
    public function getMaxIndex()
    {
        return $this->getValueIndex($this->getMax());
    }

    /**
     * Gets an array of the positions of the minimum and maximum values in the tuple
     *
     * @return  array of integers indexes
     */
    public function getMinMaxIndex()
    {
        return array($this->getMinIndex(), $this->getMaxIndex());
    }

    /**
     * Checks if the tuple is a a Zero tuple
     *
     * @return  boolean
     */
    public function isZero()
    {
        for ($i = 0; $i < $this->getSize(); $i++) {
            if ($this->data[$i] != 0) {
                return false;
            }
        }
        return true;
    }

    /**
     * Returns an string representation of the tuple
     *
     * @access  public
     * @return  string
     */
    public function toString()
    {
        return "{ " . implode(", ", $this->data) . " }";
    }

    /**
     * Returns an string representation of the tuple
     *
     * @access  public
     * @return  string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * Returns an HTML representation of the tuple
     *
     * @access  public
     * @return  string
     */
    public function toHTML()
    {
        $out = "<table border>\n\t<caption align=\"top\"><b>Vector</b></caption>\n";
        $out .= "\t<tr align=\"center\">\n\t\t<th>i</th><th>value</th>\n\t</tr>\n";
        for ($i = 0; $i < $this->getSize(); $i++) {
            $out .= "\t<tr align=\"center\">\n\t\t<th>" . $i . "</th>";
            $out .= "<td bgcolor=\"#dddddd\">" . $this->data[$i] . "</td>\n\t</tr>\n";
        }
        return $out . "\n</table>\n";
    }
}
