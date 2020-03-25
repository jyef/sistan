<?php
try {
    $db = new PDO('mysql:dbname=sistan;host=db;charset=utf8','root','root');
} catch(PDOException $e) {
    echo 'DB接続エラー: ' . $e->getMessage();
}