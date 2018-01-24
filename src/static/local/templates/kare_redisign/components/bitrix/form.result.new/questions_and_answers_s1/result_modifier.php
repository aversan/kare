<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)

$APPLICATION->restartBuffer();
foreach ($arResult['QUESTIONS'] as &$question ) {
  if ($question['REQUIRED'] == 'Y') {
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
    'textarea' => 'form-field js-field js-post',
    'PHONE' => 'form-field js-field js-phone',
    'EMAIL' => 'form-field js-field js-email',
    'CLIENT_NAME' => 'form-field js-field js-name'
  )
);