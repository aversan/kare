<?/**
 * @var array $arResult
 * @var array $arParams
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $templateFile
 * @var string $templateFolder
 * @var string $componentPath
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

if(($arParams["SHOW_MEASURE"] == "Y") && ($arResult["CATALOG_MEASURE"])){
	$arMeasure = CCatalogMeasure::getList(array(), array("ID" => $arResult["CATALOG_MEASURE"]), false, false, array())->GetNext();
}

$totalCount = ($arResult["PROPERTIES"]["AVAILABLE"]["VALUE_XML_ID"] == "Y" ? 1 : 0);


$arBrand = array();
if(strlen($arResult["DISPLAY_PROPERTIES"]["BRAND"]["VALUE"]) && $arResult["PROPERTIES"]["BRAND"]["LINK_IBLOCK_ID"]){
	$resBrand = CIBlockElement::GetList(array(), array("IBLOCK_ID" => $arResult["PROPERTIES"]["BRAND"]["LINK_IBLOCK_ID"], "ID" => $arResult["DISPLAY_PROPERTIES"]["BRAND"]["VALUE"]));
	if($arBrand = $resBrand->GetNext()){
		if($arParams["SHOW_BRAND_PICTURE"] == "Y" && ($arBrand["PREVIEW_PICTURE"] || $arBrand["DETAIL_PICTURE"])){
			$arBrand["IMAGE"] = CFile::ResizeImageGet(($arBrand["PREVIEW_PICTURE"] ? $arBrand["PREVIEW_PICTURE"] : $arBrand["DETAIL_PICTURE"]), array("width" => 120, "height" => 40), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
		}
	}
}

$arImagesSRC = array();
if (isset($arResult['PROPERTIES']["MAIN_PHOTO"]["VALUE"]) && strlen($arResult['PROPERTIES']["MAIN_PHOTO"]["VALUE"])) {
	$arImagesSRC[] = trim($arResult['PROPERTIES']["MAIN_PHOTO"]["VALUE"]);
}
for ($i=1; $i<20; $i++) {
	if (isset($arResult['PROPERTIES']['KARTINKA'.$i]) && strlen($arResult['PROPERTIES']['KARTINKA'.$i]['VALUE'])) {
		$arImagesSRC[] = trim($arResult['PROPERTIES']['KARTINKA'.$i]["VALUE"]);
	}
}

$arImages = array();
foreach($arImagesSRC as $sImageSrc)
{
	$photoName = explode('/', $sImageSrc);
	$photoName = array_pop($photoName);
	$size = getimagesize($_SERVER['DOCUMENT_ROOT'].'/'.$sImageSrc);
	$arImages[] = array(
		'FILE_NAME' => $photoName,
		'SUBDIR' => 'img',
		'WIDTH' => $size[0],
		'HEIGHT' => $size[1],
		'CONTENT_TYPE' => '',
		'ORIGINAL_URL' => $sImageSrc,
	);
}



$bIsOneImage = count($arImages) == 1;
if($arImages){
	foreach($arImages as $i => $arImage)
	{
		$src = "";
		if (file_exists($_SERVER["DOCUMENT_ROOT"].'/upload/'.$arImage['SUBDIR'].'/'.$arImage['FILE_NAME'])) { $src = '/upload/'.$arImage['SUBDIR'].'/'.$arImage['FILE_NAME']; }
			elseif(file_exists($_SERVER["DOCUMENT_ROOT"]."/".$arImage["ORIGINAL_URL"]) && $arImage["ORIGINAL_URL"]) { $src = "/".$arImage["ORIGINAL_URL"]; 	}
		if ($src)
		{
			$arImages[$i]["BIG"] = array(
				"src" => $src,
				"width" => $arImage['WIDTH'],
				"height" => $arImage['HEIGHT'],
				"size" => $arImage['FILE_SIZE'],
			);
			$arImages[$i]["SMALL"] = $arImages[$i]["THUMB"] = $arImages[$i]["BIG"];
		} else {
			unset($arImages[$i]);
		}
	}
}

/**
 * Список складов, для которых выводится доп. инфо
 * о том, что "Дата отгрузки указывается из Москвы"
 * */
$mskStores = array(
    11, // Мюнхен
    12, // ОЖИДАЕТСЯ ПОСТУПЛЕНИЕ В МОСКВУ
);
?>
<?
	//add microdata wrapp
	$this->SetViewTarget('microdata_open_tag');?><div itemscope itemtype="http://schema.org/Product"><?$this->EndViewTarget();
	$this->SetViewTarget('microdata_name');?> itemprop="name"<?$this->EndViewTarget();
	$this->SetViewTarget('microdata_close_tag');?></div><?$this->EndViewTarget();
?>
<section class="product-detail">
	<div class="product-detail__wrapper">
		<div class="product-detail__top cf">
			<h1 class="product-detail__title"><?=$arResult["NAME"]?></h1>
			<div class="product-detail__left">
				<div class="product-detail__slider">
					<ul class="product-detail-slider product-detail-slider_main js-product-detail-slider">
						<? foreach ($arResult['PICTURES'] as $src) { ?>
							<li class="product-detail-slider__slide">
								<div class="product-detail-slider__link">
									<img class="product-detail-slider__image" src="/<?=$src?>" alt="фото">
								</div>
							</li>
						<? } ?>
					</ul>

					<ul class="product-detail-slider product-detail-slider_nav js-product-detail-slider-nav">
						<? foreach ($arResult['PICTURES'] as $src) { ?>
							<li class="product-detail-slider__slide">
								<div class="product-detail-slider__link">
									<img class="product-detail-slider__image" src="/<?=$src?>" alt="фото">
								</div>
							</li>
						<? } ?>
					</ul>
					<button class="btn btn_prev product-detail-slider_prev js-product-detail-prev" type="button"></button>
					<button class="btn btn_next product-detail-slider_next js-product-detail-next" type="button"></button>
				</div>
			</div>
			<div class="product-detail__right">
				<div class="product-card js-catalog-buttons">
					<?if(strlen($arResult["DISPLAY_PROPERTIES"]["CML2_ARTICLE"]["VALUE"])):?>
						<div class="product-card__article">
							<span><?=$arResult["DISPLAY_PROPERTIES"]["CML2_ARTICLE"]["NAME"]?>:</span>
							<span><?=$arResult["DISPLAY_PROPERTIES"]["CML2_ARTICLE"]["VALUE"]?></span>
						</div>
					<?endif;?>
					<div class="product-card__price">
                        <?php if(isset($arResult['PRICES']["Скидка на артикул"])){ ?>
                            <div class="price discount_yes"><?=$arResult['PRICES']["Скидка на артикул"]["PRINT_VALUE"];?></div>
                            <div class="price-discount"><strike style="font-size: 30px;"><?=$arResult["PRICES"]["Каре розничная"]["PRINT_VALUE"]?></strike></div>
                        <?php } else { ?>
                            <?=$arResult['BASE_PRICE']['PRINT_VALUE']?>
                        <?php } ?>

					</div>
					<div class="product-card__availability">
                        <?php if(!empty($arResult['PROPERTIES']['SROK_DEYSTVIYA_AKTSIONNOY_TSENY']['VALUE'])){ ?>
                            <div class="product-card__shipment" style="color:#FF6174;">
                                <span>Срок действия акционной цены:</span>
                                <span><?=date('d.m.Y', strtotime($arResult['PROPERTIES']['SROK_DEYSTVIYA_AKTSIONNOY_TSENY']['VALUE']))?></span>
                            </div>
                        <?php } ?>
						<? if (empty($arResult['STORES'])):
							$arDate = explode(' ', $arResult['PROPERTIES']['VOZMOZHNAYA_DATA_OTGRUZKI_IZ_MYUNKHENA']['VALUE']);
							$date = $arDate[0]; ?>
							<div class="product-card__shipment">
								<span>Срок отгрузки при заказе сегодня:</span>
								<span><?=$date?></span>
							</div>
						<? else: ?>
							<div class="store js-stores">
								<span class="store__title">Наличие на складе:</span>
								<ul class="store__list js-stores-list">
									<?
									$i = 0;
									foreach ($arResult['STORES'] as $store):
                                        /*
                                         * получаем Возможный срок отгрузки
                                         * хранится в св-ве товара.
                                         * у каждого склада своя дата.
                                         * код св-ва завязан на ИД склада.
                                         * */
                                        $storeComingDate = $arResult['PROPERTIES']["STORE_{$store["STORE_ID"]}_MINDATE"]["VALUE"];
                                    ?>
										<li class="store__item <?= ($i <= 2) ? '' : 'js-stores-hidden hidden' ?> js-stores-item">
											<?= $store['STORE_ADDR'] ?> <span class="store__value">
                                                
                                            <?if ( in_array($store["STORE_ID"], $mskStores ) ) { ?>
                                                (Дата отгрузки указывается из Москвы)
                                                <br>
                                            <? } ?>
                                            
                                            (<?= $store['AMOUNT'] ?> шт.)
                                            
                                            <? if ( $storeComingDate ) { ?>
                                                <br>
                                                Дата отгрузки: <?=$storeComingDate?>
                                                <br>
                                                <br>
                                            <? } ?>
                                                
                                            </span>
										</li>
										<?
										$i++;
									endforeach; ?>
								</ul>
								<? if (count($arResult['STORES']) > 3):?>
									<div class="store__more js-show-more-stores">
										<span class="js-more-btn-text">Другие склады</span>
										<div class="icon-wrapper icon-wrapper_arrow">
											<?= \Local\Svg::get('ic-arrow-left', 'icon icon_arrow') ;?>
										</div>
									</div>
								<? endif; ?>
							</div>
						<? endif; ?>
					</div>
					<div class="product-card__amount js-amount-block <?=$arResult['ADDED'] ? 'hidden' : '' ?>" data-item="<?=$arResult["ID"];?>">
						<span>Количество</span>
						<div class="product-card__counter">
							<div class="counter_block">
								<span class="counter_block__item minus">-</span>
								<input type="text" class="counter_block__item text js-amount js-numeric-input" name="count_items"  id="count_items_sel" value="<?=($arParams["DEFAULT_COUNT"] ? $arParams["DEFAULT_COUNT"] : "1");?>" min="1"/>
								<span class="counter_block__item plus">+</span>
								<select onchange="$('.buttons_block #count_items_sel').val($('#count_items_sel_phone').val());" id="count_items_sel_phone" style="display: none;">
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="6">6</option>
								</select>
							</div>
						</div>
					</div>
					<div class="product-card__buttons">
						<a title="Добавить в корзину" class="basket-button basket-button_to-cart js-to-basket js-to-basket-btn  <?=$arResult['ADDED'] ? 'hidden' : '' ?>" rel="nofollow" href="/ajax/item.php" data-item="<?=$arResult['ID']?>"  data-quantity="1" alt="<?=$arResult['NAME']?>" onclick="ga('send', 'event', 'Button1', 'Buttonclick1');" data-type="basket">В корзину<div class="icon-wrapper icon-wrapper_like"><?= \Local\Svg::get("ic-basket", "icon icon_basket icon_white")?></div></a>
						<a title="Перейти в корзину"
							class="basket-button basket-button_in-cart js-in-basket-btn <?=$arResult['ADDED'] ? '' : 'hidden' ?>"
							rel="nofollow" href="/basket/" data-item="<?=$arResult['ID']?>">В корзине
						</a>
					</div>
				<!--/noindex-->
					<div class="product-card__wishes">
						<a title="Добавить в WishList" class="basket-button basket-button js-to-basket js-to-wish-btn <?=$arResult['DELAY'] ? 'added' : ''?>" href="/ajax/item.php" rel="nofollow" data-item="<?=$arResult["ID"]?>" data-quantity="1" data-type="wish">
							<div class="icon-wrapper icon-wrapper_like">
								<?= \Local\Svg::get('ic-like', 'icon icon_like icon_white') ;?>
							</div>
						</a>
					</div>
					<?// product subscribe block?>
					<?
						$sPriceType = $arParams["PRICE_CODE"][0]; //we use only one price type
						$PRODUCT_PRICE = false;
						$PRODUCT_PRICE_TYPE = $arResult["PRICES"][$sPriceType]["PRICE_ID"];
						if ($arResult["PRICES"][$sPriceType]["VALUE"] > $arResult["PRICES"][$sPriceType]["DISCOUNT_VALUE"])
						{
							$PRODUCT_PRICE = $arResult["PRICES"][$sPriceType]["DISCOUNT_VALUE"];
						}
						else
						{
							$PRODUCT_PRICE = $arResult["PRICES"][$sPriceType]["VALUE"];
						}
					?>
					<?if (($PRODUCT_PRICE && $PRODUCT_PRICE_TYPE) || ($totalCount <= 0)):?>
						<div class="product-card__subscribe | detail_subscribe">
							<?if ($PRODUCT_PRICE && $PRODUCT_PRICE_TYPE):?>
								<div class="subscribe_button_wrapp price_drop">
									<a class="btn btn_text | but but-transparent" onclick="subscribeProduct({	'subscription_type': 'PRICE_DOWN',
										'product_id': '<?=$arResult["ID"];?>',
										'product_article': '<?=$arResult["PROPERTIES"]["CML2_ARTICLE"]["VALUE"];?>',
										'product_available': '<?=(($totalCount > 0) ? "Y" : "N");?>',
										'product_price': '<?=$PRODUCT_PRICE?>',
										'product_price_type': '<?=$PRODUCT_PRICE_TYPE?>',
										'form_fields': ['USER_NAME', 'USER_PHONE', 'USER_EMAIL']
									});">Узнать о снижении цены</a>
								</div>
							<?endif;?>
							<?if ($totalCount <= 0):?>
								<div class="subscribe_button_wrapp product_arrival">
									<a class="btn btn_text | but but-transparent" onclick="subscribeProduct({ 'subscription_type': 'PRODUCT_ARRIVAL',
										'product_id': <?=$arResult["ID"];?>,
										'product_article': '<?=$arResult["DISPLAY_PROPERTIES"]["CML2_ARTICLE"]["VALUE"];?>',
										'product_available': '<?=(($totalCount > 0) ? "Y" : "N");?>',
										'form_fields': ['USER_NAME', 'USER_PHONE', 'USER_EMAIL']
									});">Узнать о поступлении</a>
								</div>
							<?endif;?>
						</div>
					<?endif;?>
					<?if($arParams["USE_RATING"] == "Y"):?>
						<div id="rating_wrap" class="product-card__rating"></div>
					<?endif;?>
					<div class="product-card__share | detail_share">
						<?/*<span class="title">Поделиться:</span>*/?>
						<div class="share_wrap"><?$APPLICATION->IncludeFile(SITE_DIR."include/share_buttons.php", Array(), Array("MODE" => "html", "NAME" => GetMessage('CT_BCE_CATALOG_SOC_BUTTON')));?></div>
					</div>
					<div class="product-card__info | detail_text_wrap">
						<?$APPLICATION->IncludeFile(SITE_DIR."inc/detail_order_payment.php", Array(), Array("MODE" => "html", "NAME" => 'Доставка, способы оплаты, условия продажи'));?>
					</div>
				</div>
			</div>
		</div>

		<div class="product-tabs | tabs_section">
	<div class="product-tabs__wrapper">
		<ul class="tabs tabs__list | main_tabs">
			<?
			$iTab = 0;
			$showProps = false;
			if($arResult["DISPLAY_PROPERTIES"]){
				foreach($arResult["DISPLAY_PROPERTIES"] as $arProp){
					if(!in_array($arProp["CODE"], array("SERVICES", "BRAND", "HIT", "RECOMMEND", "NEW", "STOCK", "VIDEO", "VIDEO_YOUTUBE"))){
						if(!is_array($arProp["DISPLAY_VALUE"])){
							$arProp["DISPLAY_VALUE"] = array($arProp["DISPLAY_VALUE"]);
						}
					}

					if($arProp["DISPLAY_VALUE"]){
						foreach($arProp["DISPLAY_VALUE"] as $value){
							if(strlen($value)){
								$showProps = true;
								break;
							}
						}
					}
				}
			}
			?>
			<?if($arResult["DETAIL_TEXT"] || count($arResult["STOCK"])  || count($arResult["SERVICES"]) || (count($arResult["PROPERTIES"]["INSTRUCTIONS"]["VALUE"]) || count($arResult["SECTION_FULL"]["UF_FILES"])) || ($showProps && $arParams["PROPERTIES_DISPLAY_LOCATION"] != "TAB")):?>
				<li class="tabs__caption <?=(!($iTab++) ? ' cur' : '')?>">
					<span><?=GetMessage("DESCRIPTION_TAB")?></span>
				</li>
			<?endif;?>
			<?if($arParams["SHOW_ADDITIONAL_TAB"] == "Y"):?>
				<li class="tabs__caption <?=(!($iTab++) ? ' cur' : '')?>">
					<span><?=GetMessage("INSTRUCTIONS_TAB");?></span>
				</li>
			<?endif;?>
			<?if(($arParams["SHOW_ASK_BLOCK"] == "Y") && (intVal($arParams["ASK_FORM_ID"]))):?>
				<li class="tabs__caption <?=(!($iTab++) ? ' cur' : '')?>">
					<span><?=GetMessage('ASK_TAB')?></span>
				</li>
			<?endif;?>
			<?if($arParams["USE_REVIEW"] == "Y"):?>
				<li class="tabs__caption <?=(!($iTab++) ? ' cur' : '')?>" id="product_reviews_tab">
					<span><?=GetMessage("REVIEW_TAB")?></span>
				</li>
			<?endif;?>
			<?if(strlen($arResult["DISPLAY_PROPERTIES"]["VIDEO"]["VALUE"]) || strlen($arResult["DISPLAY_PROPERTIES"]["VIDEO_YOUTUBE"]["VALUE"]) || $arResult["SECTION_FULL"]["UF_VIDEO"] || $arResult["SECTION_FULL"]["UF_VIDEO_YOUTUBE"]):?>
				<li class="tabs__caption <?=(!($iTab++) ? ' cur' : '')?>">
					<span><?=GetMessage("VIDEO_TAB")?></span>
				</li>
			<?endif;?>
			<li class="tabs__caption <?=(!($iTab++) ? ' cur' : '')?>">
				<span><?=GetMessage("DELIVERY_TAB")?></span>
			</li>
		</ul>
	</div>
	<div class="tabs-content | tabs_section_content">
		<div class="tabs-content__inner">
			<ul class="tabs-content__list | tabs_content">
				<?$show_tabs = false;?>
				<?$iTab = 0;?>
				<?$showSkUImages = (in_array('PREVIEW_PICTURE', $arParams['OFFERS_FIELD_CODE']) || in_array('DETAIL_PICTURE', $arParams['OFFERS_FIELD_CODE']));?>
				<?if($arResult["DETAIL_TEXT"] || count($arResult["STOCK"])  || count($arResult["SERVICES"]) || (count($arResult["PROPERTIES"]["INSTRUCTIONS"]["VALUE"]) || count($arResult["SECTION_FULL"]["UF_FILES"])) || ($showProps && $arParams["PROPERTIES_DISPLAY_LOCATION"] != "TAB")):?>
					<li class="tabs-content__item | <?=(!($iTab++) ? ' cur' : '')?>">
						<?if($showProps && $arParams["PROPERTIES_DISPLAY_LOCATION"] != "TAB"):?>
							<?if($arParams["PROPERTIES_DISPLAY_TYPE"] != "TABLE"):?>
								<div class="props_block">
									<?foreach($arResult["PROPERTIES"] as $propCode => $arProp):?>
										<?if(isset($arResult["DISPLAY_PROPERTIES"][$propCode])):?>
											<?$arProp = $arResult["DISPLAY_PROPERTIES"][$propCode];?>
											<?if(!in_array($arProp["CODE"], array("SERVICES", "BRAND", "HIT", "RECOMMEND", "NEW", "STOCK", "VIDEO", "VIDEO_YOUTUBE"))):?>
												<?if((!is_array($arProp["DISPLAY_VALUE"]) && strlen($arProp["DISPLAY_VALUE"])) || (is_array($arProp["DISPLAY_VALUE"]) && implode('', $arProp["DISPLAY_VALUE"]))):?>
													<div class="char">
														<div class="char_name">
															<span><?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"):?><div class="hint"><span class="icon"><i>?</i></span><b class="triangle"></b><div class="tooltip"><a class="tooltip_close">×</a><?=$arProp["HINT"]?></div></div><?endif;?><?=$arProp["NAME"]?></span>
														</div>
														<div class="char_value">
															<?if(count($arProp["DISPLAY_VALUE"]) > 1):?>
																<?=implode(', ', $arProp["DISPLAY_VALUE"]);?>
															<?else:?>
																<?=$arProp["DISPLAY_VALUE"];?>
															<?endif;?>
														</div>
													</div>
												<?endif;?>
											<?endif;?>
										<?endif;?>
									<?endforeach;?>
								</div>
							<?else:?>
								<table class="props_table" itemprop="description">
									<?foreach($arResult["DISPLAY_PROPERTIES"] as $arProp):?>
										<?if(!in_array($arProp["CODE"], array("SERVICES", "BRAND", "HIT", "RECOMMEND", "NEW", "STOCK", "VIDEO", "VIDEO_YOUTUBE"))):?>
											<?if((!is_array($arProp["DISPLAY_VALUE"]) && strlen($arProp["DISPLAY_VALUE"])) || (is_array($arProp["DISPLAY_VALUE"]) && implode('', $arProp["DISPLAY_VALUE"]))):?>
												<tr>
													<td class="char_name" data-xml-id="<?=$arProp["XML_ID"]?>">
														<span><?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"):?><div class="hint"><span class="icon"><i>?</i></span><b class="triangle"></b><div class="tooltip"><a class="tooltip_close">×</a><?=$arProp["HINT"]?></div></div><?endif;?><?=$arProp["NAME"]?></span>
													</td>
													<td class="char_value">
														<span>
															<?if(count($arProp["DISPLAY_VALUE"]) > 1):?>
																<?=implode(', ', $arProp["DISPLAY_VALUE"]);?>
															<?else:?>
																<?=$arProp["DISPLAY_VALUE"];?>
															<?endif;?>
														</span>
													</td>
												</tr>
											<?endif;?>
										<?endif;?>
									<?endforeach;?>
								</table>
							<?endif;?>
						<?endif;?>
						<?
						$arFiles = array();
						if($arResult["PROPERTIES"]["INSTRUCTIONS"]["VALUE"]){
							$arFiles = $arResult["PROPERTIES"]["INSTRUCTIONS"]["VALUE"];
						}
						else{
							$arFiles = $arResult["SECTION_FULL"]["UF_FILES"];
						}
						if(is_array($arFiles)){
							foreach($arFiles as $key => $value){
								if(!intval($value)){
									unset($arFiles[$key]);
								}
							}
						}
						?>
					</li>
				<?endif;?>

				<?if($showProps && $arParams["PROPERTIES_DISPLAY_LOCATION"] == "TAB"):?>
					<li class="tabs-content__item | <?=(!($iTab++) ? ' cur' : '')?>">
						<?if($arParams["PROPERTIES_DISPLAY_TYPE"] != "TABLE"):?>
							<div class="props_block">
								<?foreach($arResult["PROPERTIES"] as $propCode => $arProp):?>
									<?if(isset($arResult["DISPLAY_PROPERTIES"][$propCode])):?>
										<?$arProp = $arResult["DISPLAY_PROPERTIES"][$propCode];?>
										<?if(!in_array($arProp["CODE"], array("SERVICES", "BRAND", "HIT", "RECOMMEND", "NEW", "STOCK", "VIDEO", "VIDEO_YOUTUBE"))):?>
											<?if((!is_array($arProp["DISPLAY_VALUE"]) && strlen($arProp["DISPLAY_VALUE"])) || (is_array($arProp["DISPLAY_VALUE"]) && implode('', $arProp["DISPLAY_VALUE"]))):?>
												<div class="char">
													<div class="char_name">
														<span><?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"):?><div class="hint"><span class="icon"><i>?</i></span><b class="triangle"></b><div class="tooltip"><a class="tooltip_close">×</a><?=$arProp["HINT"]?></div></div><?endif;?><?=$arProp["NAME"]?></span>
													</div>
													<div class="char_value">
														<?if(count($arProp["DISPLAY_VALUE"]) > 1):?>
															<?=implode(', ', $arProp["DISPLAY_VALUE"]);?>
														<?else:?>
															<?=$arProp["DISPLAY_VALUE"];?>
														<?endif;?>
													</div>
												</div>
											<?endif;?>
										<?endif;?>
									<?endif;?>
								<?endforeach;?>
							</div>
						<?else:?>
							<table class="props_table" itemprop="description">
								<?foreach($arResult["DISPLAY_PROPERTIES"] as $arProp):?>
									<?if(!in_array($arProp["CODE"], array("SERVICES", "BRAND", "HIT", "RECOMMEND", "NEW", "STOCK", "VIDEO", "VIDEO_YOUTUBE"))):?>
										<?if((!is_array($arProp["DISPLAY_VALUE"]) && strlen($arProp["DISPLAY_VALUE"])) || (is_array($arProp["DISPLAY_VALUE"]) && implode('', $arProp["DISPLAY_VALUE"]))):?>
											<tr>
												<td class="char_name">
													<span><?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"):?><div class="hint"><span class="icon"><i>?</i></span><b class="triangle"></b><div class="tooltip"><a class="tooltip_close">×</a><?=$arProp["HINT"]?></div></div><?endif;?><?=$arProp["NAME"]?></span>
												</td>
												<td class="char_value">
													<span>
														<?if(count($arProp["DISPLAY_VALUE"]) > 1):?>
															<?=implode(', ', $arProp["DISPLAY_VALUE"]);?>
														<?else:?>
															<?=$arProp["DISPLAY_VALUE"];?>
														<?endif;?>
													</span>
												</td>
											</tr>
										<?endif;?>
									<?endif;?>
								<?endforeach;?>
							</table>
						<?endif;?>
					</li>
				<?endif;?>
				<?if($arParams["SHOW_ADDITIONAL_TAB"] == "Y"):?>
					<li class="tabs-content__item | <?=(!($iTab++) ? ' cur' : '')?>">
						<?$APPLICATION->IncludeFile(SITE_DIR."include/additional_products_description.php", array(), array("MODE" => "html", "NAME" => GetMessage('CT_NAME_INSTRUCTIONS')));?>
						<?if($arFiles):?>
							<div class="files-list">
								<?foreach($arFiles as $arItem):?>
									<?$arItem = CFile::GetFileArray($arItem);?>
									<div class="files-list__item | file_type clearfix<? if( $arItem["CONTENT_TYPE"] == 'application/pdf' ){ echo " pdf"; } elseif( $arItem["CONTENT_TYPE"] == 'application/octet-stream' ){ echo " word"; }
									elseif( $arItem["CONTENT_TYPE"] == 'application/xls' ){ echo " excel"; } elseif( $arItem["CONTENT_TYPE"] == 'image/jpeg' ){ echo " jpg"; } elseif( $arItem["CONTENT_TYPE"] == 'image/tiff' ){ echo " tiff"; }?>">
										<span class="files-list__icon icon-wrapper">
											<?= \Local\Svg::get('ic-file', 'icon icon_file icon_pink') ;?>
										</span>
										<div class="files-list__description">
											<?$fileName = substr($arItem["ORIGINAL_NAME"], 0, strrpos($arItem["ORIGINAL_NAME"], '.'));?>
											<a class="files-list__name" target="_blank" href="<?=$arItem["SRC"]?>"><?if ($arItem["DESCRIPTION"]):?><?=$arItem["DESCRIPTION"]?><?elseif($fileName):?><?=$fileName?><?endif;?></a>
											<span class="files-list__size"><?=GetMessage('CT_NAME_SIZE')?>:
												<?
												$filesize = $arItem["FILE_SIZE"];
												if($filesize > 1024){
													$filesize = ($filesize / 1024);
													if($filesize > 1024){
														$filesize = ($filesize / 1024);
														if($filesize > 1024){
															$filesize = ($filesize / 1024);
															$filesize = round($filesize, 1);
															echo $filesize.GetMessage('CT_NAME_GB');
														}
														else{
															$filesize = round($filesize, 1);
															echo $filesize.GetMessage('CT_NAME_MB');
														}
													}
													else{
														$filesize = round($filesize, 1);
														echo $filesize.GetMessage('CT_NAME_KB');
													}
												}
												else{
													$filesize = round($filesize, 1);
													echo $filesize.GetMessage('CT_NAME_b');
												}
												?>
											</span>
										</div>
									</div>
								<?endforeach;?>
							</div>
						<?endif;?>
					</li>
				<?endif;?>
				<?if(($arParams["SHOW_ASK_BLOCK"] == "Y") && (intVal($arParams["ASK_FORM_ID"]))):?>
					<li class="tabs-content__item ask-quesstion| <?=(!($iTab++) ? ' cur' : '')?>">
						<div id="ask_block"></div>
					</li>
				<?endif;?>
				<?if($arParams["USE_REVIEW"] == "Y"):?>
					<li class="tabs-content__item | reviews_block_wrapp <?=(!($iTab++) ? 'cur' : '')?>">
						<div id="reviews_block">
							<?$APPLICATION->IncludeComponent(
								"askaron:askaron.reviews.for.element",
								"main",
								Array(
									"AJAX_MODE" => "N",
									"AJAX_OPTION_ADDITIONAL" => "",
									"AJAX_OPTION_HISTORY" => "N",
									"AJAX_OPTION_JUMP" => "N",
									"AJAX_OPTION_STYLE" => "Y",
									"CACHE_TIME" => "3600",
									"CACHE_TYPE" => "N",
									"DISPLAY_BOTTOM_PAGER" => "Y",
									"ELEMENT_ID" => $arResult["ID"],
									"NEW_REVIEW_FORM" => "Y",
									"PAGER_TEMPLATE" => ".default",
									"PAGE_ELEMENT_COUNT" => "20",
									"SCHEMA_ORG_INSIDE_PRODUCT" => "N"
								)
							);?>
						</div>
					</li>
				<?endif;?>
				<li class="tabs-content__item | delivery-text <?=(!($iTab++) ? ' cur' : '')?>">
					<?$APPLICATION->IncludeFile(SITE_DIR."inc/detail_delivery_text.php", Array(), Array("MODE" => "html", "NAME" => 'Доставка и получение товара'));?>
				</li>
			</ul>
		</div>
	</div>
		</div>
	</div>
</section>

<?if($arResult['OFFERS']):?>
	<?if($arResult['OFFER_GROUP']):?>
		<?foreach($arResult['OFFERS'] as $arOffer):?>
			<?if(!$arOffer['OFFER_GROUP']) continue;?>
			<span id="<?=$arItemIDs['OFFER_GROUP'].$arOffer['ID']?>" style="display: none;">
				<?$APPLICATION->IncludeComponent("bitrix:catalog.set.constructor", "main",
					array(
						"IBLOCK_ID" => $arResult["OFFERS_IBLOCK"],
						"ELEMENT_ID" => $arOffer['ID'],
						"PRICE_CODE" => $arParams["PRICE_CODE"],
						"BASKET_URL" => $arParams["BASKET_URL"],
						"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
						"CACHE_TYPE" => $arParams["CACHE_TYPE"],
						"CACHE_TIME" => $arParams["CACHE_TIME"],
						"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
					), $component, array("HIDE_ICONS" => "Y")
				);?>
			</span>
		<?endforeach;?>
	<?endif;?>
<?else:?>
	<?$APPLICATION->IncludeComponent("bitrix:catalog.set.constructor", "main",
		array(
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			"ELEMENT_ID" => $arResult["ID"],
			"PRICE_CODE" => $arParams["PRICE_CODE"],
			"BASKET_URL" => $arParams["BASKET_URL"],
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],
			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		), $component, array("HIDE_ICONS" => "Y")
	);?>
<?endif;?>

<?if ($arResult['PROPERTIES']['KOMPLEKT']['VALUE']):?>
	<div class="product-slider js-product-gallery">
		<div class="product-slider__inner">
			<?$GLOBALS['arrFilterComplect'] = array( "ID" => $arResult['PROPERTIES']['KOMPLEKT']['VALUE'] );?>
			<?$APPLICATION->IncludeComponent("bitrix:catalog.top", "kare_slider", array(
				"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
				"IBLOCK_ID" => $arParams["IBLOCK_ID"],
				"ELEMENT_SORT_FIELD" => $arParams['ELEMENT_SORT_FIELD'],
				"ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
				"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
				"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
				"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
				"ELEMENT_COUNT" => "12",
				"LINE_ELEMENT_COUNT" => "4",
				"PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
				"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
				"OFFERS_PROPERTY_CODE" => $arParams["OFFERS_PROPERTY_CODE"],
				"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
				"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
				"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
				"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
				"OFFERS_LIMIT" => $arParams["OFFERS_LIMIT"],
				"SECTION_URL" => $arParams["SECTION_URL"],
				"DETAIL_URL" => $arParams["DETAIL_URL"],
				"BASKET_URL" => $arParams["BASKET_URL"],
				"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
				"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
				"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
				"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
				"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
				"CACHE_TYPE" => $arParams["CACHE_TYPE"],
				"CACHE_TIME" => $arParams["CACHE_TIME"],
				"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
				"DISPLAY_COMPARE" => $arParams["DISPLAY_COMPARE"],
				"PRICE_CODE" => $arParams["PRICE_CODE"],
				"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
				"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
				"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
				"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
				"USE_PRODUCT_QUANTITY" =>$arParams["USE_PRODUCT_QUANTITY"],
				"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
				"CURRENCY_ID" => $arParams["CURRENCY_ID"],
				"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
				"FILTER_NAME" => "arrFilterComplect",
				"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
				'TITLE_SECTION' => 'Другие товары данного комплекта',
				'LINK_ALL' => 'N',
				//'SECTION_PAGE_FILTER' => $arResult['SECTION']['SECTION_PAGE_URL'].'?set_filter=Y&KSHOP_SMART_FILTER_133_'.crc32($arResult['PROPERTIES']['STIL']['VALUE_ENUM_ID'][0]).'=Y',
				),
				false
			);?>
		</div>
	</div>
<?endif;?>
<?if ($arResult['COLLECTION_PRODUCT_ID']):?>
	<div class="product-slider js-product-gallery">
		<div class="product-slider__inner">
				<?$GLOBALS['arrFilterCollection'] = array( "ID" => $arResult['COLLECTION_PRODUCT_ID'] );?>
				<?$APPLICATION->IncludeComponent("bitrix:catalog.top", "kare_slider", array(
					"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
					"IBLOCK_ID" => $arParams["IBLOCK_ID"],
					"ELEMENT_SORT_FIELD" => 'RAND',
					"ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
					/*"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
					"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],*/
					"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
					"ELEMENT_COUNT" => "12",
					"LINE_ELEMENT_COUNT" => "4",
					"PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
					"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
					"OFFERS_PROPERTY_CODE" => $arParams["OFFERS_PROPERTY_CODE"],
					"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
					"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
					"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
					"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
					"OFFERS_LIMIT" => $arParams["OFFERS_LIMIT"],
					"SECTION_URL" => $arParams["SECTION_URL"],
					"DETAIL_URL" => $arParams["DETAIL_URL"],
					"BASKET_URL" => $arParams["BASKET_URL"],
					"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
					"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
					"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
					"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
					"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
					"CACHE_TYPE" => $arParams["CACHE_TYPE"],
					"CACHE_TIME" => $arParams["CACHE_TIME"],
					"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
					"DISPLAY_COMPARE" => $arParams["DISPLAY_COMPARE"],
					"PRICE_CODE" => $arParams["PRICE_CODE"],
					"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
					"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
					"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
					"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
					"USE_PRODUCT_QUANTITY" =>$arParams["USE_PRODUCT_QUANTITY"],
					"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
					"CURRENCY_ID" => $arParams["CURRENCY_ID"],
					"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
					"FILTER_NAME" => "arrFilterCollection",
					"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
					'TITLE_SECTION' => 'Другие товары в коллекции',//.$arResult['PROPERTIES']['STIL']['VALUE'][0],
					'LINK_ALL' => 'N',
					'LINK_ALL_NAME' => 'Все товары в коллекции',
					'SECTION_PAGE_FILTER' => $arResult['COLLECTION_LINK'],
					),
					false
				);?>
			</div>
		<?if ($arResult['COLLECTION_BACKGROUND']):?>
		</div>
		<?endif;?>
	</div>
	<script>
		$(document).ready(function() {
			setTimeout(function() {
				var parentBlockH = $('.analog_product.collection').height();
				var blockH = $('.enable_background .products_wrap').height();
				if (parentBlockH > blockH) {
					var paddingBlockTop = (parentBlockH - blockH)/2;
					$('.enable_background').css('padding-top', paddingBlockTop);
				}
			},10);
		});
	</script>
<?endif;?>
<?if(!empty($arResult["PROPERTIES"]["ASSOCIATED"]["VALUE"])):?>
	<div class="bg_white analog_product analog">
		<div class="wrapper_inner">
			<?$GLOBALS['arrFilterAssociated'] = array( "ID" => $arResult["PROPERTIES"]["ASSOCIATED"]["VALUE"] );?>
			<?$APPLICATION->IncludeComponent("bitrix:catalog.top", "kare_slider", array(
				"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
				"IBLOCK_ID" => $arParams["IBLOCK_ID"],
				"ELEMENT_SORT_FIELD" => $arParams['ELEMENT_SORT_FIELD'],
				"ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
				"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
				"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
				"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
				"ELEMENT_COUNT" => "12",
				"LINE_ELEMENT_COUNT" => "4",
				"PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
				"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
				"OFFERS_PROPERTY_CODE" => $arParams["OFFERS_PROPERTY_CODE"],
				"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
				"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
				"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
				"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
				"OFFERS_LIMIT" => $arParams["OFFERS_LIMIT"],
				"SECTION_URL" => $arParams["SECTION_URL"],
				"DETAIL_URL" => $arParams["DETAIL_URL"],
				"BASKET_URL" => $arParams["BASKET_URL"],
				"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
				"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
				"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
				"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
				"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
				"CACHE_TYPE" => $arParams["CACHE_TYPE"],
				"CACHE_TIME" => $arParams["CACHE_TIME"],
				"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
				"DISPLAY_COMPARE" => $arParams["DISPLAY_COMPARE"],
				"PRICE_CODE" => $arParams["PRICE_CODE"],
				"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
				"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
				"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
				"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
				"USE_PRODUCT_QUANTITY" =>$arParams["USE_PRODUCT_QUANTITY"],
				"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
				"CURRENCY_ID" => $arParams["CURRENCY_ID"],
				"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
				"FILTER_NAME" => "arrFilterAssociated",
				"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
				'TITLE_SECTION' => 'Аналогичные товары',
				'LINK_ALL' => 'N'
				),
				false
			);?>
		</div>
	</div>
<?endif;?>
<script type="text/javascript">
$(".open_stores .availability-row .value").click(function(){
	if($(".stores_tab").length){
		$(".stores_tab").addClass("cur").siblings().removeClass("cur");
	}
	else{
		$(".prices_tab").addClass("cur").siblings().removeClass("cur");
		if($(".prices_tab .property.opener").length && !$(".prices_tab .property.opener .opened").length){
			var item = $(".prices_tab .property.opener").first();
			item.find(".opener_icon").addClass("opened");
			item.parents("tr").next(".offer_stores").find(".stores_block_wrap").slideDown(200);
		}
	}
});

$(".opener").click(function(){
	$(this).find(".opener_icon").toggleClass("opened");
	var showBlock = $(this).parents("tr").toggleClass("nb").next(".offer_stores").find(".stores_block_wrap");
	showBlock.slideToggle(200);
});

$(".tabs_section .tabs li").live("click", function(){
	if(!$(this).is(".cur")){
		$(".tabs_section .tabs li").removeClass("cur");
		$(this).addClass("cur");
		$(".tabs_section ul.tabs_content li").removeClass("cur");
		$(".tabs_section ul.tabs_content > li:eq("+$(this).index()+")").addClass("cur");
	}
});

$(".hint .icon").click(function(e){
	var tooltipWrapp = $(this).parents(".hint");
	tooltipWrapp.click(function(e){e.stopPropagation();})
	if(tooltipWrapp.is(".active")){
		tooltipWrapp.removeClass("active").find(".tooltip").slideUp(200);
	}
	else{
		tooltipWrapp.addClass("active").find(".tooltip").slideDown(200);
		tooltipWrapp.find(".tooltip_close").click(function(e){
			e.stopPropagation(); tooltipWrapp.removeClass("active").find(".tooltip").slideUp(100);
		});
		$(document).click(function(){
			tooltipWrapp.removeClass("active").find(".tooltip").slideUp(100);
		});
	}
});
</script>

<script>
	function resize_image_detail(el, rightEl) {
		var winW = $(window).width();
		var maxElW = 440;
		var maxWinW = 1000;
		console.log(winW);
		if (winW < maxWinW) {

			var koeff = maxElW*(maxWinW - winW)/maxWinW;
			el.width(maxElW - koeff).height(maxElW - koeff);
			rightEl.css('padding-left', el.width() + 92);
			$('.catalog_detail .item_main_info .hr-before').css('left',  el.width() + 90);
		}else{

			el.width(maxElW).height(maxElW);
			rightEl.css('padding-left', el.width() + 92);
			$('.catalog_detail .item_main_info .hr-before').css('left',  el.width() + 90);
		}
	}
	$(document).ready(function() {
		resize_image_detail($('.catalog_detail .item_slider:not(.flex) ul.slides li>div'), $('.catalog_detail .item_main_info .right_info'));
		$('.more_text').on('click', function() {
			var topEl = $('.main_tabs li').offset().top;
			$('.main_tabs li').first().click();
			$('html, body').animate({scrollTop : topEl - 20},200);
		});
		var ratingHtml = $('#rating').html();
		$('#rating').detach();
		$('#rating_wrap').html(ratingHtml);
		$("#rating_wrap table").on('click', function(){
			$(".tabs #product_reviews_tab").addClass("cur").siblings().removeClass("cur");
			$(".tabs_content .reviews_block_wrapp").addClass("cur").siblings().removeClass("cur");
			$('html, body').animate({scrollTop : $(".tabs #product_reviews_tab").offset().top - 20},200);
		});
		$('body').on('click', '#one_click_buy_form_button', function() {
			ga('send', 'event', 'Button4', 'Buttonclick5');
		});
		// $("img.img_preview").elevateZoom({ zoomType : "inner", cursor: "crosshair" });
		// $("img.img_preview").elevateZoom({scrollZoom : true});
		$("img.img_preview").elevateZoom({easing : true});
	});
	$(window).resize(function() {
		resize_image_detail($('.catalog_detail .item_slider:not(.flex) ul.slides li>div'), $('.catalog_detail .item_main_info .right_info'));
	});
</script>