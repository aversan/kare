<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arTemplateParameters = array(
	"NEW_REVIEW_FORM" => Array(
		"NAME" => GetMessage("ASKARON_REVIEWS_NEW_REVIEW_FORM"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"SCHEMA_ORG_INSIDE_PRODUCT" => array(
		"NAME" => GetMessage("ASKARON_REVIEWS_SCHEMA_ORG_INSIDE_PRODUCT"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
	),
);
?>
