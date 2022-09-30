<?php

class MySQLCommandParameter
{

    public $Index;
    public $Value;
    public $Type;
    public $Length;

    public function __construct($paramIndex, $value, $type, $length = NULL)
    {
        $this->Index = $paramIndex;
        $this->Value = $value;
        $this->Type = $type;
        $this->Length = $length;
    }

}

?>