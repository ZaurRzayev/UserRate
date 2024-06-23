<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=laravel', 'root', '1975');
    echo "Connected successfully!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
