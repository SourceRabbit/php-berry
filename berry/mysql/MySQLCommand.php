<?php

class MySQLCommand
{

    private MySQLConnection $fMySQLConnection;  // MySQLConnection of this command
    private String $fQuery;                     // Query to execute
    private $fPreparedStatement = NULL;         // The prepared statement of this command
    public MySQLCommandParameters $Parameters;  // Command parameters

    /**
     * Constructs a new MySQLCommand
     * @param MySQLConnection $mySQLConnection is the MySQLConnection
     * @param string $query the query to execute
     */
    public function __construct(MySQLConnection $mySQLConnection, String $query = "")
    {
        $this->fMySQLConnection = $mySQLConnection;
        $this->Parameters = new MySQLCommandParameters();
        $this->fQuery = $query;
    }

    /**
     * Execute query and return the number of affected rows.
     * @return the number of affected rows.
     */
    public function ExecuteQuery(): int
    {
        if (sizeof($this->Parameters->getParameters()) > 0) // Query with parameters (Prepared Statement)
        {
            $this->fPreparedStatement = $this->fMySQLConnection->getLink()->stmt_init();

            if (!$this->fPreparedStatement->prepare($this->fQuery))
            {
                throw new Exception("ExecuteQuery failed to prepare statement: (" . $this->fMySQLConnection->getLink()->errno . ") " . $this->fMySQLConnection->getLink()->error);
            }
            else
            {
                // Prepared statement created.
                // Bind parameters and execute
                $this->BindParametersToQuery();
                if (!$this->fPreparedStatement->execute())
                {
                    throw new Exception("Execute query failed: (" . $this->fMySQLConnection->getLink()->errno . ") " . $this->fMySQLConnection->getLink()->error);
                }
                else
                {
                    return $this->fMySQLConnection->getLink()->affected_rows;
                }
            }
        }
        else // Straight mySQL Query withour parameters
        {
            if (!$this->fMySQLConnection->getLink()->query($this->fQuery))
            {
                throw new Exception("Execute query failed: (" . $this->fMySQLConnection->getLink()->errno . ") " . $this->fMySQLConnection->getLink()->error);
            }
            else
            {
                return $this->fMySQLConnection->getLink()->affected_rows;
            }
        }
    }

    /**
     * Execute reader command.
     * Returns MySQLDataReader
     * @return MySQLDataReader 
     */
    public function ExecuteReader(): MySQLDataReader
    {
        $this->fPreparedStatement = NULL;

        if (sizeof($this->Parameters->getParameters()) > 0) // Query with parameters (Prepared Statement)
        {
            $this->fPreparedStatement = $this->fMySQLConnection->getLink()->stmt_init();

            if (!$this->fPreparedStatement->prepare($this->fQuery))
            {
                throw new Exception("ExecuteReader failed to prepare statement: (" . $this->fMySQLConnection->getLink()->errno . ") " . $this->fMySQLConnection->getLink()->error);
            }
            else
            {
                // Prepared statement created.
                // Bind parameters and execute reader
                $this->BindParametersToQuery();
                if (!$this->fPreparedStatement->execute())
                {
                    throw new Exception("Execute reader failed: (" . $this->fMySQLConnection->getLink()->errno . ") " . $this->fMySQLConnection->getLink()->error);
                }

                return new MySQLDataReader($this->fPreparedStatement);
            }
        }
        else // Simple query withour prepared statement and parameters
        {
            if (!$result = $this->fMySQLConnection->getLink()->query($this->fQuery))
            {
                throw new Exception("Execute reader failed: (" . $this->fMySQLConnection->getLink()->errno . ") " . $this->fMySQLConnection->getLink()->error);
            }
            else
            {
                return new MySQLDataReader($result);
            }
        }
    }

    /**
     * Sets a new query for the MySQLCommand
     * $query: is the query to set
     * @param type $query 
     */
    public function setQuery($query): void
    {
        $this->fQuery = $query;
    }

    /**
     * Return's the query been given to this command
     * @return string the command's query 
     */
    public function getQuery(): string
    {
        return $this->fQuery;
    }

    /**
     * Returns the MySQLConnection of this MySQLCommand
     * @return type MySQLConnection
     */
    public function getMySQLConnection(): MySQLConnection
    {
        return $this->fMySQLConnection;
    }

    /**
     * Return's the last inserted ID
     * @return type 
     */
    public function getLastInsertID(): int
    {
        return $this->fMySQLConnection->getLink()->insert_id;
    }

    /**
     * Bind parameter values to query.
     * This method should be called every time before query execution.
     * @return type 
     */
    private function BindParametersToQuery(): void
    {
        $parameterTypes = "";
        $ar = array();

        foreach ($this->Parameters->getParameters() as $key => $value)
        {
            $parameterTypes .= $value->Type;
        }
        $ar[] = $parameterTypes;

        foreach ($this->Parameters->getParameters() as $key => $value)
        {
            $ar[] = &$value->Value;
        }

        call_user_func_array(array($this->fPreparedStatement, 'bind_param'), $ar);
    }

}

?>