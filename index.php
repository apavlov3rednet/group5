<?php

use Main\Application;

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/header.php');
?>

<?php Application::includeComponent('news.list', 'default');?>
       
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/core/footer.php');?>