<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if (!empty($arParams['CURRENT_FAVORITE_ID']))
    $favoriteId = $arParams['CURRENT_FAVORITE_ID'];
?>
<div data-container="favorite">
    <p>Всего элементов: <?= $arResult['FAVORITES_COUNT']; ?></p>
    <?php foreach ($arResult['FAVORITES'] as $idx => $arItem): ?>
        <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>"><?= $idx + 1 ?>) <?= $arItem['NAME'] ?></a><button onclick="<?=$favoriteId?>.remove(<?=$arItem['ID']?>)">Удалить</button>
    <?php endforeach; ?>
</div>
