<?php
  session_start();
  require_once('conn.php');

  $nickname = $_POST['nickname'];
  $username = $_POST['username'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  
  if (
    empty($nickname) ||
    empty($username) ||
    empty($_POST['password'])
  ) {
    header("Location: register.php?errCode=1");
    die();
  } 

  $sql = "INSERT INTO yuni_users (nickname,username, password) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('sss', $nickname, $username, $password);
  $result = $stmt->execute();

  // 檢查 username 有沒有重複
  if (!$result) {
    $code = $conn->errno;
    // 用 mySQL 的 error code 來比對是不是帳號已被使用
    if ($code === 1062) {
      header('Location: register.php?errCode=2');
      exit;
    }
    die($conn->error);
  } 
  $_SESSION['username'] = $username;

  header("Location: index.php");
  exit;
?>