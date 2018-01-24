<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$strReturn = '';
if($arResult){
	CModule::IncludeModule("iblock");
	global $KShopSectionID;
	$cnt = count($arResult);
	$lastindex = $cnt - 1;
	$bShowCatalogSubsections = COption::GetOptionString("aspro.kshop", "SHOW_BREADCRUMBS_CATALOG_SUBSECTIONS", "Y", SITE_ID) == "Y";
	
	for($index = 0; $index < $cnt; ++$index){
		$arSubSections = array();
		$arItem = $arResult[$index];
		$title = htmlspecialcharsex($arItem["TITLE"]);
		$bLast = $index == $lastindex;
		if($KShopSectionID && $bShowCatalogSubsections){
			$arSubSections = CKShop::getChainNeighbors($KShopSectionID, $arItem['LINK']);
		}
		if( $arItem["LINK"] <> "" && $arItem['LINK'] != GetPagePath() && $arItem['LINK']."index.php" != GetPagePath())
			$strReturn .= '<a class="number" href="'.$arItem["LINK"].'">'.($arSubSections ? '<span>'.$title.'</span><b class="space"></b>' : '<span>'.$title.'</span>').'</a>';
		else
			$strReturn .= '<span>'.$title.'</span>';
	}
	
	return '<div class="breadcrumbs">'.$strReturn.'</div>';
}
else{
	return $strReturn;
}
?>