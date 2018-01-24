<?php
/** @var array $arParams */
/** @var array $arResult */
/** @var $APPLICATION CMain */
/** @global CUser $USER */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

if (isset($APPLICATION->arAuthResult)) {
	$arResult['ERROR_MESSAGE'] = $APPLICATION->arAuthResult;
}

ShowMessage($arResult['ERROR_MESSAGE']);
