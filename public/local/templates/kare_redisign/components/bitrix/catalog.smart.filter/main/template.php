<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode( true );
?>

<?
	if (!empty($_COOKIE["KSHOP_FILTER_CLOSED"]))
	{
		$arCookies =  json_decode($_COOKIE["KSHOP_FILTER_CLOSED"]);
		array_unique ($arCookies);
		unset ($_COOKIE["KSHOP_FILTER_CLOSED"]);
		setcookie ($_COOKIE["KSHOP_FILTER_CLOSED"], null, -1);
		foreach($arCookies as $key => $value)
		{
			foreach($arResult["ITEMS"] as $key=>$property)
			{
				if ($property["ID"]==$value) $arResult["ITEMS"][$key]["OPENED"]="N";
			}
		}
		if (in_array("specials", $arCookies)) $arResult["SPECIALS_BLOCK"]["OPENED"]="N";
	}
	if (!empty($_COOKIE["KSHOP_FILTER_OPENED"]))
	{
		$arCookies =  json_decode($_COOKIE["KSHOP_FILTER_OPENED"]);
		array_unique ($arCookies);
		unset ($_COOKIE["KSHOP_FILTER_OPENED"]);
		setcookie ($_COOKIE["KSHOP_FILTER_OPENED"], null, -1);
		foreach($arCookies as $key => $value)
		{
			foreach($arResult["ITEMS"] as $key=>$property)
			{
				if ($property["ID"]==$value) $arResult["ITEMS"][$key]["OPENED"]="Y";
			}
		}
		if (in_array("specials", $arCookies)) $arResult["SPECIALS_BLOCK"]["OPENED"]="Y";
	}

	/*foreach( $arResult["ITEMS"] as $key => $arItem )
	{
		if( $arItem["PROPERTY_TYPE"] == "N" || !$arItem["PROPERTY_TYPE"] )
		{
			continue;
		}
		elseif( $arItem["VALUES"] )
		{
			$count = $countWithChecked = $checkedIndex = 0;
			foreach ($arItem["VALUES"] as $i => $arValue)
			{
				$countWithChecked++;
				if (!$arValue["CHECKED"])
				{
					$count++;
					if ($count >= 5)
					{
						$arResult["ITEMS"][$key]["OPENED"] = "N";
						$arResult["ITEMS"][$key]["MAX_OPENED_COUNT"] = $countWithChecked;
						break;
					}
				}
				else
				{
					$arResult["ITEMS"][$key]["VALUES"][$i]["CHECKED_INDEX"] = $checkedIndex;
					$checkedIndex++;
				}
			}

			//var_dump($arResult["ITEMS"][$key]["MAX_OPENED_COUNT"]);
		}
	}	*/
?>
<?
$clear_url = $_SERVER["REDIRECT_URL"].'?';
if( !empty( $_REQUEST["sort"] ) ){
	$clear_url .= '&sort='.htmlspecialchars($_REQUEST["sort"]);
}
if( !empty( $_REQUEST["order"] ) ){
	$clear_url .= '&order='.htmlspecialchars($_REQUEST["order"]);
}
if( !empty( $_REQUEST["q"] ) ){
	$clear_url .= '&q='.htmlspecialchars($_REQUEST["q"]);
}
if( !empty( $_REQUEST["show"] ) ){
	$clear_url .= '&show='.htmlspecialchars($_REQUEST["show"]);
}
?>

<?if( $arResult["ITEMS"] ){?>
	<div class="bx_filter_vertical js-mobile-filter">
		<div class="bx_filter_section m4">
			<form name="<?echo $arResult["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get" class="smartfilter">
				<?$iOpened=0;$firstTitle=true;?>
				<div>
					<div class="bx_filter_container">
						<div class="bx_filter_reset">
							<a href="<?echo $arResult['FORM_ACTION']?>" class="bx_filter_reset_btn">Очистить фильтр
								<div class="icon-wrapper icon-wrapper_close-filter" href="/">
									<?= \Local\Svg::get('close', 'icon icon_close') ;?>
								</div>
							</a>
						</div>
					</div>
					<?foreach($arResult["ITEMS"] as $key=>$arItem){?>
						<?if( $arItem["PROPERTY_TYPE"] == "N" || !$arItem["PROPERTY_TYPE"] ){?>
							<?$min_name = $arItem["VALUES"]["MIN"]["CONTROL_NAME"];
							$max_name = $arItem["VALUES"]["MAX"]["CONTROL_NAME"];

							$min_num = 100;
							if( $arItem["VALUES"]["MIN"]["VALUE"] < 100 ) $min_num = 10;
							$min = ( floor( $arItem["VALUES"]["MIN"]["VALUE"] / $min_num )) * $min_num;

							$min = $min < 1 ? 0 : $min;
							$max = ( ceil( $arItem["VALUES"]["MAX"]["VALUE"] / 100 ) ) * 100;

							$cur_min = $arItem["VALUES"]["MIN"]["HTML_VALUE"];
							$cur_min = $cur_min ? $cur_min : '';

							$cur_max = $arItem["VALUES"]["MAX"]["HTML_VALUE"];
							$cur_max = $cur_max ? $cur_max : '';

							$step = !$arItem["PROPERTY_TYPE"] ? 100 : 10;

							if( $min == $max ){
								continue;
							}?>
							<?$iOpened++;?>
							<div class="bx_filter_container js-slider <?if($arItem["OPENED"]=="N" || ($iOpened>3 && $arItem["OPENED"]!="Y")){}else{?> active<?}?>"
								data-min="<?=$cur_min?>" data-max="<?=$cur_max?>"
								data-abs-min="<?=$min?>" data-abs-max="<?=$max?>"
								data-callback="sendQuery( '' )" property_id="<?=$arItem["ID"]?>">

								<div class="bx_filter_container_title"><?=$arItem["NAME"]?>
									<div class="icon-wrapper icon-wrapper_filter" href="/">
										<?= \Local\Svg::get('ic-arrow-left', 'icon icon_arrow') ;?>
									</div>
								</div>
								<div class="bx_filter_block<?if($arItem["OPENED"]=="Y" || ($iOpened<=3 && $arItem["OPENED"]!="N")):?> active<?endif;?>"<?if($arItem["OPENED"]=="N" || ($iOpened>3 && $arItem["OPENED"]!="Y")):?> style="display:none;"<?endif;?>>
									<div class="inputs bx_ui_slider_track_values">
										<span class="track_ot">от</span>
										<input type="text" class="min-price js-min" name="<?=$min_name?>" value="" />
										<span class="track_do">до</span>
										<input type="text" class="max-price js-max" name="<?=$max_name?>" value="" />
										<div class="cls"></div>
									</div>
									<div class="slider-range slider bx_ui_slider_track"></div>
								</div>
							</div>
						<?}elseif( $arItem["CODE"]=="KATEGORIYA" && !CSite::InDir(SITE_DIR.'catalog/index.php') && !CSite::InDir(SITE_DIR.'action/index.php')){
						/*}elseif(($arItem["CODE"]=="HIT")&&strlen($arResult["SPECIALS_BLOCK"]["HTML"])){*/
							continue;
						}elseif(!empty($arItem["VALUES"]) && !isset($arItem["PRICE"])){?>
							<?//if($arItem["CODE"] == "HIT"){continue;}?>
							<?foreach($arItem["VALUES"] as $val => $ar):?>
								<? if ($ar["CHECKED"]) { $arItem["OPENED"]="Y"; } ?>
							<?endforeach;?>
							<?
								$iOpened++;
								$hideContent = 5;

							?>
							<div class="bx_filter_container<?if($arItem["OPENED"]=="N" || ($iOpened>3 && $arItem["OPENED"]!="Y")){}else{?> active<?}?>" property_id="<?=$arItem["ID"]?>" property_xml_id="<?=$arItem["XML_ID"]?>" id="block_<?=$arItem["ID"]?>">
								<?if ($arItem['CODE'] != 'AVAILABLE' && $arItem['CODE'] != 'HIT'):?>
									<div class="link2 bx_filter_container_title"><?=$arItem["NAME"]?><?if ($prop["HINT"]):?><span class="hint"><span class="hint_icon"><i>?</i></span><b class="triangle"></b><div class="tooltip"><a class="tooltip_close">×</a><?=$prop["HINT"]?></div></span><?endif;?>
										<div class="icon-wrapper icon-wrapper_filter" href="/">
											<?= \Local\Svg::get('ic-arrow-left', 'icon icon_arrow') ;?>
										</div>
									</div>
								<?endif;?>
								<div <?if($arItem["OPENED"]=="N" || ($iOpened>3 && $arItem["OPENED"]!="Y")):?> style="display:none;"<?endif;?> class="bx_filter_block<?if($arItem["OPENED"]=="Y" || ($iOpened<=3 && $arItem["OPENED"]!="N")):?> active<?endif;?>"><div>
									<?/*foreach($arItem["VALUES"] as $val => $ar):
										if ($ar['CHECKED'] == true && $val > 4) {
											$displayBlock = true;
											break;
										}else{
											$displayBlock = false;
										}
									endforeach;*/?>
									<?if (count($arItem["VALUES"]) > 5):?>
										<div class="filter_search"><input class="js-search-filter" type="text" name="" value="" placeholder="Поиск" /><span class="del_filter_search"></span></div>
									<?endif;?>
									<?$containerOpened=false; $counter=$showedCount=0;?>
									<?foreach($arItem["VALUES"] as $val => $ar):?>
										<?if ($counter>=$hideContent && !$ar["CHECKED"] && !$containerOpened){?>
											<div class="container" style="display:none;">
											<?$containerOpened=true;?>
										<?}elseif ($counter>=$hideContent && $ar["CHECKED"] && $containerOpened){?>
											</div>
											<?$containerOpened=false;?>
										<?}?>
										<div class="<?echo $ar["DISABLED"] ? 'disabled': ''?> input checkbox<?=(($arItem['CODE'] == 'AVAILABLE' || $arItem['CODE'] == 'HIT') ? ' '.strtolower($arItem['CODE']).'_filter' : '')?>">
											<input
												type="checkbox"
												value="<?echo $ar["HTML_VALUE"]?>"
												name="<?echo $ar["CONTROL_NAME"]?>"
												id="<?echo $ar["CONTROL_ID"]?>"
												<?echo $ar["CHECKED"]? 'checked="checked"': ''?>
												<?if ($arItem['CODE'] == "HIT"):?>data-url-id="<?=$ar["URL_ID"];?>"<?endif;?>
												<?if ($ar["DISABLED"]):?>disabled<?endif?>
											/>
											<label for="<?echo $ar["CONTROL_ID"]?>">
												<?=$ar["VALUE"];?>
												<?if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($ar["ELEMENT_COUNT"]) && $arItem['CODE'] != 'AVAILABLE' ):?>
													<span class="count">(<?=$ar["ELEMENT_COUNT"];?>)</span>
												<?endif;?>
											</label>
										</div>
										<?
											if ($counter<$hideContent || $ar["CHECKED"]) $showedCount++;
											$counter++;
										?>
									<?endforeach;?>
									<?if ($counter>$hideContent && $counter == count($arItem["VALUES"])){?>
										<?if ($containerOpened):?>
										</div>
										<?endif;?>
										<?if ($counter>$showedCount):?>
										<div class="open_link"><span>Показать все</span></div>
										<?endif;?>
										<?/*
										<div class="open_link<?if (($ar["CHECKED"] == 1) && ($counter > ($hideContent-1)) || $displayBlock == true){echo ' close';}?>"><span>
											<?=(($ar["CHECKED"] == 1) && ($counter > ($hideContent-1)) || $displayBlock == true ? 'Свернуть' : 'Показать все')?>
										</span></div>*/?>
									<?}?>
								</div></div>
								<span class="bx_filter_container_modef"></span>
							</div>
						<?}?>
					<?}?>
				</div>
				
				<?foreach ($arParams["REQUEST_PARAMS"] as $name => $val):?>
					<input type="hidden" name="<?=$name?>" value="<?=$val?>" />
				<?endforeach;?>
				
				<input type="hidden" name="set_filter" value="Y">
				<button class="bx_filter_submit_btn" type="submit">Применить</button>
			</form>
		</div>
		<div class="line"></div>
	</div>

	<script>
		var checkClosed = function(item)
		{
			$.cookie.json = true;
			var arClosed = $.cookie("KSHOP_FILTER_CLOSED");
			if (arClosed && typeof arClosed != "undefined")
			{
				if (typeof item != "undefined")
				{
					var propID = item.parents(".bx_filter_container").attr("property_id");
					var delIndex = $.inArray(propID, arClosed);
					if (delIndex >= 0) { arClosed.splice(delIndex,1);} else {arClosed.push(propID);}
				}
			}
			else
			{
				var arClosed = [];
				if (typeof item != "undefined")
				{
					item = $(item);
					var propID = item.parents(".bx_filter_container").attr("property_id");
					if (!item.parents(".bx_filter_container").is(".active")) { if (!$.inArray(propID, arClosed) >= 0) { arClosed.push(propID); } }
						else { if ($.inArray(propID, arClosed) >= 0) { arClosed.splice(delIndex,1); } }
				}
			}
			$.cookie("KSHOP_FILTER_CLOSED", arClosed);
			return true;
		}

		var checkOpened = function(item)
		{

			$.cookie.json = true;
			var arOpened = $.cookie("KSHOP_FILTER_OPENED");
			if (arOpened && typeof arOpened != "undefined")
			{
				if (typeof item != "undefined")
				{
					var propID = item.parents(".bx_filter_container").attr("property_id");
					var delIndex = $.inArray(propID, arOpened);
					if (delIndex >= 0) { arOpened.splice(delIndex,1); checkClosed(item); }
						else { arOpened.push(propID); checkClosed(item); }
				}
				else
				{
					$(".bx_filter_container").each(function()
					{
						var propID = $(this).attr("property_id");
						if ($(this).is(".active")) { if ($.inArray(propID, arOpened) < 0) { arOpened.push(propID); checkClosed(item); } }
					});
				}
			}
			else
			{
				var arOpened = [];
				if (typeof item != "undefined")
				{
					item = $(item);
					var propID = item.parents(".bx_filter_container").attr("property_id");
					if (item.parents(".bx_filter_container").is(".active")) { if (!$.inArray(propID, arOpened) >= 0) { arOpened.push(propID); checkClosed(item); }  }
						else { if ($.inArray(propID, arOpened) >= 0) { arOpened.splice(delIndex,1); checkClosed(item); } }
				}
				else
				{
					$(".bx_filter_container").each(function()
					{
						var propID = $(this).attr("property_id");
						if ($(this).is(".active")) { if ($.inArray(propID, arOpened) < 0) { arOpened.push(propID); checkClosed(item); } }
					});
				}
			}
			$.cookie("KSHOP_FILTER_OPENED", arOpened);
			return true;
		}
		checkOpened();

		/*var smartFilter = new JCSmartFilter('<?#echo CUtil::JSEscape($arResult["FORM_ACTION"])?>');*/

		$(".bx_filter_container_title").click( function()
		{
			if ($(this).parents(".bx_filter_container").hasClass("active")) {
				$(this).next(".bx_filter_block").slideUp(100);
			}else{
				$(this).next(".bx_filter_block").slideDown(200).css('overflow', 'visible');
			}
			$(this).parents(".bx_filter_container").toggleClass("active");
			checkOpened($(this));
		});


		$(".hint .hint_icon").click(function(e)
		{
			var tooltipWrapp = $(this).parents(".hint");
			tooltipWrapp.click(function(e){e.stopPropagation();})
			if (tooltipWrapp.is(".active"))
			{
				tooltipWrapp.removeClass("active").find(".tooltip").slideUp(200);
			}
			else
			{
				tooltipWrapp.addClass("active").find(".tooltip").slideDown(200);
				tooltipWrapp.find(".tooltip_close").click(function(e) { e.stopPropagation(); tooltipWrapp.removeClass("active").find(".tooltip").slideUp(100);});
				$(document).click(function() { tooltipWrapp.removeClass("active").find(".tooltip").slideUp(100);});
			}
		});
		$( document ).ready( function(){
			$('.open_link>span').on('click', function() {
				//var text = 'Показать все';
				if (!$(this).parent().is('.close')) {
					$(this).text('Свернуть');
					$(this).parent().addClass('close');
					$(this).closest('.bx_filter_block').find('.container').slideDown(100);
				}else{
					$(this).text('Показать все');
					$(this).parent().removeClass('close');
					$(this).closest('.bx_filter_block').find('.container').slideUp(100);
				}
			});
			$( '.js-slider' ).filterSlider();
			// $( ".smartfilter input[type='checkbox']" ).change( function(){
			// 	console.log("change");
			// 	sendQuery( '' );
			// } );

			$( ".smartfilter label" ).click( function(){
				console.log("click");

			} );

			// $('.js-reset').click(function(){
			// 	sendQuery( $(this ).data('href') );
			// 	$(this).find('>span').addClass('loading');
			// });
			$('.input.checkbox input').each(function() {
				if ($(this).attr('disabled') == 'disabled') {
					$(this).parent().find('label').addClass('disable');
				}
			});

			$( '.js-search-filter' ).change( function(){
				$this = $( this );
				var val = $this.val();
				var item = $this.closest( '.bx_filter_block' );

				val = $.trim( val );
				item.find( '.input.checkbox' ).each( function(){
					( $( this ).text().search( new RegExp( val, "i" ) ) < 0 ) ? $( this ).hide() : $( this ).show();
				} );
				if (val != '') {
					item.find('.open_link').hide();
					item.find('.container').show();
				}else{
					item.find('.open_link').show();
					item.find('.container').hide();
				}
			} );

			$( '.js-search-filter' ).keyup( function(){
				$( this ).change();
			} );

			if ($(".bx_filter_vertical .available_filter").length)
			{
				var item = $(".bx_filter_vertical .available_filter").clone().wrapInner('<span class="extended"></span>');
				$(".bx_filter_vertical .available_filter").parents(".bx_filter_container").hide();
				$(item).find("input").removeAttr("name").removeAttr("id");
				$(".sort_filter_wrapper .extended_sort").append($(item));
				/*$(".sort_filter_wrapper .extended_sort span").on("click", function(e){
					e.preventDefault();
					$(".bx_filter_vertical .available_filter input[type='checkbox']").click();
				});
				$(".bx_filter_vertical .available_filter input[type='checkbox']").change(function () {
					if ($(".bx_filter_vertical .available_filter input[type='checkbox']").attr("checked")=="checked") {
						$(".sort_filter_wrapper .extended_sort .available_filter input[type='checkbox']").attr("checked", "checked");
					} else {
						$(".sort_filter_wrapper .extended_sort .available_filter input[type='checkbox']").removeAttr("checked");
					}
				});*/
			}


			if ($(".bx_filter_vertical .hit_filter").length)
			{
				var items = $(".bx_filter_vertical .hit_filter").clone().wrapInner('<span class="extended"></span>');
				$(".bx_filter_vertical .hit_filter").parents(".bx_filter_container").hide();
				$(items).find("input").removeAttr("name").removeAttr("id");

				$(items).each(function() {
					if ( $(this).find("[data-url-id]").attr("data-url-id") != "new" && $(this).find("[data-url-id]").attr("data-url-id") != "hit") {
						$(this).hide();
					}
					else if ($(this).find("[data-url-id]").attr("data-url-id") == "hit"){
						$(this).find("label").text("Хиты");
					}
					else if ($(this).find("[data-url-id]").attr("data-url-id") == "new"){
						$(this).find("label").text("Новинки");
					}
				});
				$(".sort_filter_wrapper .extended_sort").append($(items));
			}

			if ($(".sort_filter_wrapper .extended_sort .input").length)
			{
				$(".sort_filter_wrapper .extended_sort .extended").on("click", function(e){
					e.preventDefault();
					$(".bx_filter_vertical input#"+$(this).find("label").attr("for")).click();
				});

				$(".bx_filter_vertical .available_filter input[type='checkbox'], .bx_filter_vertical .hit_filter input[type='checkbox']").change(function ()
				{
					if ($(this).attr("checked")=="checked") {
						$(".sort_filter_wrapper .extended_sort label[for=" + $(this).attr("id") + "]").prev("input").attr("checked", "checked");
					} else {
						$(".sort_filter_wrapper .extended_sort label[for=" + $(this).attr("id") + "]").prev("input").removeAttr("checked");
					}
				});
			}
		})
	</script>
<?}?>