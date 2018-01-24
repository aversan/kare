<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); $this->setFrameMode(true);?>
<?
	CJSCore::Init(array("ajax"));
	if($arParams["DISPLAY_AS_RATING"] == "vote_avg") //Let's determine what value to display: rating or average ?
	{
		if($arResult["PROPERTIES"]["vote_count"]["VALUE"]) $DISPLAY_VALUE = round($arResult["PROPERTIES"]["vote_sum"]["VALUE"]/$arResult["PROPERTIES"]["vote_count"]["VALUE"], 2); 
		else $DISPLAY_VALUE = 0; 
	} 
	else $DISPLAY_VALUE = $arResult["PROPERTIES"]["rating"]["VALUE"] ;

?>

<div class="iblock-vote" id="vote_<?=$arParams["ELEMENT_ID"]?>">
	<div style="display: none;" itemprop="aggregateRating" itemscope="itemscope" itemtype="http://schema.org/AggregateRating">
		<meta itemprop="bestRating" content="<?=$DISPLAY_VALUE;?>" />
		<meta itemprop="ratingValue" content="<?=$DISPLAY_VALUE;?>" />
		<meta itemprop="ratingCount" content="<?=$arResult["PROPERTIES"]["vote_count"]["VALUE"];?>" />
	</div>
	<div class="vote_stars" <?if($arResult["VOTED"] || $arParams["READ_ONLY"]):?>title="<?if($arResult["PROPERTIES"]["vote_count"]["VALUE"]) { echo GetMessage("T_IBLOCK_VOTE_RESULTS", array("#VOTES#"=>$arResult["PROPERTIES"]["vote_count"]["VALUE"] ,"#VOTE_TITLE#"=>declOfNum($arResult["PROPERTIES"]["vote_count"]["VALUE"], array(GetMessage("VOTES_1"), GetMessage("VOTES_2"), GetMessage("VOTES_3"))) , "#RATING#"=>$DISPLAY_VALUE));} else {echo GetMessage("T_IBLOCK_VOTE_NO_RESULTS");}?>"<?endif;?>>
		<?if($arResult["VOTED"] || $arParams["READ_ONLY"]==="Y"):?>
			<?if($DISPLAY_VALUE):?>
				<?foreach($arResult["VOTE_NAMES"] as $i=>$name):?>
					<?if(round($DISPLAY_VALUE) > $i):?>
						<div id="vote_<?=$arParams["ELEMENT_ID"]?>_<?=$i?>" class="star-voted"></div>
					<?else:?>
						<div id="vote_<?=$arParams["ELEMENT_ID"]?>_<?=$i?>" class="star-empty"></div>
					<?endif?>
				<?endforeach?>
			<?else:?>
				<?foreach($arResult["VOTE_NAMES"] as $i=>$name):?>
					<div id="vote_<?=$arParams["ELEMENT_ID"]?>_<?=$i?>" class="star-empty" title="<?=$name?>"></div>
				<?endforeach?>
			<?endif?>
		<?else:
			$onclick = "voteScript.do_vote(this, 'vote_".$arParams["ELEMENT_ID"]."', ".$arResult["AJAX_PARAMS"].")";
			?>
			<?if($DISPLAY_VALUE):?>
				<?foreach($arResult["VOTE_NAMES"] as $i=>$name):?>
					<?if(round($DISPLAY_VALUE) > $i):?>
						<div id="vote_<?=$arParams["ELEMENT_ID"]?>_<?=$i?>" class="star-active star-voted" title="<?=$name?>" onmouseover="voteScript.trace_vote(this, true);" onmouseout="voteScript.trace_vote(this, false)" onclick="<?=htmlspecialcharsbx($onclick);?>"></div>
					<?else:?>
						<div id="vote_<?=$arParams["ELEMENT_ID"]?>_<?=$i?>" class="star-active star-empty" title="<?=$name?>" onmouseover="voteScript.trace_vote(this, true);" onmouseout="voteScript.trace_vote(this, false)" onclick="<?=htmlspecialcharsbx($onclick)?>"></div>
					<?endif?>
				<?endforeach?>
			<?else:?>
				<?foreach($arResult["VOTE_NAMES"] as $i=>$name):?>
					<div id="vote_<?=$arParams["ELEMENT_ID"]?>_<?=$i?>" class="star-active star-empty" title="<?=$name?>" onmouseover="voteScript.trace_vote(this, true);" onmouseout="voteScript.trace_vote(this, false)" onclick="<?=htmlspecialcharsbx($onclick)?>"></div>
				<?endforeach?>
			<?endif?>
		<?endif?>
	
		<span class="votes_count">
			<?if ($arResult["PROPERTIES"]["vote_count"]["VALUE"]):?>
				<?=$arResult["PROPERTIES"]["vote_count"]["VALUE"]."&nbsp;".declOfNum($arResult["PROPERTIES"]["vote_count"]["VALUE"], array(GetMessage("VOTES_1"), GetMessage("VOTES_2"), GetMessage("VOTES_3")));?>
			<?else:?>
				<?=GetMessage("T_IBLOCK_VOTE_NO_RESULTS");?>
			<?endif;?>
		</span>
		
	</div>	
	
	<script type="text/javascript">
		if(!window.voteScript) window.voteScript =
		{
			trace_vote: function(div, flag)
			{
				var my_div;
				var r = div.id.match(/^vote_(\d+)_(\d+)$/);
				for(var i = r[2]; i >= 0; i--)
				{
					my_div = document.getElementById('vote_'+r[1]+'_'+i);
					if(my_div)
					{
						if(flag)
						{
							if(!my_div.saved_class) my_div.saved_className = my_div.className;
							if(my_div.className!='star-active star-over') my_div.className = 'star-active star-over';
						}
						else
						{
							if(my_div.saved_className && my_div.className != my_div.saved_className) my_div.className = my_div.saved_className;
						}
					}
				}
				i = r[2]+1;
				while(my_div = document.getElementById('vote_'+r[1]+'_'+i))
				{
					if(my_div.saved_className && my_div.className != my_div.saved_className) my_div.className = my_div.saved_className;
					i++;
				}
			},
			do_vote: function(div, parent_id, arParams)
			{
				var r = div.id.match(/^vote_(\d+)_(\d+)$/);
				var vote_id = r[1];
				var vote_value = r[2];
				function __handler(data)
				{
					var obContainer = document.getElementById(parent_id);					
					if (obContainer)
					{
						$(obContainer).replaceWith(data);
					}
				}
				arParams['vote'] = 'Y';
				arParams['vote_id'] = vote_id;
				arParams['rating'] = vote_value;
				
				console.log(arParams);
				
				BX.ajax.post(
					'/bitrix/components/bitrix/iblock.vote/component.php',
					arParams,
					__handler
				);
			}
		}
	</script>
</div>