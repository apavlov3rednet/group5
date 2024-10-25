<?php

use Main\Application;

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/header.php');
?>

<?php Application::includeComponent('news.list', 'default', [
    'TABLE_NAME' => 'users',
    'COUNT_ELEMENT' => 2
]);?>
       
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/core/footer.php');?>