<?php

class MySQLDataReader
{

    private $fResult = null;
    private $fReadValues;
    private $fResultIsPreparedStatement = false;
    private $fResultVariables = array();
    private $fResultKeys = array();

    /**
     * Constructs a new data reader
     * @param type $result 
     */
    public function __construct($result = null)
    {
        $this->fResult = $result;

        if ($this->fResult instanceof mysqli_stmt)
        {
            $this->fResultIsPreparedStatement = true;
            $this->fResult->store_result();

            $this->fResultVariables = array();
            $data = array();
            $meta = $this->fResult->result_metadata();

            while ($field = $meta->fetch_field())
            {
                $this->fResultVariables[] = &$this->fResultKeys[$field->name]; // pass by reference
            }

            call_user_func_array(array($this->fResult, 'bind_result'), $this->fResultVariables);
        }
    }

    /**
     * Read next value in MySQLDataReader
     * @return type 
     */
    public function Read()
    {
        if ($this->fResultIsPreparedStatement) // Prepared statement
        {
            if ($this->fResult->fetch())
            {
                $this->fReadValues = $this->fResultVariables;
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            if ($this->fReadValues = $this->fResult->fetch_row())
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }

    /**
     * Close the reader
     */
    public function Close()
    {
        $this->fResult->close();
    }

    /**
     * Return a readed value
     * @param type $index
     * @return type 
     */
    public function getValue($index)
    {
        return $this->fReadValues[$index];
    }

}

?>