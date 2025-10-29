    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 Playlytics - MySQL Query Showcase Project</p>
            <p>Built with HTML, CSS, JavaScript, PHP & MySQL</p>
        </div>
    </footer>

    <!-- SQL Query Viewer Modal -->
    <div id="sqlModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-database"></i> SQL Query Viewer</h2>
                <button class="close-btn" onclick="closeSQLViewer()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="flex-between" style="margin-bottom: 1.5rem;">
                    <p style="color: var(--text-secondary);"><i class="fas fa-info-circle"></i> <strong>Queries executed on this page:</strong></p>
                    <button onclick="clearSQLLog()" class="btn btn-outline"><i class="fas fa-trash"></i> Clear Log</button>
                </div>
                <div id="queryLogContainer">
                    <?php
                    if (function_exists('getQueryLog')) {
                        $queries = getQueryLog();
                        if (empty($queries)) {
                            echo '<div class="alert alert-info"><i class="fas fa-info-circle"></i> No queries executed yet on this page.</div>';
                        } else {
                            foreach ($queries as $index => $log) {
                                echo '<div class="card" style="margin-bottom: 1rem;">';
                                echo '<div class="card-header">';
                                echo '<span style="font-weight: 600; color: var(--success-color);"><i class="fas fa-code"></i> Query #' . ($index + 1) . '</span>';
                                echo '<span style="color: var(--text-muted); font-size: 0.85rem;"><i class="far fa-clock"></i> ' . $log['timestamp'] . '</span>';
                                echo '</div>';
                                echo '<div class="card-body">';
                                
                                // File location
                                if (isset($log['file']) && isset($log['line'])) {
                                    echo '<div style="margin-bottom: 0.75rem; padding: 0.5rem; background: var(--dark-bg); border-left: 3px solid var(--info-color); border-radius: 4px;">';
                                    echo '<i class="fas fa-file-code"></i> <strong>Location:</strong> <code style="color: var(--info-color);">' . htmlspecialchars($log['file']) . '</code> <strong>Line:</strong> <code style="color: var(--warning-color);">' . $log['line'] . '</code>';
                                    echo '</div>';
                                }
                                
                                if (!empty($log['description'])) {
                                    echo '<div style="margin-bottom: 0.75rem; color: var(--text-secondary); font-style: italic;"><i class="fas fa-comment-alt"></i> ' . htmlspecialchars($log['description']) . '</div>';
                                }
                                
                                echo '<pre style="margin: 0;"><code>' . htmlspecialchars($log['query']) . '</code></pre>';
                                
                                if (isset($log['params']) && !empty($log['params'])) {
                                    echo '<div style="margin-top: 0.75rem; padding: 0.5rem; background: var(--dark-bg); border-radius: 4px; font-size: 0.9rem;">';
                                    echo '<i class="fas fa-cog"></i> <strong>Parameters:</strong> <code style="color: var(--warning-color);">' . htmlspecialchars(json_encode($log['params'])) . '</code>';
                                    echo '</div>';
                                }
                                
                                echo '</div>';
                                echo '</div>';
                            }
                        }
                    } else {
                        echo '<div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> Query logging not initialized.</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo BASE_URL; ?>assets/js/main.js"></script>
    <script>
        // Update query count in floating button
        document.addEventListener('DOMContentLoaded', function() {
            const queryCount = <?php echo function_exists('getQueryLog') ? count(getQueryLog()) : 0; ?>;
            const countElement = document.getElementById('queryCount');
            if (countElement) {
                countElement.textContent = queryCount;
            }
        });
    </script>
</body>
</html>
