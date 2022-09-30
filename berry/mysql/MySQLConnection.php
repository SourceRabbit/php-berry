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

class MySQLConnection
{

    private $fLink;    // The mysqli link with the selected MySQL database

    /**
     * Constructs a new MySQLConnection
     * @param string $serverIP is the mySQL's server IP
     * @param string $database is the database to connect
     * @param string $user is a user with permissions for the selected database
     * @param string $password is the user's password
     * @param string $charset is the the database charset
     */
    public function __construct($serverIP, $database, $user, $password, $charset = "")
    {
        $this->fLink = new mysqli($serverIP, $user, $password, $database);

        if ($this->fLink->connect_errno)
        {
            throw new Exception("Failed to connect to MySQL: (" . $this->fLink->connect_errno . ") " . $this->fLink->connect_error);
        }

        if ($charset != "")
        {
            $this->fLink->set_charset($charset);
        }
    }

    /**
     * Escapes special characters in a string for use in an SQL statement
     * @param string $string is the string to escape
     * @return string string
     */
    public function EscapeString($string)
    {
        return mysql_real_escape_string($string, $this->fLink);
    }

    /**
     * Close MySQLConnection
     */
    public function Close()
    {
        $this->fLink->close();
    }

    /**
     * Returns the mysqli link with selected MySQL database.
     * @return mysqli 
     */
    public function getLink()
    {
        return $this->fLink;
    }

}

?>
