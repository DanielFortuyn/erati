<?php
header("Content-Type: text/javascript");
echo $this->data['object']->jsonEncode();
?>