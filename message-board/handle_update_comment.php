<?php
  session_start();
  require_once('conn.php');
  require_once('utils.php');

  if(
    empty($_POST['message'])
  ) {
    header('Location: update_comment.php?errCode=1&id=' . escape($_POST['id']));
    die ('資料不齊全');
  }

  $username = $_SESSION['username'];
  $user = getUserFromUsername($username);
  $id = $_POST['id'];
  $content = $_POST['message'];

  if (isAdmin($user)) {
    $sql = 'UPDATE yuni_comments SET comment=? WHERE id=?';
  } else {
    $sql = 'UPDATE yuni_comments SET comment=? WHERE id=? and username=?';
  }
  $stmt = $conn->prepare($sql);
  if (isAdmin($user)) {
    $stmt->bind_param('si', $content, $id);
  } else {
    $stmt->bind_param('sis', $content, $id, $username);
  }
  $result = $stmt->execute();

  if(!$result) {
    die($conn->error);
  }
  
  header('Location: index.php');
  exit;
?>