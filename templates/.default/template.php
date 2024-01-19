<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$favoriteId = 'bm_favorites' . $this->randString();
?>
<button class="<?=$arResult['FAVORITES_COUNT'] > 0 ? '' : 'empty'?>" id="<?=$favoriteId?>">Получить избранное</button>
<button id="<?=$favoriteId?>_clear">Очистить избранное</button>
<?php require(realpath(dirname(__FILE__)).'/ajax_template.php');?>

<script type="text/javascript">
    var <?=$favoriteId?> = new BasisMarketingFavorites;
    <?=$favoriteId?>.siteId = '<?=SITE_ID?>';
    <?=$favoriteId?>.favoriteId = '<?=$favoriteId?>';
    <?=$favoriteId?>.favoriteContainer = '[data-container="favorite"]';
    <?=$favoriteId?>.ajaxPath = '<?=$componentPath?>/ajax_template.php';
    <?=$favoriteId?>.templateName = '<?=$templateName?>';
    <?=$favoriteId?>.params = <?=CUtil::PhpToJSObject ($arParams)?>;
    BX.ready(() => {
        <?=$favoriteId?>.init(<?=CUtil::PhpToJSObject ($arResult['FAVORITES'])?>);
    })

</script>