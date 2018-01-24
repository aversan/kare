<?
	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
	__IncludeLang($_SERVER["DOCUMENT_ROOT"].$templateFolder."/lang/".LANGUAGE_ID."/template.php");
?>
<?if($arParams["USE_RATING"] == "Y"):?>
	<div id="rating" style="display:none;">
		<div class="rating">
			<span class="block_title"><?=GetMessage("RATING");?></span>
			<?$APPLICATION->IncludeComponent(
			   "bitrix:iblock.vote",
			   "element_rating",
			   Array(
				  "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
				  "IBLOCK_ID" => $arResult["IBLOCK_ID"],
				  "ELEMENT_ID" =>$arResult["ID"],
				  "MAX_VOTE" => 5,
				  "VOTE_NAMES" => array(),
				  "CACHE_TYPE" => 'N',
				  //"CACHE_TIME" => $arParams["CACHE_TIME"],
				  "DISPLAY_AS_RATING" => 'vote_avg',
				  "READ_ONLY" => 'Y'
			   ),
			   $component, array("HIDE_ICONS" =>"Y")
			);?>
		</div>
	</div>
<?endif;?>

<?if($arResult["ID"]):?>
	<?if($arParams["SHOW_COMPARE"]):?>
		<div class="compare" id="compare">
			<?$APPLICATION->IncludeComponent(
				"bitrix:catalog.compare.list",
				"preview",
				Array(
					"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
					"IBLOCK_ID" => $arParams["IBLOCK_ID"],
					"AJAX_MODE" => "N",
					"AJAX_OPTION_JUMP" => "N",
					"AJAX_OPTION_STYLE" => "Y",
					"AJAX_OPTION_HISTORY" => "N",
					"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
					"COMPARE_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["compare"],
					"NAME" => "CATALOG_COMPARE_LIST",
					"AJAX_OPTION_ADDITIONAL" => ""
				)
			);?>
		</div>
	<?endif;?>
	<?if(($arParams["SHOW_ASK_BLOCK"] == "Y") && (intVal($arParams["ASK_FORM_ID"]))):?>
		<div id="ask_block_content">
			<?$APPLICATION->IncludeComponent(
				"bitrix:form.result.new",
				"questions_and_answers_s1",
				Array(
					"WEB_FORM_ID" => $arParams["ASK_FORM_ID"],
					"IGNORE_CUSTOM_TEMPLATE" => "N",
					"USE_EXTENDED_ERRORS" => "N",
					"SEF_MODE" => "N",
					"CACHE_TYPE" => "A",
					"CACHE_TIME" => "3600",
					"LIST_URL" => "",
					"EDIT_URL" => "",
					"SUCCESS_URL" => "?send=ok",
					"CHAIN_ITEM_TEXT" => "",
					"CHAIN_ITEM_LINK" => "",
					"VARIABLE_ALIASES" => Array("WEB_FORM_ID" => "WEB_FORM_ID", "RESULT_ID" => "RESULT_ID")
				)
			);?>
		</div>
	<?endif;?>
	<?if($arParams["USE_REVIEW"] == "Y"):?>
		<div id="reviews_block_content">
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
					"CACHE_TYPE" => "A",
					"DISPLAY_BOTTOM_PAGER" => "Y",
					"ELEMENT_ID" => $arResult["ID"],
					"NEW_REVIEW_FORM" => "Y",
					"PAGER_TEMPLATE" => ".default",
					"PAGE_ELEMENT_COUNT" => "20",
					"SCHEMA_ORG_INSIDE_PRODUCT" => "N"
				)
			);?>
		</div>
	<?endif;?>
	<script type="text/javascript">
		if($("#ask_block_content").length && $("#ask_block").length){
			$("#ask_block").html($("#ask_block_content").html());
			$("#ask_block_content").remove();
			<?if (isset($_REQUEST["send"]) && $_REQUEST["send"]=="ok"):?>
				$("#ask_block").parents("li").addClass("cur").siblings().removeClass("cur");
				$(".tabs li:eq(" + $(".tabs_content li.cur").index() + ")").addClass("cur").siblings().removeClass("cur");
			<?endif;?>
		}
		if($("#reviews_block_content").length && $("#reviews_block").length){
			$("#reviews_block").html($("#reviews_block_content").html());
			if (parseInt($("#reviews_block_content input[name=reviews_count]").val())>0)
			{
				$("#product_reviews_tab span").text( $("#product_reviews_tab span").text() + " (" + $("#reviews_block_content input[name=reviews_count]").val() + ")");
			}

			$.validator.addMethod("captcha", function( value, element, params )
				{
				   return $.validator.methods.remote.call(this, value, element,{
						url: "/ajax/check_captcha.php",
						type: "post",
						data:	{	captcha_word: value,
									captcha_code: function(){ return $(element).parents(".ask-captcha").find('input[name="captcha_code"]').val(); }
								}
				   });
				},
				"Введите корректное значение"
			);
			$("#reviews_block_content").remove();
			<?if (isset($_REQUEST["new_review_added"]) && $_REQUEST["new_review_added"]=="Y"):?>
				$("#reviews_block").parents("li").addClass("cur").siblings().removeClass("cur");
				$(".tabs li:eq(" + $(".tabs_content li.cur").index() + ")").addClass("cur").siblings().removeClass("cur");
				window.scrollTo(0, $("#reviews_block a[name=new-review]").offset().top);
			<?endif;?>
		}
	</script>
<?endif;?>
<script>
	$("input[name=count_items]").each(function(i, el) {	var correctVal = $(el).val();
		if ( $(".basket_button[data-item='" + $(el).parents("[data-item]").attr("data-item") + "']").attr("data-quantity") != correctVal) {
			$(".basket_button[data-item='" + $(el).parents("[data-item]").attr("data-item") + "']").attr("data-quantity", correctVal)
		}
	});
</script>
<?
GLOBAL $lastModified;
if (!$lastModified)
   $lastModified = MakeTimeStamp($arResult['TIMESTAMP_X']);
else
   $lastModified = max($lastModified, MakeTimeStamp($arResult['TIMESTAMP_X']));
?>