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
		'EMAIL' => 'email',
	),
	array(
		'NAME' => 'custom-input',
		'PHONE' => 'custom-input js-phone',
		'TEXT' => 'custom-input',
		'FILE' => 'inputfile inputfile-3 js-files',
		'EMAIL' => 'custom-input',
		'JOB' => 'job',
		'JOB_ID' => 'job-id',
	)
);