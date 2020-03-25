<?php
require('dbconnect.php');

$sjis = file_get_contents('./sistan.csv');
$utf8 = mb_convert_encoding($sjis, 'UTF-8', 'SJIS-win');
file_put_contents('./utf8.csv', $utf8);

$f = fopen("./utf8.csv", "r");

while($line = fgetcsv($f)){
    //重複チェック
    $id = $db->prepare('SELECT count(*) AS cnt FROM mytable WHERE id=?');
    $id->execute(array($line[0]));
    $count = $id->fetch();
    //重複していなかったらインサート
    if ($count['cnt'] === '0') {
        $statement = $db->prepare('INSERT INTO mytable SET id=?, enwords=?, jpwords=?');
        $statement->execute(array($line[0], $line[1], $line[2]));
    }
}
fclose($f);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

</body>
</html>
