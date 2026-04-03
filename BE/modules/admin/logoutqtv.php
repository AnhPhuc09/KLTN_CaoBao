<?php
session_start();

session_unset();

session_destroy();

header("Location: ?module=admin&action=loginqtv");
exit;
?>