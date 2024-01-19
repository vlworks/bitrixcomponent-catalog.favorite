[33mcommit cc9cc81262af5519f3c640e03e0c532311eba8d2[m[33m ([m[1;36mHEAD -> [m[1;32mmain[m[33m, [m[1;33mtag: 1.1.2[m[33m, [m[1;31morigin/main[m[33m)[m
Author: Vladislav Lyakishev <vlad169@yandex.ru>
Date:   Fri Jan 19 15:10:19 2024 +0300

    change vendor name

[1mdiff --git a/.description.php b/.description.php[m
[1mindex 8a28336..54c1c67 100644[m
[1m--- a/.description.php[m
[1m+++ b/.description.php[m
[36m@@ -6,7 +6,7 @@[m [m$arComponentDescription = array([m
     "DESCRIPTION" => GetMessage("IBLOCK_СOMPONENT_DESCRIPTION"),[m
     "CACHE_PATH" => "Y",[m
     "PATH" => array([m
[31m-        "ID" => "basis-marketing",[m
[32m+[m[32m        "ID" => "vlworks",[m
         "NAME" => GetMessage("IBLOCK_NAME_VENDOR"),[m
     ),[m
 );[m
\ No newline at end of file[m
[1mdiff --git a/ajax_template.php b/ajax_template.php[m
[1mindex ab247b0..2ee9862 100644[m
[1m--- a/ajax_template.php[m
[1m+++ b/ajax_template.php[m
[36m@@ -17,4 +17,4 @@[m [m$templateName = empty(!$_POST['templateName']) ? htmlspecialchars(strip_tags($_P[m
 [m
 $APPLICATION->RestartBuffer();[m
 header('Content-Type: text/html; charset='.LANG_CHARSET);[m
[31m-$APPLICATION->IncludeComponent('basis-marketing:catalog.favorite', $templateName, array('AJAX' => 'Y', 'CURRENT_FAVORITE_ID' => htmlspecialchars(strip_tags($_POST['favoriteId']))));[m
\ No newline at end of file[m
[32m+[m[32m$APPLICATION->IncludeComponent('vlworks:catalog.favorite', $templateName, array('AJAX' => 'Y', 'CURRENT_FAVORITE_ID' => htmlspecialchars(strip_tags($_POST['favoriteId']))));[m
\ No newline at end of file[m
[1mdiff --git a/lang/ru/.description.php b/lang/ru/.description.php[m
[1mindex 7c2d0ec..aa20593 100644[m
[1m--- a/lang/ru/.description.php[m
[1m+++ b/lang/ru/.description.php[m
[36m@@ -1,4 +1,4 @@[m
 <?php[m
[31m-$MESS ['IBLOCK_NAME_VENDOR'] = "Базис-Маркетинг";[m
[32m+[m[32m$MESS ['IBLOCK_NAME_VENDOR'] = "VLWorks";[m
 $MESS ['IBLOCK_COMPONENT_NAME'] = "Каталог.Избранное";[m
 $MESS ['IBLOCK_СOMPONENT_DESCRIPTION'] = "Избранное для торгового каталога";[m
\ No newline at end of file[m
