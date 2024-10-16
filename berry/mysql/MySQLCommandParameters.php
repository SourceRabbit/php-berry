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
    private function Add(int $paramIndex, $value, string $type, $length = NULL): void
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
    public function setString(int $paramIndex, string $value, $length = NULL): void
    {
        $this->Add($paramIndex, $value, "s", $length);
    }

    /**
     * Adds or sets an integer parameter.
     * @param type $paramIndex
     * @param type $value
     * @param type $length 
     */
    public function setInteger(int $paramIndex, int $value, $length = NULL): void
    {
        $this->Add($paramIndex, $value, "i", $length);
    }

    /**
     * Adds or sets a double parameter.
     * @param type $paramIndex
     * @param type $value
     * @param type $length 
     */
    public function setDouble(int $paramIndex, float $value, $length = NULL): void
    {
        $this->Add($paramIndex, $value, "d", $length);
    }

    /**
     * Adds or sets a blob parameter.
     * @param type $paramIndex
     * @param type $value
     * @param type $length 
     */
    public function setBlob(int $paramIndex, $value, $length = NULL): void
    {
        $this->Add($paramIndex, $value, "b", $length);
    }

    /**
     * Remove all parameters
     */
    public function Clear(): void
    {
        $this->fParameters = array();
    }

    public function getParameters(): array
    {
        return $this->fParameters;
    }

}

?>