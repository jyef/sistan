<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<!-- <?php
try {
    $db = new PDO('mysql:dbname=mydb;host=db;charset=utf8','root','root');
} catch (PDOException $e) {
    echo 'DB接続エラー： ' . $e->getMessage();
}

$count = $db->exec('INSERT INTO test SET name="幸子"');
echo $count . '件のデータを挿入しました';
?> -->
<?php
$sjis = file_get_contents('./sistan.csv');
$utf8 = mb_convert_encoding($sjis, 'UTF-8', 'SJIS-win');
file_put_contents('./utf8.csv', $utf8);

$f = fopen("./utf8.csv", "r");

while($line = fgetcsv($f)){
    var_dump($line);
}
fclose($f);
?>
</body>
</html>
