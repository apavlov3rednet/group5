<?php

/**
 * @var array $arParams;
 * @var array $arResult;
 * @var string $templatePath;
 * @var string $componentPath;
 * @var string $curPage;
 * @var object $ob;
 */
?>
<h2>Пользователи</h2>
<div class="user-list">
    <?php if(!empty($arResult)):?>
        <?php foreach($arResult as $arItem):?>
            <div class="user-list-card">
                <div class="user-list-card-id"><?=$arItem['ID']?></div>
                <div class="user-list-card-login"><?=$arItem['LOGIN']?></div>
            </div>
        <?php endforeach;?>
    <?php endif;?>
</div>