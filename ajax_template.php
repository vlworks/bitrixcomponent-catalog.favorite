<?php
define('STOP_STATISTICS', true);
define('NOT_CHECK_PERMISSIONS', true);

if (!isset($_POST['siteId']) || !is_string($_POST['siteId']))
    die();

define('SITE_ID', $_POST['siteId']);
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
if (!check_bitrix_sessid())
    die;

if (empty($_POST['favoriteId']))
    die();

$templateName = empty(!$_POST['templateName']) ? htmlspecialchars(strip_tags($_POST['templateName'])) : '';

$APPLICATION->RestartBuffer();
header('Content-Type: text/html; charset='.LANG_CHARSET);
$APPLICATION->IncludeComponent('vlworks:catalog.favorite', $templateName, array('AJAX' => 'Y', 'CURRENT_FAVORITE_ID' => htmlspecialchars(strip_tags($_POST['favoriteId']))));