<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
$sliderID  = "specials_slider_wrapp_".rand();
$notifyOption = COption::GetOptionString("sale", "subscribe_prod", "");
$arNotify = unserialize($notifyOption);
?>
<?if($arResult["ITEMS"]):?>
	<div class="products_wrap">
		<div class="top_bl_control">
			<div class="title"><h2><?=$arParams['TITLE_SECTION']?></h2></div>
			<div class="control">
				<a class="but but-transparent" href="<?=$arParams['SECTION_PAGE_URL']?>">Все&nbsp;<?=$arParams['TITLE_SECTION']?></a>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="items block_items">
			<?$n=1;?>							
			<?foreach($arResult["ITEMS"] as $key => $arItem):?>
				<?
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));
				$totalCount = CKShop::GetTotalCount($arItem);
				$arQuantityData = CKShop::GetQuantityArray($totalCount);
				$arAddToBasketData = CKShop::GetAddToBasketArray($arItem, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arItem["PRICES"], $arItem["PROPERTIES"]["CML2_ARTICLE"]["VALUE"]);
				$json_props = json_encode($arItem['PROD_PROP']);
				?>
				<?
					if ($n>9){$n=1;}
					$bigItem = ($n == 1 || $n == 5 || $n == 9);
				?>
				<div id="<?=$this->GetEditAreaId($arItem['ID']);?>" class="prod_item<?=($bigItem ? ' big_item' : '')?>">
					<div class="item-equalize transition">
						<div class="image">
							<div>
								<a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
									<?if ($bigItem):?>
										<?if ($arItem['PICTURE']['BIG']['src']):?>
											<img src="<?=$arItem['PICTURE']['BIG']['src']?>" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"] ? $arItem["PREVIEW_PICTURE"]["ALT"] : $arItem["NAME"]);?>" title="<?=($arItem["PREVIEW_PICTURE"]["TITLE"] ? $arItem["PREVIEW_PICTURE"]["TITLE"] : $arItem["NAME"]);?>" />
										<?else:?>
											<img width="229" src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"] ? $arItem["PREVIEW_PICTURE"]["ALT"] : $arItem["NAME"]);?>" title="<?=($arItem["PREVIEW_PICTURE"]["TITLE"] ? $arItem["PREVIEW_PICTURE"]["TITLE"] : $arItem["NAME"]);?>" />
										<?endif;?>
									<?else:?>
										<?if ($arItem['PICTURE']['SMALL']['src']):?>
											<img src="<?=$arItem['PICTURE']['SMALL']['src']?>" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"] ? $arItem["PREVIEW_PICTURE"]["ALT"] : $arItem["NAME"]);?>" title="<?=($arItem["PREVIEW_PICTURE"]["TITLE"] ? $arItem["PREVIEW_PICTURE"]["TITLE"] : $arItem["NAME"]);?>" />
										<?else:?>
											<img width="189" src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"] ? $arItem["PREVIEW_PICTURE"]["ALT"] : $arItem["NAME"]);?>" title="<?=($arItem["PREVIEW_PICTURE"]["TITLE"] ? $arItem["PREVIEW_PICTURE"]["TITLE"] : $arItem["NAME"]);?>" />
										<?endif;?>
									<?endif;?>
								</a>
							</div>
							<div class="ribbons">
								<?if(is_array($arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?>
									<?if(in_array("NEW", $arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?><span class="ribon_new"></span><?endif;?>
									<?/*if(in_array("HIT", $arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"]) ):?><span class="ribon_hit"></span><?endif;*/?>
									<?if(in_array("RECOMMEND", $arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?><span class="ribon_recomend"></span><?endif;?>
									<?if(in_array("STOCK", $arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?><span class="ribon_action"></span><?endif;?>
								<?endif;?>
								<?if ($arItem['DISCOUNT_ICO'] == 'Y'):?>
									<span class="discount-ico"></span>
								<?endif;?>
							</div>
						</div>
						<div class="item_info">
							<div class="top-info">
								<div class="name">
									<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a>
								</div>
								<div class="cost">
									<?
									$frame = $this->createFrame()->begin('');
									$frame->setBrowserStorage(true);
									?>
									<?if($arItem["OFFERS"]):?> 
										<div class="price_bl">
											<div class="price"><?=GetMessage("CATALOG_FROM");?> <?=$arItem["MIN_PRODUCT_OFFER_PRICE_PRINT"]?></div>
										</div>
									<?elseif($arItem["PRICES"]):?>
										<?
										$arCountPricesCanAccess = 0;
										foreach($arItem["PRICES"] as $key => $arPrice){
											if($arPrice["CAN_ACCESS"]){
												++$arCountPricesCanAccess;
											}
										}
										?>
										<?foreach($arItem["PRICES"] as $key => $arPrice):?>
											<?if($arPrice["CAN_ACCESS"]):?>
												<?$price = CPrice::GetByID($arPrice["ID"]); ?>
												<?if($arCountPricesCanAccess > 1):?>
													<div class="price_name"><?=$price["CATALOG_GROUP_NAME"];?></div>
												<?endif;?>
												<div class="price_bl">
													<?if($arPrice["VALUE"] > $arPrice["DISCOUNT_VALUE"]):?>
														<div class="price discount_yes"><?=$arPrice["PRINT_DISCOUNT_VALUE"];?></div>
														<div class="price-discount">
															<strike><?=$arPrice["PRINT_VALUE"];?></strike>
														</div>
													<?else:?>
														<div class="price"><?=$arPrice["PRINT_VALUE"];?></div>
													<?endif;?>
												</div>
											<?endif;?>
										<?endforeach;?>				
									<?else:?>
										<div class="price_bl">
											<div class="price soon_available">СКОРО В ПРОДАЖЕ!</div>
										</div>
									<?endif;?>
									<?$frame->end();?>
								</div>
								<?if (is_array($arItem['DISPLAY_PROPERTIES']) && $bigItem):?>
									<div class="props">
										<?foreach($arItem['DISPLAY_PROPERTIES'] as $arProps):?>
											<?if ($arProps['VALUE']):?>
												<div class="prop-wrap">
													<div class="prop-name"><?=$arProps['NAME']?></div>
													<div class="prop-value">
														<?if (is_array($arProps['VALUE'])):?>
															<?$countVal = count($arProps['VALUE'])-1;?>
															<?foreach ($arProps['VALUE'] as $keyValue => $value):?>
																<span><?=$value?><?=($keyValue != $countVal ? ', ' : '')?></span>
															<?endforeach?>
														<?else:?>
															<span><?=$arProps['VALUE']?></span>
														<?endif;?>
													</div>
													<div class="clearfix fix"></div>
												</div>
											<?endif;?>
										<?endforeach;?>
									</div>
								<?endif;?>
							</div>
							<div class="buttons_block clearfix">
								<!--noindex-->
								<?=$arAddToBasketData["HTML"]?>
								<span class="json_props"><?=$json_props;?></span>
								<?if($arParams["DISPLAY_WISH_BUTTONS"] != "N" || $arParams["DISPLAY_COMPARE"] == "Y"):?>
									<?if(empty($arItem["OFFERS"]) && $arParams["DISPLAY_WISH_BUTTONS"] != "N"):?>
										<div class="wish_item-wrap"><a title="Добавить в WishList" class="wish_item" rel="nofollow" data-item="<?=$arItem["ID"]?>"></a></div>
									<?endif;?>
									<?if($arParams["DISPLAY_COMPARE"] == "Y"):?>
										<div class="compare_item-wrap"><a title="<?=GetMessage('CATALOG_COMPARE')?>" class="compare_item" rel="nofollow" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arItem["ID"]?>" href="<?=$arItem["COMPARE_URL"]?>"><i></i></a></div>
									<?endif;?>
								<?endif;?>
								<!--/noindex-->
							</div>
						</div>
					</div>
				</div>
				<?$n++;?>

			<?endforeach;?>
			<div class="clearfix"></div>
		</div>
		<?if ($arParams['ELEMENT_COUNT'] < $arResult['ALL_COUNT']):?>
			<div class="button-more">
				<span class="but but-red more-ico js-more-product">Посмотреть еще</span>
			</div>
		<?endif;?>
	</div>
<?endif;?>
