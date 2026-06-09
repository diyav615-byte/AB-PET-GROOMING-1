<?php
session_start();
include "config/db.php";
require_once "admin/includes/ActivityLogger.php";

$logger = getActivityLogger($conn);
$_SESSION['admin_id'] = 1;

// Test log
$result = $logger->log(1, 'TEST_ACTION', 'test_entity', 123, ['old' => 'data'], ['new' => 'data']);
echo "Log result: " . ($result ? 'SUCCESS' : 'FAILED') . "\n";

// Test delete
$result = $logger->delete('test_table', 1, ['old' => 'data']);
echo "Delete log result: " . ($result ? 'SUCCESS' : 'FAILED') . "\n";

// Test create
$result = $logger->create('test_table', 456, ['name' => 'test']);
echo "Create log result: " . ($result ? 'SUCCESS' : 'FAILED') . "\n";

// Test update
$result = $logger->update('test_table', 123, ['old' => 'data'], ['new' => 'data']);
echo "Update log result: " . ($result ? 'SUCCESS' : 'FAILED') . "\n";