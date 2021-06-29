<?php
  session_start();
  require_once("conn.php");
  require_once("utils.php");

  if (
    empty($_GET['id']) ||
    empty($_GET['role'])
  ) {
    header('Location: role_backstage.php');
    exit;
  }

  $username = $_SESSION['username'];
  $user = getUserFromUsername($username);
  $id = $_GET['id'];
  $role = $_GET['role'];

  // 驗證 user 有沒有管理員的權限
  if ($user === NULL || $user['role'] !== 'ADMIN') {
    header('Location: index.php');
    exit;
  }

  $sql = "UPDATE `yuni_users` SET role=? WHERE id=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('si', $role ,$id);
  $result = $stmt->execute();
  if(!$result) {
    die($conn->error);
  }
  
  header("Location: role_backstage.php");
  exit;
?>