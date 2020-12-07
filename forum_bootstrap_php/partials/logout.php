<?php
session_start();
echo 'Logout anda telah berhasil !';

session_destroy();
header ("Location: /forum_bootstrap_php/index.php");
?>