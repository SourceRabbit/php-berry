<?php

/*
  MIT License

  Copyright (c) 2022 Nikos Siatras

  Permission is hereby granted, free of charge, to any person obtaining a copy
  of this software and associated documentation files (the "Software"), to deal
  in the Software without restriction, including without limitation the rights
  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
  copies of the Software, and to permit persons to whom the Software is
  furnished to do so, subject to the following conditions:

  The above copyright notice and this permission notice shall be included in all
  copies or substantial portions of the Software.

  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
  SOFTWARE.
 */

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
