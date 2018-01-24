<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)
	die();

foreach ($arResult['QUESTIONS'] as &$question ) {
	if ($question['REQUIRED'] == 'Y') {
		$question['CAPTION'] .= '*';
	}
}

$arResult['QUESTIONS'] = formQuestionsRebuild(
	$arResult['QUESTIONS'],
	array(
		'PHONE' => 'tel',
		'EMAIL' => 'email'
	),
	array(
		'PHONE' => 'feedback-form__field js-field js-input js-phone',
		'EMAIL' => 'feedback-form__field js-field js-email',
		'CLIENT_NAME' => 'feedback-form__field js-field js-name js-input',
		'POST' => 'feedback-form__field js-field js-post js-input'
	)
);

