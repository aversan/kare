<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!empty($arResult["ITEMS"])){
	$arRegions = array();
	
	$property_enums = CIBlockPropertyEnum::GetList(Array("sort" => "asc", "value" => "asc"), Array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "CODE" => "REGION"));
	while ($enum_fields = $property_enums->GetNext()) {
		$arRegions[$enum_fields["ID"]] = $enum_fields;
	}
	
	$arResult["REGIONS"] = $arRegions;
}
?>