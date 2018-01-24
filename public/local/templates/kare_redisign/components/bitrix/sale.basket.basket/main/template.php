<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixBasketComponent $component */
global $APPLICATION;
$curPage = $APPLICATION->GetCurPage().'?'.$arParams["ACTION_VARIABLE"].'=';
$arUrls = array(
	"delete" => $curPage."delete&id=#ID#",
	"delay" => $curPage."delay&id=#ID#",
	"add" => $curPage."add&id=#ID#",
);
unset($curPage);

$arBasketJSParams = array(
	'SALE_DELETE' => GetMessage("SALE_DELETE"),
	'SALE_DELAY' => GetMessage("SALE_DELAY"),
	'SALE_TYPE' => GetMessage("SALE_TYPE"),
	'TEMPLATE_FOLDER' => $templateFolder,
	'DELETE_URL' => $arUrls["delete"],
	'DELAY_URL' => $arUrls["delay"],
	'ADD_URL' => $arUrls["add"]
);
?>
	<script type="text/javascript">
		var basketJSParams = <?=CUtil::PhpToJSObject($arBasketJSParams);?>
	</script>
<? $APPLICATION->AddHeadScript($templateFolder."/script.js");?>

	<h1 class="main-catalog__title js-cart-title"><?=$arResult['BASKET_TYPES']['AnDelCanBuy']['SHOW_DELAY'] ? 'Корзина' : 'Избранное'?></h1>

<? if ($arResult['AJAX'] == 'Y') {
	$APPLICATION->RestartBuffer();
}?>

	<div class="js-tabs-cart" data-header-info="<?=$arResult['JSON'] ?>">
		<? //if (strlen($arResult["ERROR_MESSAGE"]) <= 0)
		{
			?>
			<div id="warning_message">
				<?
				if (!empty($arResult["WARNING_MESSAGE"]) && is_array($arResult["WARNING_MESSAGE"]))
				{
					foreach ($arResult["WARNING_MESSAGE"] as $v)
						ShowError($v);
				}
				?>
			</div>

			<form method="post" action="<?=POST_FORM_ACTION_URI?>" name="basket_form" id="basket_form">
				<div class="cart__links js-cart__links">
					<?foreach ($arResult['BASKET_TYPES'] as $key => $value):?>
						<span class="inner-link js-tab-link <?=$value['SHOW_DELAY'] ? 'active' : ''?>" data-code="<?=$value['CODE']?>" data-name="<?=$value['NAME']?>"><?=$value['NAME']?></span>
					<?endforeach;?>
				</div>


				<?foreach ($arResult['BASKET_TYPES'] as $key => $value):?>
					<div class="tab-content js-tab-content <?=$value['SHOW_DELAY'] ? 'active' : ''?>">


					<?if( count($arResult['ITEMS'][$key]) > 0) {
						$emptyTableClass = '';
						$emptyMessageClass = 'hidden';
					}else {
						$emptyMessageClass = '';
						$emptyTableClass = 'hidden';
					}?>
						<div class="cart__table-wrap js-cart__table-wrap <?=$emptyTableClass?>">
						<div class="cart__table-overflow">
							<table class="cart__table cart__table-withGrayHead <?=($value['CODE'] == 'cart') ? 'js-cart' : 'js-fav'?>" id="<?=($value['CODE'] == 'cart') ? 'basket_items' : 'delayed_items'?>">
								<thead>
								<tr>
									<th class="cart__delete"></th>
									<th class="cart__pic"></th>
									<th class="cart__name">Название</th>
									<th class="cart__count">Количество</th>
									<th class="cart__price-td">Цена</th>
									<th class="cart__sum">Сумма</th>
									<th class="cart__fav"></th>
								</tr>
								</thead>
								<tbody>
								<?foreach ($arResult['ITEMS'][$key] as $item):?>
									<tr id="<?=$item["ID"]?>">
										<td class="cart__delete">
											<a href="<?=str_replace("#ID#", $item["ID"], $arUrls["delete"])?>" class="butn btn-delete js-cart-delete"></a>
										</td>
										<td class="cart__pic">
											<a href="<?=$item['DETAIL_PAGE_URL']?>" class="cart__img">
												<img src="<?=$item['PHOTO']?>" alt="<?=$item['NAME']?>">
											</a>
										</td>
										<td class="cart__name">
											<a href="<?=$item['DETAIL_PAGE_URL']?>"><?=$item['NAME']?></a>
											<div class="cart__info">
												<? foreach ($item['PROPS'] as $property): ?>
													<span><?=$property['NAME']?> - <?=$property['VALUE']?></span><br>
												<? endforeach; ?>
											</div>
										</td>
										<td class="cart__count">
											<div class="spinner <?=($value['CODE'] == 'cart') ? 'js-cart-counter' : ''?>" data-id="<?=$item['ID'];?>">
												<span class="spinner__minus js-minus">-</span>
												<input type="text" name="QUANTITY_INPUT_<?=$item["ID"]?>" id="QUANTITY_INPUT_<?=$item["ID"]?>" min="0" value="<?=$item['QUANTITY']?>" class="js-counter-input js-numeric-input" <?=($value['CODE'] !== 'cart') ? 'readonly' : ''?>>
												<input type="hidden" name="QUANTITY_<?=$item["ID"]?>" class="js-counter-hidden" id="QUANTITY_<?=$item["ID"]?>" value="<?=$item['QUANTITY']?>" <?=($value['CODE'] !== 'cart') ? 'disabled' : ''?>>
												<span class="spinner__plus js-plus">+</span>
											</div>
											<? /* <span class="cart__status-yes"><?=$item['AVAILABLE_STATUS']?></span> */ ?>
											<input type="hidden" name="DELAY_<?=$item["ID"]?>" value="Y" class="js-delay-input" <?= ($item['DELAY'] !== 'Y') ? 'disabled' : '' ?>>
										</td>
										<td class="cart__price-td">
											<span class="cart__price" data-price="<?=$item['PRICE']?>"><?=$item['PRICE_FORMATED']?></span>
										</td>
										<td class="cart__sum">
											<span class="cart__price cart__price_sum js-sum" data-sum="<?=$item['QUANTITY']*$item['PRICE']?>"><?=($key == 'AnDelCanBuy') ? $item['SUM'] : number_format($item['QUANTITY']*$item['PRICE'], 0, '', ' ').' <span class="rub"><span class="text">р.</span></span>'?></span>
										</td>
										<td class="cart__fav">
											<a href="<?=str_replace("#ID#", $item["ID"], $arUrls["delay"])?>" title="Добавить в WishList" class="basket-button wish_item js-to-wishlist <?=($key == 'AnDelCanBuy') ? '' : 'hidden'?>" rel="nofollow">
												<div class="icon-wrapper icon-wrapper_like">
													<?
													echo \Local\Svg::get('ic-like', 'icon icon_like icon_white');
													?>
												</div>
											</a>
											<a href="<?=str_replace("#ID#", $item["ID"], $arUrls["add"])?>" title="Добавить в корзину" class="basket-button wish_item js-from-wishlist <?=($key == 'AnDelCanBuy') ? 'hidden' : ''?>" rel="nofollow">
												<div class="icon-wrapper icon-wrapper_like">
													<?
													echo \Local\Svg::get('ic-basket', 'icon icon_basket icon_white');
													?>
												</div>
											</a>

										</td>
									</tr>
								<?endforeach;?>
								</tbody>
							</table>
						</div>


						<?if ($key == 'AnDelCanBuy') :?>
							<div class="cart__total">
								<div class="cart__total-text">Итого к оплате:</div>
								<div class="cart__total-sum js-total" data-total="<?=$arResult['allSum']?>"><?=$arResult['allSum_FORMATED']?></div>
							</div>
							<div class="cart__ctrl">
								<a href="/catalog/" class="butn btn-gray">продолжить покупки</a>
								<input type="submit" value="Подтвердить заказ" name="" id="" class="butn btn-base">
							</div>
						<?elseif ($key == 'DelDelCanBuy') :?>
							<div class="cart__print">
								<a class="butn btn-base js-print">Распечатать WishList</a>
							</div>
						<?endif?>
					</div>

					<div class="empty-list-block js-empty-list-block <?=$emptyMessageClass?>">
						<p class="empty-mes">Список пуст.</p>
						<div class="cart__ctrl">
							<a href="/catalog/" class="butn btn-gray">продолжить покупки</a>
						</div>
					</div>
				</div>

				<? endforeach;?>

				<input type="hidden" name="BasketOrder" value="BasketOrder" />
				<input type="hidden" id="column_headers" value="<?=CUtil::JSEscape(implode($arResult['arHeaders'], ","))?>" />
				<input type="hidden" id="offers_props" value="<?=CUtil::JSEscape(implode($arParams["OFFERS_PROPS"], ","))?>" />
				<input type="hidden" id="action_var" value="<?=CUtil::JSEscape($arParams["ACTION_VARIABLE"])?>" />
				<input type="hidden" id="quantity_float" value="<?=$arParams["QUANTITY_FLOAT"]?>" />
				<input type="hidden" id="count_discount_4_all_quantity" value="<?=($arParams["COUNT_DISCOUNT_4_ALL_QUANTITY"] == "Y") ? "Y" : "N"?>" />
				<input type="hidden" id="price_vat_show_value" value="<?=($arParams["PRICE_VAT_SHOW_VALUE"] == "Y") ? "Y" : "N"?>" />
				<input type="hidden" id="hide_coupon" value="<?=($arParams["HIDE_COUPON"] == "Y") ? "Y" : "N"?>" />
				<input type="hidden" id="use_prepayment" value="<?=($arParams["USE_PREPAYMENT"] == "Y") ? "Y" : "N"?>" />
			</form>
			<?
		}
		/*else
		{
			ShowError($arResult["ERROR_MESSAGE"]);
		} */?>
	</div>
<? if (!empty($arResult['AJAX']) && ($arResult['AJAX'] == 'Y')) {
	die();
} ?>