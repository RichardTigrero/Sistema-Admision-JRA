<?php
// remove all session variables
session_unset();

// destroy the session
session_destroy();

echo "<script>
  window.open('../loginPro.html',target='_self');</script>";

?>