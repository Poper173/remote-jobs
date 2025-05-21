<?php
// filepath: /var/www/html/jobsearch/includes/log_action.php

require_once 'db.php';

/**
 * Log an action to the audit_logs table.
 *
 * @param int $user_id The ID of the user performing the action.
 * @param string $action The description of the action performed.
 */
function log_action($user_id, $action) {
    global $pdo;

    $stmt = $pdo->prepare("INSERT INTO audit_logs (user_id, action) VALUES (?, ?)");
    $stmt->execute([$user_id, $action]);
}