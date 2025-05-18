<?php
session_start();
session_destroy();
header('Location: ../publico/index.php');
exit();
?>