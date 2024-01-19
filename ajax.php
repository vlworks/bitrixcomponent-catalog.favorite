<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Application;
use Bitrix\Main\Web\Cookie;
use Bitrix\Main\Engine\Response\AjaxJson;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Engine\JsonPayload;

class CatalogFavoriteComponentAjaxController extends Controller
{
    public function configureActions()
    {
        return [
            'send' => [
                'prefilters' => [],
                'postfilters' => [],
            ],
        ];
    }

    public function sendAction($id)
    {
        $idx = (int)$id;

        global $USER;
        if (!$USER->IsAuthorized()) {
            $result = $this->toggleCookie($idx);
        } else {
            $result = $this->toggleUserField($idx);
        }

        return json_encode(['ID' => $idx, 'MESSAGE' => $result['MESSAGE'], 'COUNT' => $result['COUNT']]);
    }

    private function toggleCookie($id): array
    {
        if ($id === 0)
        {
            // clear favorite
            $cookie = new Cookie('FAVORITE', json_encode([]));
            $cookie->setDomain(Application::getInstance()->getContext()->getServer()->getServerName());
            Application::getInstance()->getContext()->getResponse()->addCookie($cookie);

            return $this->sendResponse();
        }

        $favorite = [];
        $message = "ADD";

        $getFavorite = Application::getInstance()->getContext()->getRequest()->getCookie("FAVORITE");

        if (!$getFavorite)
        {
            $favorite[] = $id;
        } else {
            $favorite = json_decode($getFavorite);

            if (in_array($id, $favorite)) {
                $idx = array_search($id, $favorite);
                array_splice($favorite, $idx, 1);
                $message = "DEL";
            } else {
                $favorite[] = $id;
            }
        }

        $cookie = new Cookie('FAVORITE', json_encode($favorite));
        $cookie->setDomain(Application::getInstance()->getContext()->getServer()->getServerName());
        Application::getInstance()->getContext()->getResponse()->addCookie($cookie);

        return $this->sendResponse($message, count($favorite));
    }

    private function toggleUserField($id): array
    {
        global $USER;
        $idUser = $USER->GetID();
        $rsUser = CUser::GetByID($idUser);
        $arUser = $rsUser->Fetch();

        if ($id === 0) {
            // clear favorite
            $USER->Update($idUser, ['UF_FAVORITES' => []]);
            return $this->sendResponse();
        }


        $favorite = [];
        $message = "ADD";

        $getFavorite = $arUser['UF_FAVORITES'];

        if (!$getFavorite) {
            $favorite[] = $id;
        } else {
            $favorite = $getFavorite;

            if (in_array($id, $favorite)) {
                $idx = array_search($id, $favorite);
                array_splice($favorite, $idx, 1);
                $message = "DEL";
            } else {
                $favorite[] = $id;
            }
        }

        $USER->Update($idUser, ['UF_FAVORITES' => $favorite]);

        return $this->sendResponse($message, count($favorite));
    }

    private function sendResponse($message = 'CLEAN', $count = 0)
    {
        return ['MESSAGE' => $message, 'COUNT' => $count];
    }
}
