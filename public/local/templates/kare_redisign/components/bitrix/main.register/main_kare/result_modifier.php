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
global $USER;

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$arResult['AJAX'] = ($request->isAjaxRequest()) ? 'Y' : 'N';
if (($arResult['AJAX'] == 'Y') && $USER->IsAuthorized()) {
	echo json_encode([
		'result' => 'Y',
		'message' => 'Вы зарегистрированы на сайте и успешно авторизованы.'
	]);
	die();
}

foreach ($arResult["SHOW_FIELDS"] as $key => $field) {
	if (in_array($field, ['LOGIN', 'PASSWORD', 'CONFIRM_PASSWORD'])) {
		unset($arResult['SHOW_FIELDS'][$key]);
		array_push($arResult['SHOW_FIELDS'], $field);
	}
}