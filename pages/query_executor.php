<?php
require_once '../config/config.php';
require_once '../config/db_connect.php';

$page_title = 'SQL Query Executor';
$conn = getConnection();
clearQueryLog();

$result_html = '';
$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['query'])) {
    $query = trim($_POST['query']);
    
    if (!empty($query)) {
        // Security: Block dangerous operations
        $dangerous_keywords = ['DROP DATABASE', 'DROP TABLE', 'TRUNCATE', 'DELETE FROM users', 'UPDATE users'];
        $is_dangerous = false;
        
        foreach ($dangerous_keywords as $keyword) {
            if (stripos($query, $keyword) !== false) {
                $is_dangerous = true;
                break;
            }
        }
        
        if ($is_dangerous) {
            $error_message = 'This query contains potentially dangerous operations and has been blocked for safety.';
        } else {
            try {
                $result = executeQuery($conn, $query, 'User-executed custom query');
                
                if ($result === true) {
                    $success_message = 'Query executed successfully! Affected rows: ' . $conn->affected_rows;
                } elseif ($result === false) {
                    $error_message = 'Query error: ' . $conn->error;
                } else {
                    // SELECT query - display results
                    if ($result->num_rows > 0) {
                        $result_html = '<div class="result-table"><table class="data-table"><thead><tr>';
                        
                        // Get column names
                        $fields = $result->fetch_fields();
                        foreach ($fields as $field) {
                            $result_html .= '<th>' . htmlspecialchars($field->name) . '</th>';
                        }
                        $result_html .= '</tr></thead><tbody>';
                        
                        // Get rows
                        while ($row = $result->fetch_assoc()) {
                            $result_html .= '<tr>';
                            foreach ($row as $value) {
                                $result_html .= '<td>' . ($value !== null ? htmlspecialchars($value) : '<em>NULL</em>') . '</td>';
                            }
                            $result_html .= '</tr>';
                        }
                        
                        $result_html .= '</tbody></table></div>';
                        $success_message = 'Query executed successfully! Rows returned: ' . $result->num_rows;
                    } else {
                        $success_message = 'Query executed successfully! No rows returned.';
                    }
                }
            } catch (Exception $e) {
                $error_message = 'Error: ' . $e->getMessage();
            }
        }
    } else {
        $error_message = 'Please enter a SQL query.';
    }
}

include '../includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-terminal"></i> SQL Query Executor</h1>
        <p>Write and execute SQL queries to explore the database</p>
    </div>

    <!-- Query Input Section -->
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-code"></i> Write Your Query</h2>
        </div>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="sqlQuery"><i class="fas fa-keyboard"></i> SQL Query:</label>
                <textarea 
                    name="query" 
                    id="sqlQuery" 
                    class="form-control" 
                    placeholder="SELECT * FROM teams LIMIT 10;"
                    rows="10"
                    style="font-family: 'Courier New', monospace; font-size: 0.95rem;"><?php echo isset($_POST['query']) ? htmlspecialchars($_POST['query']) : ''; ?></textarea>
            </div>
            
            <div class="btn-group">
                <button type="submit" class="btn btn-success"><i class="fas fa-play"></i> Execute Query</button>
                <button type="button" class="btn btn-outline" onclick="document.getElementById('sqlQuery').value = ''"><i class="fas fa-eraser"></i> Clear</button>
            </div>
        </form>

        <div class="alert alert-warning" style="margin-top: 1rem;">
            <i class="fas fa-exclamation-triangle"></i> <strong>Note:</strong> Dangerous operations (DROP, TRUNCATE, etc.) are blocked for safety.
        </div>
    </div>

    <!-- Query Results - Shown right after query input -->
    <?php if (!empty($success_message)): ?>
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-check-circle"></i> Query Results</h3>
        </div>
        <div class="card-body">
            <div class="alert alert-success">
                <i class="fas fa-check"></i> <?php echo $success_message; ?>
            </div>
            <?php if (!empty($result_html)): ?>
                <?php echo $result_html; ?>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-exclamation-circle"></i> Error</h3>
        </div>
        <div class="card-body">
            <div class="alert alert-danger">
                <i class="fas fa-times-circle"></i> <?php echo $error_message; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Sample Queries -->
    <section class="section">
        <h2 class="section-title"><i class="fas fa-book"></i> Sample Queries</h2>
        
        <div class="grid grid-2">
            <div class="card">
                <h4><i class="fas fa-list"></i> 1. Basic SELECT</h4>
                <pre><code>SELECT * FROM teams LIMIT 10;</code></pre>
                <button class="btn btn-primary" onclick="loadQuery('SELECT * FROM teams LIMIT 10;')"><i class="fas fa-upload"></i> Load Query</button>
            </div>

            <div class="card">
                <h4><i class="fas fa-link"></i> 2. INNER JOIN</h4>
                <pre><code>SELECT m.*, 
  ht.name as home_team, 
  at.name as away_team 
FROM football_matches m 
JOIN teams ht ON m.home_team_id = ht.id 
JOIN teams at ON m.away_team_id = at.id 
LIMIT 10;</code></pre>
                <button class="btn btn-primary" onclick="loadQuery(`SELECT m.*, ht.name as home_team, at.name as away_team FROM football_matches m JOIN teams ht ON m.home_team_id = ht.id JOIN teams at ON m.away_team_id = at.id LIMIT 10;`)"><i class="fas fa-upload"></i> Load Query</button>
            </div>

            <div class="card">
                <h4><i class="fas fa-chart-bar"></i> 3. COUNT with GROUP BY</h4>
                <pre><code>SELECT t.name, 
  COUNT(p.id) as player_count 
FROM teams t 
LEFT JOIN players p ON t.id = p.team_id 
GROUP BY t.id 
ORDER BY player_count DESC;</code></pre>
                <button class="btn btn-primary" onclick="loadQuery(`SELECT t.name, COUNT(p.id) as player_count FROM teams t LEFT JOIN players p ON t.id = p.team_id GROUP BY t.id ORDER BY player_count DESC;`)"><i class="fas fa-upload"></i> Load Query</button>
            </div>

            <div class="card">
                <h4><i class="fas fa-layer-group"></i> 4. Subquery</h4>
                <pre><code>SELECT name 
FROM teams 
WHERE id IN (
  SELECT team_id 
  FROM players 
  GROUP BY team_id 
  HAVING COUNT(*) > 5
);</code></pre>
                <button class="btn btn-primary" onclick="loadQuery(`SELECT name FROM teams WHERE id IN (SELECT team_id FROM players GROUP BY team_id HAVING COUNT(*) > 5);`)"><i class="fas fa-upload"></i> Load Query</button>
            </div>

            <div class="card">
                <h4><i class="fas fa-object-group"></i> 5. UNION</h4>
                <pre><code>SELECT name, 'Team' as type 
FROM teams 
UNION 
SELECT name, 'Player' as type 
FROM players 
LIMIT 20;</code></pre>
                <button class="btn btn-primary" onclick="loadQuery(`SELECT name, 'Team' as type FROM teams UNION SELECT name, 'Player' as type FROM players LIMIT 20;`)"><i class="fas fa-upload"></i> Load Query</button>
            </div>

            <div class="card">
                <h4><i class="fas fa-calculator"></i> 6. Complex Aggregation</h4>
                <pre><code>SELECT l.name, 
  COUNT(DISTINCT t.id) as teams, 
  COUNT(DISTINCT m.id) as matches,
  AVG(m.home_score + m.away_score) as avg_goals
FROM leagues l 
LEFT JOIN teams t ON l.id = t.league_id 
LEFT JOIN football_matches m ON l.id = m.league_id 
GROUP BY l.id;</code></pre>
                <button class="btn btn-primary" onclick="loadQuery(`SELECT l.name, COUNT(DISTINCT t.id) as teams, COUNT(DISTINCT m.id) as matches, AVG(m.home_score + m.away_score) as avg_goals FROM leagues l LEFT JOIN teams t ON l.id = t.league_id LEFT JOIN football_matches m ON l.id = m.league_id GROUP BY l.id;`)"><i class="fas fa-upload"></i> Load Query</button>
            </div>

            <div class="card">
                <h4><i class="fas fa-arrows-alt-h"></i> 7. BETWEEN Clause</h4>
                <pre><code>SELECT * 
FROM football_matches 
WHERE match_date 
  BETWEEN '2024-01-01' AND '2024-12-31' 
LIMIT 10;</code></pre>
                <button class="btn btn-primary" onclick="loadQuery(`SELECT * FROM football_matches WHERE match_date BETWEEN '2024-01-01' AND '2024-12-31' LIMIT 10;`)"><i class="fas fa-upload"></i> Load Query</button>
            </div>

            <div class="card">
                <h4><i class="fas fa-search"></i> 8. LIKE Pattern Matching</h4>
                <pre><code>SELECT * 
FROM players 
WHERE name LIKE '%son%' 
LIMIT 10;</code></pre>
                <button class="btn btn-primary" onclick="loadQuery(`SELECT * FROM players WHERE name LIKE '%son%' LIMIT 10;`)"><i class="fas fa-upload"></i> Load Query</button>
            </div>

            <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <h4><i class="fas fa-eye"></i> 9. VIEW - Match Details</h4>
                <p style="margin: 0.5rem 0; opacity: 0.9;">Views encapsulate complex JOINs into reusable virtual tables</p>
                <pre style="background: rgba(0,0,0,0.2); color: white; border: 1px solid rgba(255,255,255,0.2);"><code>-- Using pre-defined VIEW instead of complex JOIN
SELECT * 
FROM v_match_details 
WHERE status = 'finished' 
LIMIT 10;</code></pre>
                <button class="btn btn-success" onclick="loadQuery(`SELECT * FROM v_match_details WHERE status = 'finished' LIMIT 10;`)"><i class="fas fa-upload"></i> Load Query</button>
            </div>

            <div class="card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                <h4><i class="fas fa-eye"></i> 10. VIEW - Player Statistics</h4>
                <p style="margin: 0.5rem 0; opacity: 0.9;">Aggregate data pre-computed for easy querying</p>
                <pre style="background: rgba(0,0,0,0.2); color: white; border: 1px solid rgba(255,255,255,0.2);"><code>-- Using VIEW for aggregated player stats
SELECT * 
FROM v_player_stats 
ORDER BY total_goals DESC 
LIMIT 10;</code></pre>
                <button class="btn btn-success" onclick="loadQuery(`SELECT * FROM v_player_stats ORDER BY total_goals DESC LIMIT 10;`)"><i class="fas fa-upload"></i> Load Query</button>
            </div>
        </div>
    </section>
</div>

<script>
function loadQuery(query) {
    document.getElementById('sqlQuery').value = query;
    document.getElementById('sqlQuery').focus();
    // Scroll to the query input
    document.getElementById('sqlQuery').scrollIntoView({ behavior: 'smooth', block: 'center' });
}
</script>

<?php
$conn->close();
include '../includes/footer.php';
?>
