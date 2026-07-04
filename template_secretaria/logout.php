<?php
// remove all session variables
session_unset();

// destroy the session
session_destroy();

echo "<script>
  window.open('../loginSecre.html',target='_self');</script>";

?>