<?php
enum AccessMode {
    case ReadOnly;
    case ReadWrite;
}

/**
 * A connection to the SQLite blog database used for posts, tags, and comments.
 */
class BlogDB {
    private PDO $db;
    private $preparedStatement;

    /**
     * Connect to the blog database using the $BLOG_DB_PATH environment variable.
     * 
     * @param AccessMode $mode determine whether to open in read-only mode or read-write mode
     * @return bool whether the database connection was successful or not
     */
    function connect(AccessMode $mode) {
        $db_location = filter_var(getenv('BLOG_DB_PATH'), FILTER_SANITIZE_URL);

        try {
            $this->db = new PDO("sqlite:$db_location");
            $this->db->query('SELECT id FROM posts LIMIT 1'); // Must use test query to see if it is actually there
        }
        catch (PDOException $e) {
            $message = $e->getMessage();
            error_log("couldn't connect to blog DB: '$message'.");
            return false;
        }

        switch ($mode) {
            case AccessMode::ReadOnly:
                $this->db->query('PRAGMA query_only=1');
                break;
            case AccessMode::ReadWrite:
                $this->db->query('PRAGMA foreign_keys=1');
                break;
        }

        return true;
    }

    /**
     * Prepare a statement for repeated use with the queryPreparedStmt() function. Useful if the only thing changing between queries is the parameters.
     * 
     * @param string $query_string the query with '?' placeholders to prepare beforehand, it is stored until it is used
     */
    function prepare(string $query_string) {
        $this->preparedStatement = $this->db->prepare($query_string);
    }

    /**
     * Query a statement prepared with prepare() with new parameters, returning an array of all the results that matched.
     * 
     * @param array $params the array of parameters to fill in the placeholders in the prepared query
     * @param int $fetch_type the fetching mode of the query, defaults to associative array
     * @return array the array of results from the query
     */
    function queryPreparedStmt(array $params, int $fetch_type = PDO::FETCH_ASSOC) {
        $this->preparedStatement->execute($params);
        return $this->preparedStatement->fetchAll($fetch_type);
    }

    /**
     * Given a query with a list of parameters for the query, return all results that match the query.
     * 
     * @param string $query_string the query with '?' placeholders to mark where parameters should be substituted
     * @param array $params the array of parameters to fill in the placeholders
     * @param int $fetch_type the fetching mode of the query, defaults to associative array
     * @return array the array of results from the query
     */
    function query(string $query_string, array $params, int $fetch_type = PDO::FETCH_ASSOC) {
        $query_stmt = $this->db->prepare($query_string);
        $query_stmt->execute($params);
        return $query_stmt->fetchAll($fetch_type);
    }

    /**
     * Given a query with a list of parameters for the query, return the first result that matches the query.
     * 
     * @param string $query_string the query with '?' placeholders to mark where parameters should be substituted
     * @param array $params the array of parameters to fill in the placeholders
     * @param int $fetch_type the fetching mode of the query, defaults to associative array
     * @return array the first result of the query
     */
    function querySingle(string $query_string, array $params, int $fetch_type = PDO::FETCH_ASSOC) {
        $query_stmt = $this->db->prepare($query_string);
        $query_stmt->execute($params);
        $output = $query_stmt->fetch($fetch_type);
        $query_stmt->closeCursor();
        return $output;
    }

    function transaction(array $queries) {
        $this->db->beginTransaction();
        try {
            foreach ($queries as [$query_string, $params]) {
                $this->query($query_string, $params);
            }
            $this->db->commit();
        } catch (Throwable $e) {
            $message = $e->getMessage();
            error_log("error during transaction: $message");
            $this->db->rollBack();
            throw $e; // pass the error to higher error handler
        }
    }
}