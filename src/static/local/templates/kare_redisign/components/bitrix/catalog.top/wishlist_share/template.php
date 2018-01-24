<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?if($arResult["ITEMS"]):?>
	<div id="basket_form" class="basket_wrapp wishlist_share">
		<div class="module-cart">
			<div class="colored basket_items">
				<div class="thead">
					<?
						foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader)
						{
							if ($arHeader["id"] == "DELETE"){$bDeleteColumn = true;}	
							if ($arHeader["id"] == "TYPE"){$bTypeColumn = true;}
							if ($arHeader["id"] == "QUANTITY"){$bQuantityColumn = true;}
						}
					?>
					<div class="td image"><div></div></div>
					<?foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader):
						if (in_array($arHeader["id"], array("TYPE"))) {continue;} // some header columns are shown differently
						elseif ($arHeader["id"] == "PROPS"){$bPropsColumn = true; continue;}
						elseif ($arHeader["id"] == "DELAY"){$bDelayColumn = true; continue;}
						elseif ($arHeader["id"] == "WEIGHT"){ $bWeightColumn = true;}
						elseif ($arHeader["id"] == "DELETE"){ continue;}?>
						<div class="td <?=strToLower($arHeader["id"])?>-th"><?=getColumnName($arHeader)?></div>
					<?endforeach;?>
				</div>
				<div class="tbody">
					<?foreach ($arResult["GRID"]["ROWS"] as $k => $arItem):?>
						<div class="tr" data-id="<?=$arItem["ID"]?>">
							<?if ($bDeleteColumn):?>
								<div class="td remove-cell"><a class="remove" href="<?=str_replace("#ID#", $arItem["ID"], $arUrls["delete"])?>" title="<?=GetMessage("SALE_DELETE")?>"><i></i></a></div>
							<?endif;?>
							<? foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader):
								if (in_array($arHeader["id"], array("PROPS", "DELAY", "DELETE", "TYPE"))) continue; // some values are not shown in columns in this template
								if ($arHeader["id"] == "NAME"):?>
									<div class="td thumb-cell">
										<div class="thumb_wrap">
											<div>
												<a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
													<?if ($arItem['PICTURE']['SMALL']['src']):?>
														<img src="<?=$arItem['PICTURE']['SMALL']['src']?>" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"] ? $arItem["PREVIEW_PICTURE"]["ALT"] : $arItem["NAME"]);?>" title="<?=($arItem["PREVIEW_PICTURE"]["TITLE"] ? $arItem["PREVIEW_PICTURE"]["TITLE"] : $arItem["NAME"]);?>" />
													<?else:?>
														<img width="189" src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"] ? $arItem["PREVIEW_PICTURE"]["ALT"] : $arItem["NAME"]);?>" title="<?=($arItem["PREVIEW_PICTURE"]["TITLE"] ? $arItem["PREVIEW_PICTURE"]["TITLE"] : $arItem["NAME"]);?>" />
													<?endif;?>
												</a>	
												<?if (!empty($arItem["BRAND"])):?><div class="ordercart_brand"><img src="<?=$arItem["BRAND"]?>" /></div><?endif;?>
											</div>
										</div>
									</div>
									<div class="td name-cell">
										<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?endif;?><?=$arItem["NAME"]?><?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?></a><?endif;?><br />
										<?if ($bPropsColumn):?>	
											<div class="item_props">
												<? foreach ($arItem["PROPS"] as $val) {
														if (is_array($arItem["SKU_DATA"])) {
															$bSkip = false;
															foreach ($arItem["SKU_DATA"] as $propId => $arProp) { if ($arProp["CODE"] == $val["CODE"]) { $bSkip = true; break; } } 
															if ($bSkip) continue;
														} echo '<span class="item_prop"><span class="name">'.$val["NAME"].':&nbsp;</span><span class="property_value">'.$val["VALUE"].'</span></span>';
													}?>
											</div>
										<?endif;?>
										<?if (is_array($arItem["SKU_DATA"])):
											foreach ($arItem["SKU_DATA"] as $propId => $arProp):
												$isImgProperty = false; // is image property
												foreach ($arProp["VALUES"] as $id => $arVal) { if (isset($arVal["PICT"]) && !empty($arVal["PICT"])) { $isImgProperty = true; break; } }
												$full = (count($arProp["VALUES"]) > 5) ? "full" : "";
												if ($isImgProperty): // iblock element relation property
												?>
													<div class="bx_item_detail_scu_small_noadaptive <?=$full?>">
														<label><?=$arProp["NAME"]?>:</span>
														<div class="bx_scu_scroller_container">
															<div class="bx_scu">
																<ul id="prop_<?=$arProp["CODE"]?>_<?=$arItem["ID"]?>" style="width: 200%;margin-left:0%;">
																<?	foreach ($arProp["VALUES"] as $valueId => $arSkuValue){
																		$selected = "";
																		foreach ($arItem["PROPS"] as $arItemProp) { 
																			if ($arItemProp["CODE"] == $arItem["SKU_DATA"][$propId]["CODE"])
																				{ if ($arItemProp["VALUE"] == $arSkuValue["NAME"] || $arItemProp["VALUE"] == $arSkuValue["XML_ID"]) $selected = "class=\"bx_active\""; }
																		};?>
																		<li style="width:10%;" <?=$selected?>>
																			<a href="javascript:void(0);"><span style="background-image:url(<?=$arSkuValue["PICT"]["SRC"]?>)"></span></a>
																		</li>
																<?}?>
																</ul>
															</div>
															<div class="bx_slide_left" onclick="leftScroll('<?=$arProp["CODE"]?>', <?=$arItem["ID"]?>);"></div>
															<div class="bx_slide_right" onclick="rightScroll('<?=$arProp["CODE"]?>', <?=$arItem["ID"]?>);"></div>
														</div>
													</div>
												<?else:?>
													<div class="bx_item_detail_size_small_noadaptive <?=$full?>">
														<span class="bx_item_section_name_gray">
															<?=$arProp["NAME"]?>:
														</span>

														<div class="bx_size_scroller_container">
															<div class="bx_size">
																<ul id="prop_<?=$arProp["CODE"]?>_<?=$arItem["ID"]?>" style="width: 200%; margin-left:0%;">
																	<?foreach ($arProp["VALUES"] as $valueId => $arSkuValue) {
																		$selected = "";
																		foreach ($arItem["PROPS"] as $arItemProp) {
																			if ($arItemProp["CODE"] == $arItem["SKU_DATA"][$propId]["CODE"]) 
																			{ if ($arItemProp["VALUE"] == $arSkuValue["NAME"]) $selected = "class=\"bx_active\""; }
																		}?>
																		<li style="width:10%;" <?=$selected?>><a href="javascript:void(0);"><?=$arSkuValue["NAME"]?></a></li>
																	<?}?>
																</ul>
															</div>
															<div class="bx_slide_left" onclick="leftScroll('<?=$arProp["CODE"]?>', <?=$arItem["ID"]?>);"></div>
															<div class="bx_slide_right" onclick="rightScroll('<?=$arProp["CODE"]?>', <?=$arItem["ID"]?>);"></div>
														</div>
													</div>
												<?endif;
											endforeach;
										endif;
										?>
										<?if ($arItem['PROPS_FORMAT']):?>
											<div class="props_wrap">
												<?foreach ($arItem['PROPS_FORMAT'] as $arProp):?>
													<div class="prop">
														<span class="prop-name"><?=$arProp['NAME']?></span>
														<span class="prop-value">
															<?if (is_array($arProp['VALUE'])):?>
																<span><?=(count($arProp['VALUE']) > 1 ? implode(', ', $arProp['VALUE']) : $arProp['VALUE'][0])?></span>
															<?else:?>
																<span><?=$arProp['VALUE']?></span>
															<?endif;?>
														</span>
													</div>
												<?endforeach;?>
											</div>
										<?endif;?>
										<input type="hidden" name="DELAY_<?=$arItem["ID"]?>" value="Y" >
									</div>	
									<div class="clearfix media"></div>
								<?elseif ($arHeader["id"] == "QUANTITY"):?>
									<div class="td count-cell">
										<span class="count"><?=$arItem["QUANTITY"]?><?if (isset($arItem["MEASURE_TEXT"]) && $arParams["SHOW_MEASURE"]=="Y"):?> <?=$arItem["MEASURE_TEXT"];?><?endif;?></span>
										<?/*
											$ratio = isset($arItem["MEASURE_RATIO"]) ? $arItem["MEASURE_RATIO"] : 0;
											$max = isset($arItem["AVAILABLE_QUANTITY"]) ? "max=\"".$arItem["AVAILABLE_QUANTITY"]."\"" : "";
										?>
										<input
											type="hidden" 
											id="QUANTITY_INPUT_<?=$arItem["ID"]?>"
											name="QUANTITY_INPUT_<?=$arItem["ID"]?>"
											size="2"
											data-id="<?=$arItem["ID"];?>" 
											maxlength="18"
											min="0"
											<?=$max?>
											step="<?=$ratio?>"
											value="<?=$arItem["QUANTITY"]?>"
										>
										<input type="hidden" id="QUANTITY_<?=$arItem['ID']?>" name="QUANTITY_<?=$arItem['ID']?>" value="<?=$arItem["QUANTITY"]?>" > */?>
									</div>
								<?elseif ($arHeader["id"] == "SUMM"):?>
									<div class="td cost-cell summ-cell">
										<?/*<div class="price"><?=$arItem["SUMM_FORMATED"];?></div>*/?>								
										<?if( $arItem["OFFERS"]){?> 
											<div class="price"><?=GetMessage("CATALOG_FROM");?> <?=CurrencyFormat($arItem["MIN_PRODUCT_OFFER_PRICE"]*$arItem["QUANTITY"], "RUB");?></div>
										<?} elseif ( $arItem["PRICES"] ){?>
										<? $arCountPricesCanAccess = 0; foreach( $arItem["PRICES"] as $key => $arPrice ) { if($arPrice["CAN_ACCESS"]){$arCountPricesCanAccess++;} } ?>
											<?foreach( $arItem["PRICES"] as $key => $arPrice ){?>
												<?if( $arPrice["CAN_ACCESS"] ){?>
													<?$price = CPrice::GetByID($arPrice["ID"]); ?>
													<?if($arCountPricesCanAccess>1):?><div class="price_name"><?=$price["CATALOG_GROUP_NAME"];?></div><?endif;?>
													<?if($arPrice["VALUE"] > $arPrice["DISCOUNT_VALUE"]){?>
														<?$discount = true;?>
														<div class="price"><?=CurrencyFormat($arPrice["DISCOUNT_VALUE"]*$arItem["QUANTITY"],$arPrice["CURRENCY"]);?><?=$arPrice["PRINT_DISCOUNT_VALUE"];?></div>
														<div class="price discount"><strike><?=CurrencyFormat($arPrice["VALUE"]*$arItem["QUANTITY"],$arPrice["CURRENCY"]);?><?=$arPrice["PRINT_VALUE"]?></strike></div>
													<?}else{?>
														<div class="price"><?=CurrencyFormat($arPrice["VALUE"]*$arItem["QUANTITY"],$arPrice["CURRENCY"]);?></div>
													<?}?>
												<?}?>
											<?}?>
										<?}?>
									</div>		
								<?elseif ($arHeader["id"] == "PRICE"):?>
									<div class="td cost-cell">								
										<?/*if( doubleval($arItem["DISCOUNT_PRICE_PERCENT"]) > 0 ){?>
											<div class="price"><?=$arItem["PRICE_FORMATED"]?></div>
											<div class="price discount"><strike><?=$arItem["FULL_PRICE_FORMATED"]?></strike></div>
										<?}else{?>
											<div class="price"><?=$arItem["PRICE_FORMATED"];?></div>
										<?}?>
										<?if (strlen($arItem["NOTES"]) > 0 && $bTypeColumn):?>
											<div class="price_name"><?=$arItem["NOTES"]?></div>
										<?endif;*/?>																	
										<?if( $arItem["OFFERS"]){?> 
											<div class="price"><?=GetMessage("CATALOG_FROM");?> <?=$arItem["MIN_PRODUCT_OFFER_PRICE_PRINT"]?></div>
										<?} elseif ( $arItem["PRICES"] ){?>
										<? $arCountPricesCanAccess = 0; foreach( $arItem["PRICES"] as $key => $arPrice ) { if($arPrice["CAN_ACCESS"]){$arCountPricesCanAccess++;} } ?>
											<?foreach( $arItem["PRICES"] as $key => $arPrice ){?>
												<?if( $arPrice["CAN_ACCESS"] ){?>
													<?$price = CPrice::GetByID($arPrice["ID"]); ?>
													<?if($arCountPricesCanAccess>1):?><div class="price_name"><?=$price["CATALOG_GROUP_NAME"];?></div><?endif;?>
													<?if($arPrice["VALUE"] > $arPrice["DISCOUNT_VALUE"]){?>
														<?$discount = true;?>
														<div class="price"><?=$arPrice["PRINT_DISCOUNT_VALUE"];?></div>
														<div class="price discount"><strike><?=$arPrice["PRINT_VALUE"]?></strike></div>
													<?}else{?>
														<div class="price"><?=$arPrice["PRINT_VALUE"];?></div>
													<?}?>
												<?}?>
											<?}?>
										<?} else {?>
											<div class="price_bl">
												<div class="price soon_available">СКОРО<br />В ПРОДАЖЕ!</div>
											</div>
										<?}?>
									</div>
								<?elseif ($arHeader["id"] == "DISCOUNT"):?>
									<div class="td discount-cell"><?=$arItem["DISCOUNT_PRICE_PERCENT_FORMATED"]?></div>
								<?elseif ($arHeader["id"] == "WEIGHT"):?>
									<div class="td weight-cell"><?=$arItem["WEIGHT_FORMATED"]?></div>
								<?else:?>
									<div class="td cell"><?=$arItem[$arHeader["id"]]?></div>
								<?endif;?>
							<?endforeach;?>
							<?if ($bDelayColumn ):?>
								<div class="td add delay-cell">
									<div class="wish_item-wrap basket_button_wrap">
										<?if ( $arItem["PRICES"] ){?>
											<a title="Добавить в корзину" class="basket_button to_basket to-cart" rel="nofollow" href="<?=$arParams["BASKET_URL"]."?".$arParams["ACTION_VARIABLE"]."=".$arParams["ADD_TO_BASKET_ACTION"]."&id=".$arItem["PRODUCT_ID"];?>" data-item="<?=$arItem["PRODUCT_ID"]?>" data-quantity="<?=$arItem["QUANTITY"]?>" alt="<?=$arItem["NAME"]?>"></a>
											<a title="Перейти в корзину" class="basket_button to_basket in-cart" rel="nofollow" href="<?=$arParams["BASKET_URL"]?>" data-item="<?=$arItem["PRODUCT_ID"]?>" style="display:none;"></a>	
										<?}else{?>
											<a title="Узнать о поступлении" class="basket_button but-transparent" onclick="subscribeProduct({ 'subscription_type': 'PRODUCT_ARRIVAL', 
																						'product_id': <?=$arItem["ID"];?>, 
																						'product_article': '<?=$arItem["PROPERTIES"]["CML2_ARTICLE"]["VALUE"];?>', 
																						'product_available': '<?=(($totalCount > 0) ? "Y" : "N");?>', 
																						'form_fields': ['USER_NAME', 'USER_PHONE', 'USER_EMAIL']
																					});"></a>
										<?}?>
									</div>
								</div>
							<?endif;?>
							<div class="clearfix media"></div>
						</div>
					<?endforeach;?>
				</div>
			</div>
		</div>
	</div>
<?endif;?>