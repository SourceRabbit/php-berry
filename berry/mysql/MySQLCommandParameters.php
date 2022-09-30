<?php

class MySQLCommandParameters
{

    private $fParameters = null;

    public function __construct()
    {
        $this->fParameters = array();
    }

    /**
     *
     * @param type $parameter
     * @param type $type 
     */
    private function Add($paramIndex, $value, $type, $length = NULL)
    {
        $parameter = new MySQLCommandParameter($paramIndex, $value, $type, $length);
        $this->fParameters[$paramIndex] = $parameter;
    }

    /**
     * Adds or sets a string parameter.
     * @param type $paramIndex
     * @param type $value
     * @param type $length 
     */
    public function setString($paramIndex, $value, $length = NULL)
    {
        $this->Add($paramIndex, $value, "s", $length);
    }

    /**
     * Adds or sets an integer parameter.
     * @param type $paramIndex
     * @param type $value
     * @param type $length 
     */
    public function setInteger($paramIndex, $value, $length = NULL)
    {
        $this->Add($paramIndex, $value, "i", $length);
    }

    /**
     * Adds or sets a double parameter.
     * @param type $paramIndex
     * @param type $value
     * @param type $length 
     */
    public function setDouble($paramIndex, $value, $length = NULL)
    {
        $this->Add($paramIndex, $value, "d", $length);
    }

    /**
     * Adds or sets a blob parameter.
     * @param type $paramIndex
     * @param type $value
     * @param type $length 
     */
    public function setBlob($paramIndex, $value, $length = NULL)
    {
        $this->Add($paramIndex, $value, "b", $length);
    }

    /**
     * Remove all parameters
     */
    public function Clear()
    {
        $this->fParameters = array();
    }

    public function getParameters()
    {
        return $this->fParameters;
    }

}

?>
