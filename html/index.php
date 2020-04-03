<?php
session_start();
require('dbconnect.php');

if (isset($_POST['action']) && $_POST['action'] == 'importcsv') {
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
    $message['import'] = 'インポートが完了しました';
}

if (isset($_GET['action']) && $_GET['action'] == 'inputnum') {
    if (!empty($_GET['start']) && !empty($_GET['end']) && is_numeric($_GET['start']) && is_numeric($_GET['end'])) {
        $start = (int) $_GET['start'];
        $end = (int) $_GET['end'];
        if ($start > $end) {
            $error['diff'] = '※不正な範囲です';
        }
    } else {
        $error['num'] = '※数値を入力してください';
    }

    if (empty($error)) {
        unset($_SESSION['join']);
        $_SESSION['join']['start'] = $start;
        $_SESSION['join']['end'] = $end;
        header('Location: question.php');
        exit();
    }
}

if (isset($_SESSION['join'])) {
    $start = $_SESSION['join']['start'];
    $end = $_SESSION['join']['end'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form action="" method="post">
        <input type="hidden" name="action" value="importcsv">
        <button type="submit">csvインポート</button>
    </form>
    <?php $message['import'] ? print '<p>'.$message['import'].'</p>' : '' ; ?>

    <form action="" method="get">
        <p>出題範囲を整数で入力してください</p>
        <input type="hidden" name="action" value="inputnum">
        <input type="text" name="start"> 〜 <input type="text" name="end">
        <input type="submit" value="送信する">
    </form>
    <?php $error['num'] ? print '<p class="warn">'.$error['num'].'</p>' : '' ; ?>
    <?php $error['diff'] ? print '<p class="warn">'.$error['diff'].'</p>' : '' ; ?>
    <p>
        <?php if (isset($start)): ?>
        現在セットされている出題範囲：<?php print($start); ?> 〜 <?php print($end); ?><br>
        <?php endif ?>
        <a href="question.php">チェック</a>
         | <a href="tempreview.php">短期復習</a>
         | <a href="permreview.php">長期復習</a>
         | <a href="isolation.php">隔離</a>
    </p>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        $('.words').on('click', function(){
            var index = $('.words').index(this);
            var word = $('.words').eq(index);
            word.toggleClass('one');
        });
    </script>
</body>
</html>
