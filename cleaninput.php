<?php
// function to clean input but not validate type and content
function cleaninput($data) {
  if ($data === NULL) {
      return NULL; // Return NULL if the input is NULL
  } else {
      return htmlspecialchars(stripslashes(trim($data)));
  }
}

?>

