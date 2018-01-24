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
global $USER, $APPLICATION;

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$arResult['AJAX'] = ($request->isAjaxRequest()) ? 'Y' : 'N';
$isAuth = $request->getPost('isAuth');
if (($arResult['AJAX'] == 'Y') && $USER->IsAuthorized() && !empty($isAuth)) {
	$APPLICATION->RestartBuffer();
	echo json_encode([
		'result' => 'Y'
	]);
	die();
}

if (empty($arResult['USER_LOGIN']) && !empty($request->getPost('USER_LOGIN'))) {
	$arResult['USER_LOGIN'] = $request->getPost('USER_LOGIN');
}