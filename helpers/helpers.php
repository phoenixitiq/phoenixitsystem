<?php
function checkDatabaseConnection($host, $dbname, $user, $pass) {
    try {
        new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function createDatabase($host, $dbname, $user, $pass) {
    try {
        $pdo = new PDO("mysql:host=$host", $user, $pass);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` 
                    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        return true;
    } catch (PDOException $e) {
        return false;
    }
} 