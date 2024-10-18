<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/core/header.php');

use Db\Basic;
?>

    <?php
    $result = new Basic();
    $arResult = $result->getList('users', [
        'select' => ['LOGIN'],
        'limit' => [
            'rows' => 2,
        ]
    ]);

    // $result->add('users', [
    //     'LOGIN' => 'IVAN',
    //     'PASSWORD' => '1223445'
    // ]);
    ?>

    <pre><?print_r($arResult)?></pre>
    
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/core/footer.php');?>