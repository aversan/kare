<?
$properties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$arParams["IBLOCK_ID"]));
while ($arProperty = $properties->GetNext()) {
	if (array_key_exists ( $arProperty["ID"], $arResult["ITEMS"]) ) {
		$arResult["ITEMS"][$arProperty["ID"]]["XML_ID"] = $arProperty["XML_ID"];
		
		/* rename props*/
		if ($arProperty["XML_ID"] == "sv_Colors") $arResult["ITEMS"][$arProperty["ID"]]["NAME"] = "Цвета";
		elseif ($arProperty["XML_ID"] == "sv_materials") $arResult["ITEMS"][$arProperty["ID"]]["NAME"] = "Материалы";
	}
}

$arProp = array();
foreach( $arResult["ITEMS"] as $key => $arItem )
{
	if( $arItem["PROPERTY_TYPE"] == "N" || !$arItem["PROPERTY_TYPE"] )
	{
		if( ( (int)$arItem["VALUES"]["MIN"]["VALUE"] == (int)$arItem["VALUES"]["MAX"]["VALUE"] ) || (int)$arItem["VALUES"]["MIN"]["VALUE"] == 0 ){
			unset( $arResult["ITEMS"][$key] );
			continue;
		}
		$min = ( floor( $arItem["VALUES"]["MIN"]["VALUE"] / 100 ) ) * 100;
		$min = $min < 1 ? 0 : $min;
		$max = ( ceil( $arItem["VALUES"]["MAX"]["VALUE"] / 100 ) ) * 100;
		if( $min == $max ){
			unset( $arResult["ITEMS"][$key] );
		}elseif( !$arItem["PROPERTY_TYPE"] ){
//			$arItem["NAME"]="Цена";
//			array_unshift( $arProp, $arItem );
		}else{
			$arProp[$arItem["CODE"]] = $arItem;
		}
	}
	elseif( $arItem["VALUES"] )
	{
		/*if($arItem["CODE"]=="IN_STORES"){
			//uasort($arItem["VALUES"], array('pivatoria','addrCmpStores'));
			$settings = new \Aspro\Settings;
			$settings->sort( $arItem["VALUES"], array('VALUE'), 'ASC', true );
		}*/
		
		
		//remove value All from Style property
		if ($arItem["XML_ID"] == "sv_stil")
		{
			foreach ($arItem["VALUES"] as $j=>$arValue)
			{
				if (trim($arValue["VALUE"]) == "Все")
				{
					unset($arItem["VALUES"][$j]);
				}
			}
		}		
		
		//remove disabled values
		foreach ($arItem["VALUES"] as $key => $arValue)
		{
			if ($arValue["DISABLED"])
			{
				unset($arItem["VALUES"][$key]);
			}
		}	
		if (!$arItem["VALUES"])
		{
			unset($arProp[$arItem["CODE"]]);
		}
		else
		{
			foreach ($arItem["VALUES"] as $key => $arValue)
			{
				if ($arValue["DISABLED"])
				{
					unset($arItem["VALUES"][$key]);
				}
			}	
			
			$settings = new \Aspro\Settings;
			$settings->sort( $arItem["VALUES"], array('VALUE'), 'ASC', true );
		
			$arProp[$arItem["CODE"]] = $arItem;
		}
		
	}
}

$arResult["ITEMS"] = $arProp;

if( $_REQUEST["set_filter"] == "Y" ){
	$urls = array();
	if( $_REQUEST["sort"] ){
		$urls[] = 'sort='.htmlspecialchars( $_REQUEST["sort"] );
	}
	if( $_REQUEST["store"] ){
		$urls[] = 'store='.htmlspecialchars( $_REQUEST["store"] );
	}
	if( $_REQUEST["order"] ){
		$urls[] = 'order='.htmlspecialchars( $_REQUEST["order"] );
	}
	if( $_REQUEST["type"] ){
		$urls[] = 'type='.htmlspecialchars( $_REQUEST["type"] );
	}
	if( $_REQUEST["brand"] ){
		$urls[] = 'brand='.htmlspecialchars( $_REQUEST["brand"] );
	}
	if( $_REQUEST["q"] ){
		$urls[] = 'q='.htmlspecialchars( $_REQUEST["q"] );
	}

	$arResult["CLEAN_URL"] = $_SERVER["REDIRECT_URL"].$urls ? '?'.implode( '&', $urls ) : '';
}

//var_dump($arResult["ITEMS"]);

?>