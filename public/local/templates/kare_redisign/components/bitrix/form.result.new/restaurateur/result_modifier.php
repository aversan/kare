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

foreach ($arResult['QUESTIONS'] as $key => &$question) {
	if ($question['REQUIRED'] === 'Y') {
		$question['CAPTION'] .= '*';
	}
}

$arResult['QUESTIONS'] = formQuestionsRebuild(
	$arResult['QUESTIONS'],
	array(
		'PHONE' => 'tel',
	),
	array(
		'NAME' => 'custom-input',
		'PHONE' => 'custom-input js-phone',
		'TEXT' => 'custom-input',
		'CASE' => 'case',
		'CASE_ID' => 'case-id',
	)
);