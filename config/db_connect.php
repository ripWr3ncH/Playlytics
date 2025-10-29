<?php
// ==============================================================
// PLAYLYTICS - Database Connection
// ==============================================================

require_once 'config.php';

// Global variable to store last executed query for SQL viewer
$GLOBALS['last_query'] = '';
$GLOBALS['query_log'] = [];

// Create connection
function getConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    return $conn;
}

// Execute query and log it for SQL viewer
function executeQuery($conn, $query, $description = '') {
    $GLOBALS['last_query'] = $query;
    
    // Get the file that called this function
    $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
    $caller_file = isset($backtrace[0]['file']) ? basename($backtrace[0]['file']) : 'unknown';
    $caller_line = isset($backtrace[0]['line']) ? $backtrace[0]['line'] : 0;
    
    // Add to query log with description and file location
    $GLOBALS['query_log'][] = [
        'query' => $query,
        'description' => $description,
        'file' => $caller_file,
        'line' => $caller_line,
        'timestamp' => date('Y-m-d H:i:s')
    ];
    
    $result = $conn->query($query);
    
    if (!$result && $conn->error) {
        error_log("Query Error: " . $conn->error . " | Query: " . $query);
    }
    
    return $result;
}

// Execute prepared statement and log it
function executePrepared($conn, $query, $types = '', $params = [], $description = '') {
    $GLOBALS['last_query'] = $query . " | Params: " . json_encode($params);
    
    // Get the file that called this function
    $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
    $caller_file = isset($backtrace[0]['file']) ? basename($backtrace[0]['file']) : 'unknown';
    $caller_line = isset($backtrace[0]['line']) ? $backtrace[0]['line'] : 0;
    
    $GLOBALS['query_log'][] = [
        'query' => $query,
        'params' => $params,
        'description' => $description,
        'file' => $caller_file,
        'line' => $caller_line,
        'timestamp' => date('Y-m-d H:i:s')
    ];
    
    $stmt = $conn->prepare($query);
    
    if ($types && !empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    return $stmt;
}

// Get query log for SQL viewer
function getQueryLog() {
    return $GLOBALS['query_log'];
}

// Clear query log
function clearQueryLog() {
    $GLOBALS['query_log'] = [];
}

// Test connection
$test_conn = getConnection();
if ($test_conn) {
    // Connection successful
    $test_conn->close();
}
?>
