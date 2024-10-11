<?php

class MySQLConnection
{

    private mysqli $fLink; // The mysqli link with the selected MySQL database
    private bool $fConnectionIsOpen = false;

    /**
     * Constructs a new MySQLConnection
     * @param string $serverIP is the mySQL's server IP
     * @param string $database is the database to connect
     * @param string $user is a user with permissions for the selected database
     * @param string $password is the user's password
     * @param string $charset is the the database charset
     */
    public function __construct(string $serverIP, string $database, string $user, string $password, string $charset = "")
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
        $this->fConnectionIsOpen = true;
    }

    /**
     * Escapes special characters in a string for use in an SQL statement, 
     * taking into account the current charset of the connection.
     * @param string $string is the string to escape
     * @return the escaped string
     */
    public function EscapeString(string $string): string
    {
        return mysqli_real_escape_string($this->fLink, $string);
    }

    /**
     * Closes this MySQLConnection
     */
    public function Close(): void
    {
        if ($this->fConnectionIsOpen)
        {
            $this->fLink->close();
            $this->fConnectionIsOpen = false;
        }
    }

    /**
     * Returns the mysqli link with selected MySQL database.
     * @return mysqli 
     */
    public function getLink(): mysqli
    {
        return $this->fLink;
    }
}

?>