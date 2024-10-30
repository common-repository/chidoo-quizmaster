/*
 * All AssetMaps
 */
var chiQmAssetMaps = [];
var chiqmCookie;

jQuery(document).ready( function( $ ) {
  'use strict';

	var ajaxUrl = chiqm_memory.ajaxurl;
	var theGame = null;
	
	var cooStr = chiqmGetCookie('chiqm-memory');
	if(cooStr == ""){
		chiqmSetCookie('chiqm-memory',JSON.stringify({'set':0,'pairs':6,'attempts':0}),365);
		var cstr = chiqmGetCookie('chiqm-memory');
		chiqmCookie = JSON.parse(cstr);
	} else {
		chiqmCookie = JSON.parse(cooStr);
	}

	if(chiqmCookie['pairs'] != ""){
		$('#numpairs').val( chiqmCookie['pairs'] );
	}

	var om = $('#optionmap');
	var opts = "";
	var previews = [];
	for(var i=0;i<chiQmAssetMaps.length;i++){

		previews.push(
			[ 
				new ChiQmMemoryCard(chiQmAssetMaps[i].getAssetPairAt(0)[0],'asset-info-'+i+'1',0),
		    new ChiQmMemoryCard(chiQmAssetMaps[i].getAssetPairAt(0)[1],'asset-info-'+i+'2',0),
				new ChiQmMemoryCard(chiQmAssetMaps[i].getAssetPairAt(chiQmAssetMaps[i].getAssets().length - 1)[0],'asset-info-'+i+'3',0) ,
		    new ChiQmMemoryCard(chiQmAssetMaps[i].getAssetPairAt(chiQmAssetMaps[i].getAssets().length - 1)[1],'asset-info-'+i+'4',0)
			]);
		opts = opts + '<div class="chiqm-om-item"><div class="om-item-left"><input class="chiqm-asset-radio" type="radio" id="asset-'+i+'" name="asset" value="'+i+'" '+( (i == chiqmCookie['set']) ? ' checked' : '')+'></div><div class="om-item-right"><label class="label-chiqm-om-item" for="asset-'+i+'"><div class="om-item-info first">'+chiQmAssetMaps[i].getName()+'</div><div class="om-item-info second">'+chiQmAssetMaps[i].getDescription()+'</div><div class="om-item-info third">'+
		'<div class="chiqm-first-pair"><div class="chiqm-asset-preview" id="asset-info-'+i+'1"></div>'+
		'<div class="chiqm-asset-preview" id="asset-info-'+i+'2"></div></div>'+
		'<div class="chiqm-second-pair"><div class="chiqm-asset-preview" id="asset-info-'+i+'3"></div>'+
		'<div class="chiqm-asset-preview" id="asset-info-'+i+'4"></div></div>'+
		'</div></label></div></div>';

	}
	om.html(opts);

	for(var i = 0; i< previews.length;i++){
		previews[i][0].renderPreview( document.getElementById('asset-info-'+i+'1') );
		previews[i][1].renderPreview( document.getElementById('asset-info-'+i+'2') );
		previews[i][2].renderPreview( document.getElementById('asset-info-'+i+'3') );
		previews[i][3].renderPreview( document.getElementById('asset-info-'+i+'4') );
	}

	$('.entry-content').on("change","#numpairs",function($this){
		var r = $(this);
		chiqmCookie['pairs'] = r.val();
		chiqmSetCookie('chiqm-memory',JSON.stringify(chiqmCookie,365)) ;
	});

	$('.entry-content').on("click",".chiqm-asset-radio",function($this){
		var r = $(this);
		chiqmCookie['set'] = r.val();
		chiqmSetCookie('chiqm-memory',JSON.stringify(chiqmCookie,365)) ;
	});

	$('.entry-content').on("click","#bmem",function($this){
		$('#game').html('');
		
		var omap = $('input[name="asset"]:checked').val();

		// We default to 8 pairs, maximum 12
		var numPairs = $('#numpairs').val();
		if(numPairs < 2){ numPairs = 8;	}
		else if(numPairs > 12){ numPairs = 12;	}
		theGame = new ChiQmMemory('game',numPairs,chiQmAssetMaps[omap]);

		// TODO: put game start/end into some kind of run method
		var enMode = $('#energymode').prop('checked');
		theGame.setEnergyMode(enMode);

		$(this).hide();
		$('#numpairs').closest('p').hide();
		$('div.chiqm-row').hide();
		$('h3#chiqm-available').hide();

		if(enMode){
			$('div.chiqm-row.chiqm-energy').show();
		}
		$('#breset').show('');

	});

	$('.entry-content').on("click","#breset",function($this){
		location.reload();
	});

	$('.entry-content').on("click","#gameoverlay",function($this){
		location.reload();
	});

});


