<?php
/**
 * @var array $arResult
 * @var array $arParams
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $templateFile
 * @var string $templateFolder
 * @var string $componentPath
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

$CFile = new \CFile();
foreach ($arResult['SECTIONS'] as &$section) {
	$section['PICTURE'] = $CFile->ResizeImageGet(
		$section['PICTURE'],
		[
			'width' => '1000',
			'height' => '10000',
		]
	);
}