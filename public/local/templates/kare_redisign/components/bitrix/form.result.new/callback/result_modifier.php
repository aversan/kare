<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) {
	die();
}

foreach ($arResult['QUESTIONS'] as &$question) {
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
		'CLIENT_NAME' => 'top-callback-form__input js-field js-name',
		'PHONE' => 'top-callback-form__input js-field js-phone',
	)
);