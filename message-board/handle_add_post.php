<?php
  session_start();
  require_once("conn.php");
  require("utils.php");

  $username = NULL;
  $user = NULL;
  if (!empty($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $user = getUserFromUsername($username);
  }

  // 檢查有沒有資料
  if (empty($_POST["message"])) {
    header("Location: index.php?errCode=1");
    die ('資料不齊全');
  }
  $content = $_POST["message"];
  $username = $_SESSION['username'];

  // 檢查權限
  if (!hasPermission($user, 'create', NULL)) {
    header("Location: index.php");
    exit;
  }

  $sql = "INSERT INTO yuni_comments (comment, username) VALUES (?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ss', $content, $username);
  $result = $stmt->execute();
  if(!$result) {
    die($conn->error);
  }

  header("Location: index.php");
  exit;
?>