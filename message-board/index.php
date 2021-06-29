<?php
  session_start();
  require_once('conn.php');
  require_once('utils.php');
  
  $username = NULL;
  $user = NULL;
  if (!empty($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $user = getUserFromUsername($username);
    $nickname = $user['nickname'];
  }

  $page = 1;
  if (!empty($_GET['page'])) {
    $page = intval($_GET['page']);
  }
  $limit = 5;
  $offset = ($page - 1) * $limit;

  $stmt = $conn->prepare(
    'SELECT 
      C.id as id,
      C.comment as content,
      C.created_at as created_at,
      U.nickname as nickname, 
      U.username as username 
    FROM
      yuni_comments as C  
    LEFT JOIN
      yuni_users as U 
    ON 
      C.username = U.username 
    WHERE 
      C.is_deleted IS NULL 
    ORDER BY 
      C.id 
    DESC 
    LIMIT ? OFFSET ?'
  );
  $stmt->bind_param('ii', $limit, $offset);
  $result= $stmt->execute();
  if(!$result) {
    die('Error:' . $conn_error);
  }
  $result = $stmt->get_result();
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
        <!-- 登入註冊區 -->
        <div class="memberLogin">
          <?php if (!$username) {?>
              <a class="inputBtn" href="login.php">登 入</a>
              <a class="inputBtn" href="register.php">註 冊</a>
          <?php } else {?>
              <a class="inputBtn" href="logout.php">登 出</a>
              <div class="inputBtn update-nickname">編輯暱稱</div>  
              <?php if (isAdmin($user)) {?>
                <a class="inputBtn" href="role_backstage.php">管理後台</a> 
              <?php }?>
          <?php }?>
        </div>

        <!-- 輸入框錯誤處理 -->
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
        <?php if ($username && !hasPermission($user, 'create', NULL)) {?>
          <h4>Hi <?php echo $nickname;?></h4>
          <h4>您已被停權</h4>
        <?php } else if ($username) {?>
          <form class="hide nickname-form" method="POST" action="handle_update_nickname.php">
            <div class="registerInfo">
              <label for="新的暱稱">新的暱稱：</label>
              <input type="text" name="nickname">
            </div>
            <input class="inputBtn" type="submit" value="送 出">
          </form>

          <h4>Hi <?php echo $nickname;?></h4>
          
          <form method="POST" action="handle_add_post.php">
            <textarea placeholder="請輸入想說的話..." name="message" cols="30" rows="5"></textarea>
            <input class="inputBtn" type="submit" value="送 出">
          </form>
        <?php } else {?>
          <h4>登入後就可以留言喔</h4>
        <?php }?>

        <h3>Comments</h3>
      </section>
        
      <div class="horizonLine"></div>

      <!-- 顯示留言區 -->
      <section class="messageList">
        <?php while($row = $result->fetch_assoc()) {?>
          <div class="messageItem">
            <div class="userAvatar"></div>
            <div class="messageBody">
              <div class="userInfo">
                <div class="username">
                  <?php echo escape($row['nickname']);?>
                  (@<?php echo escape($row['username']);?>)
                </div>
                <div class="timeStamp">
                  <?php echo escape($row['created_at']);?>
                </div>
              </div>
              <div class="messageContent">
                <?php echo escape($row['content']);?>
              </div>
              <div class="editContent">
                <?php if (hasPermission($user, 'update', $row)) { ?>
                  <a href="update_comment.php?id=<?php echo escape($row['id']);?>">編輯</a>
                  <a href="handle_delete_comment.php?id=<?php echo escape($row['id']);?>">刪除</a>
                <?php }?>
              </div>
            </div>
          </div>
        <?php }?>
      </section>
      <!-- 分頁功能 -->
      <?php
        $stmt = $conn->prepare(
          'SELECT count(id) as count FROM yuni_comments WHERE is_deleted
          is NULL' 
        );
        $result = $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $count = $row['count'];
        $total_page = ceil($count / $limit);
      ?>
      <div class="page-info">
        <span>總共有<?php echo $count ?>筆資料</span>
        <span><?php echo $page ?> / <?php echo $total_page ?></span>
      </div>
      <div class="paginator">        
        <?php if($page !== 1) {?>
          <a href="index.php?page=1">首頁</a>
          <a href="index.php?page=<?php echo $page - 1 ?>">上一頁</a>
        <?php }?>
        <?php if($page != $total_page) {?>
          <a href="index.php?page=<?php echo $page + 1 ?>">下一頁</a>
          <a href="index.php?page=<?php echo $total_page ?>">最後一頁</a>
        <?php }?>
      </div>
    </div>
  </main>
  <script>
    const btn = document.querySelector('.update-nickname')
    btn.addEventListener('click', function(){
      const form = document.querySelector('.nickname-form')
      form.classList.toggle('hide')
    })
  </script>
</body>
</html>