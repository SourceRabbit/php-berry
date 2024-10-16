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
     * Return a read value
     * @param type $index
     * @return type 
     */
    public function getValue(int $index)
    {
        return $this->fReadValues[$index];
    }

    /**
     * DATETIME is always stored and returned as UTC, regardless of what your system's timezone is.
     * This method reads a MySQL DATETIME value into a PHP DateTime Object and converts the TimeZone
     * to the given TimeZone.
     * @param int $index
     * @param DateTimeZone $timeZone 
     * @return DateTime
     */
    public function getDateTime(int $index, DateTimeZone $timeZone): DateTime
    {
        $dt = new DateTime($this->fReadValues[$index], new DateTimeZone('UTC'));
        $dt->setTimezone($timeZone);
        return $dt;
    }

    /**
     * Return the read datetime value
     * @param int $index
     * @return DateTimeF
     */
    public function getDateTimeUTC(int $index): DateTime
    {
        $dt = new DateTime($this->fReadValues[$index]);
        return $dt;
    }
}

?>