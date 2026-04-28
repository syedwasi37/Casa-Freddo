<?php
/**
 * Casa Freddo - Database Configuration
 * Connects to MySQL database using MySQLi
 */

// Database credentials
$host = 'localhost';
$dbname = 'casa_freddo';
$username = 'root';
$password = ''; // Default XAMPP password is empty

// Create MySQLi connection
$mysqli = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

// Set charset to utf8mb4
$mysqli->set_charset("utf8mb4");

// Create a PDO-like wrapper for compatibility
class MySQLiWrapper {
    private $mysqli;
    
    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
    }
    
    public function query($sql) {
        $result = $this->mysqli->query($sql);
        if ($result === false) {
            throw new Exception("Query failed: " . $this->mysqli->error);
        }
        return new QueryResult($result);
    }
    
    public function prepare($sql) {
        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {
            throw new Exception("Prepare failed: " . $this->mysqli->error);
        }
        return new PreparedStatement($stmt, $this->mysqli);
    }
}

class QueryResult {
    private $result;
    
    public function __construct($result) {
        $this->result = $result;
    }
    
    public function fetchAll() {
        return $this->result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function fetch() {
        return $this->result->fetch_assoc();
    }
}

class PreparedStatement {
    private $stmt;
    private $mysqli;
    
    public function __construct($stmt, $mysqli) {
        $this->stmt = $stmt;
        $this->mysqli = $mysqli;
    }
    
    public function execute($params = []) {
        if (!empty($params)) {
            // Build type string
            $types = '';
            foreach ($params as $param) {
                if (is_int($param)) {
                    $types .= 'i';
                } elseif (is_float($param)) {
                    $types .= 'd';
                } elseif (is_string($param)) {
                    $types .= 's';
                } else {
                    $types .= 's';
                }
            }
            // Bind parameters
            $this->stmt->bind_param($types, ...$params);
        }
        
        if (!$this->stmt->execute()) {
            throw new Exception("Execute failed: " . $this->stmt->error);
        }
    }
    
    public function fetchAll() {
        $result = $this->stmt->get_result();
        if ($result === false) {
            throw new Exception("Get result failed: " . $this->stmt->error);
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function fetch() {
        $result = $this->stmt->get_result();
        if ($result === false) {
            throw new Exception("Get result failed: " . $this->stmt->error);
        }
        return $result->fetch_assoc();
    }
}

$pdo = new MySQLiWrapper($mysqli);
?>

