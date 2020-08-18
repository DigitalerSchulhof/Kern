<?php
if (isset($DSH_BENUTZER)) {
  if ($DSH_BENUTZER !== null) {
    $DSH_BENUTZER->abmelden();
  }
}
?>
