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
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

$keyItem = 0;
foreach ($arResult as $key => &$item) {
	if ($item['IS_PARENT']) {
		$keyItem = $key;
		continue;
	}
	if ($item['DEPTH_LEVEL'] > 1) {
		$arResult[$keyItem]['SUB_ITEMS'][] = $item;
		unset ($arResult[$key]);
	}
}
$arResult = array_values($arResult);