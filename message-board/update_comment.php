<?php
  session_start();
  require_once('conn.php');
  require_once('utils.php');
  
  $id = $_GET['id'];

  $username = NULL;
  $user = NULL;
  if (!empty($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $user = getUserFromUsername($username);
    $nickname = $user['nickname'];
  }

  $stmt = $conn->prepare(
    'SELECT * FROM yuni_comments WHERE id = ?'
  );
  $stmt->bind_param('i', $id);
  $result= $stmt->execute();
  if(!$result) {
    die('Error:' . $conn_error);
  }
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
?> 

<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>留言板</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <div class="wrapper">
      本站為練習使用，註冊時請勿使用任何真實的帳號密碼！！！
    </div>
  </header>
  <main>
    <div class="wrapper">
      <section class="sendMessage">
        <!-- alert input-related error message -->
        <?php
          if(!empty($_GET['errCode'])){
            $code = $_GET['errCode'];
            $msg = 'Error';
            if($code === '1'){
              $msg = '不能送出空白內容，請輸入留言';
            }
            echo '<h4 class="error">'. $msg .'</h4>';
          }
        ?>
        <!-- 留言輸入區 -->
        <?php
          if($username) {
        ?>
          <h4>Hi <?php echo $nickname;?></h4>
          <h3>編輯留言</h3>

          <form method="POST" action="handle_update_comment.php">
            <!-- textarea 顯示原本的留言 -->
            <textarea name="message" cols="30" rows="5"><?php echo escape($row['comment'])?></textarea>

            <!-- 加上隱藏的 input 欄位，用來把 id 帶到下一個頁面（handle_update_comment.php） -->
            <input type="hidden" name="id" value="<?php echo escape($row['id'])?>">
            <input class="inputBtn" type="submit" value="送 出">
          </form>
        <?php
          } else {
        ?>
          <h4>登入後就可以留言喔</h4>
        <?php
          }
        ?>
      </section>
    </div>
  </main>
</body>
</html>