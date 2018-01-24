<?php
/**
 * @var array $arResult
 * @var array $arParams
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $templateFile
 * @var string $templateFolder
 * @var string $componentPath
 * @var CBitrixComponent $component
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}
global $APPLICATION;

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$arResult['AJAX'] = ($request->isAjaxRequest()) ? 'Y' : 'N';


if (isset($APPLICATION->arAuthResult)){
	$arResult['ERROR_MESSAGE'] = $APPLICATION->arAuthResult;
}

if (($arResult['AJAX'] == 'Y') && !empty($arResult['ERROR_MESSAGE'])) {
	echo json_encode([
		'result' => 'Y',
		'message' => ($arResult['ERROR_MESSAGE']['TYPE'] == 'OK') ?  'Ваш пароль выслан вам по SMS.' : $arResult['ERROR_MESSAGE']['MESSAGE']
	]);
	die();
}