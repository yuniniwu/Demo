<?php
  require_once("conn.php");

  function getUserFromUsername($username) {
    global $conn;
    $sql = "SELECT * FROM yuni_users WHERE username = ?"
    ;
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $result = $stmt->execute();
    if(!$result) {
      die('Error:' . $conn_error);
    }
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row; //username, id, nickname, role
  }

  function escape($str) {
    return htmlspecialchars($str, ENT_QUOTES);
  }

  // $action: update(including delete), create
  function hasPermission($user, $action, $comment) {
    if ($user === NULL) {
      return;
    }
    // 管理員
    if ($user['role'] === 'ADMIN') { 
      return true;
    }
    // 一般使用者
    if ($user['role'] === 'NORMAL') { 
      if ($action === 'create') return true;
      return $comment['username'] === $user['username'];
    }
    // 被停權的使用者
    if ($user['role'] === 'BANNED') { 
      /* 
          return $action !== 'create'; 
          寫上面這樣的話，會出現 bug: 被停權的使用者的視角中，所有留言都有編輯刪除功能。
      */
      if ($action === 'create') return false;
      return $comment['username'] === $user['username'];
    }
  }

  function isAdmin($user) {
    return $user['role'] === 'ADMIN';
  }

?>