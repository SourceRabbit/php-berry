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

class MySQLCommandParameters
{

    private $fParameters;
    private MySQLCommand $fMyCommand;

    public function __construct(MySQLCommand $myCommand)
    {
        $this->fParameters = array();
        $this->fMyCommand = $myCommand;
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
