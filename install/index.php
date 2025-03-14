<?php
session_start();
require_once __DIR__ . '/Install.php';

$step = $_GET['step'] ?? 1;
$installer = new \Install\Install();

switch ($step) {
    case 1:
        // Requirements check
        $requirements = $installer->checkRequirements();
        include 'templates/requirements.php';
        break;
        
    case 2:
        // Database configuration
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $installer->setDatabaseConfig($_POST);
            header('Location: ?step=3');
            exit;
        }
        include 'templates/database.php';
        break;
        
    case 3:
        // System configuration
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $installer->setSystemConfig($_POST);
            header('Location: ?step=4');
            exit;
        }
        include 'templates/system.php';
        break;
        
    case 4:
        // Installation
        $result = $installer->install();
        include 'templates/finish.php';
        break;
}
