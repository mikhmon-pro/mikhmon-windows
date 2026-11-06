<?php
if (!isset($_SESSION["mikhmon"])) {
    header("Location:../admin.php?id=login");
  } else {
        $_SESSION["v"] = "3.21.2 11-06-2026";
    
    }
