<?php

class MySQLCommandParameter
{

    public int $Index;
    public $Value;
    public string $Type;
    public $Length;

    public function __construct(int $paramIndex, $value, string $type, $length = NULL)
    {
        $this->Index = $paramIndex;
        $this->Value = $value;
        $this->Type = $type;
        $this->Length = $length;
    }

}

?>