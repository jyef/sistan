<?php
session_start();
require('dbconnect.php');

if (!isset($_SESSION['join'])) {
    header('Location: index.php');
    exit();
}

if(isset($_POST['false'])){
    foreach($_POST['false'] as $k => $v){
        if ($v == 'off') {
            $statement = $db->prepare('UPDATE mytable SET iso=false WHERE id=?');
            $statement->execute(array($k));
        }
    }
}

$start = $_SESSION['join']['start'];
$end = $_SESSION['join']['end'];

$words = $db->prepare('SELECT * FROM mytable WHERE id BETWEEN ' . $start . ' AND ' . $end . ' AND iso = true ORDER BY RAND()');
$words->execute();
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
    <p>
        <?php print($start); ?> 〜 <?php print($end); ?>
         | <a href="index.php">ホーム</a>
         | <a href="question.php">チェック</a>
         | <a href="tempreview.php">短期復習</a> 
         | <a href="permreview.php">長期復習</a>
         | 隔離
    </p>

    <div class="questiontarea">
        <form action="" method="post">
            <table class="questiontable">
                <?php while ($word = $words->fetch()): ?>
                <tr>
                    <td class="enwords">
                        <?php print($word['enwords']); ?>
                    </td>
                    <td class="jpwords">
                        <div class="jpcheck">
                            <input type="hidden" name="false[<?php print($word['id']); ?>]" value="off">  
                            <input type="checkbox" name="false[<?php print($word['id']); ?>]" value="on" checked>
                        </div>
                        <div class="jpcontents">
                            <div style="padding: 5px;">
                                <?php print($word['jpwords']); ?>
                            </div>
                        </div>
                    </td>
                    <td class="id">
                        <?php print($word['id']); ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
            <button class="questionsubmit">送信する</button>
        </form>
    </div>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    $('.enwords').on('click', function(){
        var index = $('.enwords').index(this);
        var words = document.getElementsByClassName('enwords');
        var word = words[index].textContent;
        let u = new SpeechSynthesisUtterance();
        u.lang = 'en-US';
        u.text = word;
        speechSynthesis.speak(u);
    });

    $('.jpwords').mouseover(function(){
        var index = $('.jpwords').index(this);
        var word = $('.jpcontents').eq(index);
        word.addClass('disp');
    });

    $('.jpcontents').on('click', function(){
        var index = $('.jpcontents').index(this);
        var word = $('.jpcontents').eq(index);
        word.toggleClass('disp');
    });

$(function(){
    var row = 35;

    $('html').keyup(function(e){
        switch(e.which){
            case 65: // Key[a]
                $l = $('.jpwords').length;
                for (var i = 0; i < $l; i++){
                    var word = $('.jpcontents').eq(i);
                    word.removeClass('disp');
                }
            break;
        }
    });

    $('html').mousemove(function(e){
        var sy = e.screenY;
        var py = e.pageY;

        $('html').keyup(function(e){
            switch(e.which){
                case 88: // Key[x]
                    $(window).scrollTop(0);
                break;
                case 90: // Key[z]
                    $(window).scrollTop(py - row);
                break;
            }
        });
    });
});
</script>
</body>
</html>