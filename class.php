<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Application;
use Bitrix\Main\Web\Cookie;

class CatalogFavoriteComponent extends CBitrixComponent
{
    public function onPrepareComponentParams($params)
    {
        return $params;
    }

    public function executeComponent()
    {
        $this->prepareFavorites();

        if ($this->arParams['AJAX'] === 'Y') {
            $this->includeComponentTemplate('ajax_template');
        } else {
            $this->includeComponentTemplate();
        }

    }

    private function prepareFavorites(): void
    {
        global $USER;

        if (!$USER->IsAuthorized()) {
            $favorite = $this->getFavoritesFromCookies();
        } else {
            $favorite = $this->getFavoritesFromUserField($USER);
        }

        $this->modifiedResult($favorite);
    }

    private function getFavoritesFromCookies()
    {
        $data = Application::getInstance()->getContext()->getRequest()->getCookie("FAVORITE");
        if (!is_null($data)) {
            return json_decode($data);
        } else {
            return [];
        }
    }

    private function getFavoritesFromUserField($USER)
    {
        $idUser = $USER->GetID();
        $rsUser = CUser::GetByID($idUser);
        $arUser = $rsUser->Fetch();

        return $arUser['UF_FAVORITES'];
    }

    private function modifiedResult($favorites): void
    {
        $prepare = [
            'IS_EMPTY' => 'Y',
            'FAVORITES' => [],
            'FAVORITES_COUNT' => 0
        ];

        if (!empty($favorites)) {

            $favorites = $this->getFavoriteItems($favorites);

            $prepare = [
                'IS_EMPTY' => 'N',
                'FAVORITES' => $favorites,
                'FAVORITES_COUNT' => count($favorites)
            ];
        }

        $this->arResult = array_merge($this->arResult, $prepare);
    }

    private function getFavoriteItems($favorites): array
    {
        CModule::IncludeModule('iblock');

        $result = [];

        $arSelect = Array('NAME', 'PREVIEW_PICTURE', 'DETAIL_PICTURE', 'DETAIL_PAGE_URL');
        $arFilter = Array(
            "IBLOCK_ID"=>$this->arParams['IBLOCK_ID'],
            "ID" => $favorites,
            "ACTIVE"=>"Y"
        );
        $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
        while($ob = $res->GetNextElement())
        {
            $arFields = $ob->GetFields();

            $product = CCatalogProduct::GetByID($arFields['ID']);
            $arFields['PRODUCT_TYPE'] = $product['TYPE'];

            $price = GetCatalogProductPriceList($arFields["ID"])[0];
            // convert curse
            $convertVal = CCurrencyRates::ConvertCurrency((float)$price['PRICE'], $price['CURRENCY'], 'RUB');
            $arFields['PRICE'] = CurrencyFormat($convertVal, 'RUB');

            $prepareImg =
                !empty($arFields['PREVIEW_PICTURE'])
                ? $arFields['PREVIEW_PICTURE']
                : $arFields['DETAIL_PICTURE'];

            $arFields['IMG_URL'] = 'https://placehold.co/60x60';
            if (!empty($prepareImg))
            {
                $file = CFile::GetFileArray($prepareImg);
                $arFields['IMG_URL'] = $file['SRC'];
            }

            $result[] = $arFields;
        }

        return $result;
    }
}