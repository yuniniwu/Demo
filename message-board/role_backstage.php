<?php
  session_start();
  require_once('conn.php');
  require_once('utils.php');
  
  $user = NULL;
  $username = NULL;
  if (!empty($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $user = getUserFromUsername($username);
  }
  // 驗證 user 有沒有管理員的權限
  if ($user === NULL || $user['role'] !== 'ADMIN') {
    header('Location: index.php');
    exit;
  }
?> 

<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>後台管理</title>
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
        <p>目前使用帳號：<?php echo escape($user['username'])?></p>
        <!-- 登入註冊區 -->
        <div class="memberLogin">
            <a class="inputBtn" href="index.php">回留言區</a>
            <a class="inputBtn" href="logout.php">登 出</a>
        </div>
      </section>
      <!-- 顯示 user 區 -->
      <section class="userList">
        <table class="userTable">
          <tr>
            <th>id</th>
            <th>username</th>
            <th>nickname</th>
            <th>目前權限</th>
            <th>調整權限</th>
          </tr>
          <?php
            $stmt = $conn->prepare(
              'SELECT * FROM yuni_users ORDER BY id ASC'
            );
            $result = $stmt->execute();
            if(!$result) {
              die('Error:' . $conn_error);
            }
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
          ?>
          <tr>
            <td><?php echo escape($row['id'])?></td>
            <td><?php echo escape($row['username'])?></td>
            <td><?php echo escape($row['nickname'])?></td>
            <td>
              <?php 
                if ($row['role'] === 'NORMAL') {
                  echo "一般使用者";
                }
                if ($row['role'] === 'BANNED') {
                  echo "已停權";
                }
                if ($row['role'] === 'ADMIN') {
                  echo "管理員";
                } 
              ?>
            </td>
            <td class="tdBtn">
              <?php
                if ($row['role'] === 'NORMAL') {
              ?>
                <a href="handle_update_role.php?role=ADMIN&id=<?php echo escape($row['id']);?>">管理員</a>
                <a href="handle_update_role.php?role=BANNED&id=<?php echo escape($row['id']);?>">停權</a>
              <?php
                } else if ($row['role'] === 'BANNED') { 
              ?>
                <a href="handle_update_role.php?role=ADMIN&id=<?php echo escape($row['id']);?>">管理員</a>
                <a href="handle_update_role.php?role=NORMAL&id=<?php echo escape($row['id']);?>">一般</a>
              <?php
                } else if ($row['role'] === 'ADMIN') { 
              ?>
                <a href="handle_update_role.php?role=BANNED&id=<?php echo escape($row['id']);?>">停權</a>
                <a href="handle_update_role.php?role=NORMAL&id=<?php echo escape($row['id']);?>">一般</a>
              <?php
                }
              ?>
            </td>
          </tr>
          <?php
            }
          ?>
        </table>
      </section>
    </div>
  </main>
</body>
</html>