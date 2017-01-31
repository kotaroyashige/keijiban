<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>PHP TEST</title>
</head>

<p>掲示板</p>
<body>


<form method="POST" action="<?php print($_SERVER['PHP_SELF']) ?>">
    名前<br>
    <input type="text" name="name"><br><br>
    120文字未満で入力してください。<br>
    <textarea name="contents" rows="3" cols="40">
</textarea>

    <br><br>
    <input type="submit" name="btn1" value="投稿する">
</form>
<table border="1">
    <tr>
        <th>投稿ナンバー</th><th>名前</th><th>投稿内容</th>
    </tr>

    <?php

    $a = "contents";
    if(mb_strlen($a)<120) {

        $dsn = 'mysql:dbname=keijiban; host=127.0.0.1';
        $user = 'root';
        $password = '';

        try {

            $db = new PDO($dsn, $user, $password);

            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                if (!empty($_POST["name"]) && !empty($_POST["contents"])) {
                    $stt = $db->prepare('INSERT INTO keijiban(name,contents) VALUES (:name,:contents )');
                    $stt->bindValue(':name', $_POST["name"]);
                    $stt->bindValue(':contents', $_POST["contents"]);
                    $stt->execute();
                } else if (empty($_POST["name"]) || empty($_POST["contents"])) {
                    echo "空欄を入力してください";
                }

            }
            $stt1 = $db->prepare('SELECT * FROM keijiban ORDER BY id DESC');
            $stt1->execute();
            while ($result = $stt1->fetch(PDO::FETCH_ASSOC)) {
                ?>

                <tr>
                    <td><?php echo htmlspecialchars($result['id'], ENT_QUOTES, "UTF-8"); ?></td>
                    <td><?php echo htmlspecialchars($result['name'], ENT_QUOTES, "UTF-8"); ?></td>
                    <td><?php echo htmlspecialchars($result['contents'], ENT_QUOTES, "UTF-8"); ?></td>
                </tr>


                <?php

            }
            $db = NULL;

        } catch (PDOException $e) {
            die("データベース接続失敗:{$e->getMessage()}");

        }
    }
    else if (mb_strlen($a)>119) {
        echo $_SERVER["REQUEST_URI"];
    };
    ?>

</table>
</body>
</html>
