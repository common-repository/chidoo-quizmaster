
jQuery(document).ready( function( $ ) {
  'use strict';
	
	var lett = "ABCDEFG";
	var octave = 0;
	var oa = [",","","'","''","'''","''''","'''''"];
	
	var amap2 = new ChiQmAssetMap( chiqmMemoryAssetl10n.amap2title ,chiqmMemoryAssetl10n.amap2desc);
	octave = 0
	j = 6;
	for(var i = 0;i<15;i++){
		amap2.addAssetPair(
			new ChiQmAsset('abc',{abc: "T:\nM: none\nL: 1/4\n"+lett.charAt(j) + oa[octave]+ "|]"}),
			new ChiQmAsset('text',{text: lett.charAt(j).toLowerCase() + oa[(octave+1)] })
		);

		if(lett.charAt(j) == 'B'){
			octave++;
		}
		j++;
		if(j > 6){j=0;}
	}

	chiQmAssetMaps.push(amap2);

	
	var amap1 = new ChiQmAssetMap( chiqmMemoryAssetl10n.amap1title ,chiqmMemoryAssetl10n.amap1desc);
	octave = 0
	var j = 6;
	for(var i = 0;i<29;i++){
		amap1.addAssetPair(
			new ChiQmAsset('abc',{abc: "T:\nM: none\nL: 1/4\n"+lett.charAt(j) + oa[octave]+ "|]"}),
			new ChiQmAsset('text',{text: lett.charAt(j).toLowerCase() + oa[(octave+1)] })
		);

		if(lett.charAt(j) == 'B'){
			octave++;
		}

		j++;

		if(j > 6){j=0;}

	}

	chiQmAssetMaps.push(amap1);


	var amap3 = new ChiQmAssetMap( chiqmMemoryAssetl10n.amap3title ,chiqmMemoryAssetl10n.amap3desc);
	octave = 0
	j = 6;
	for(var i = 0;i<15;i++){
		amap3.addAssetPair(
			new ChiQmAsset('abc',{abc: "T:\nM: none\nL: 1/4\n"+lett.charAt(j) + oa[octave]+ "|]"}),
			new ChiQmAsset('abc',{abc: "T:\nM: none\nL: 1/4\n"+lett.charAt(j) + oa[octave]+ "|]"}),
		);

		if(lett.charAt(j) == 'B'){
			octave++;
		}

		j++;

		if(j > 6){j=0;}

	}

	chiQmAssetMaps.push(amap3);

	var amap4 = new ChiQmAssetMap( chiqmMemoryAssetl10n.amap4title ,chiqmMemoryAssetl10n.amap4desc);
	octave = 0
	j = 6;
	for(var i = 0;i<15;i++){
		amap4.addAssetPair(
			new ChiQmAsset('abc',{abc: "T:\nM: none\nL: 1/4\n"+lett.charAt(j) + oa[octave]+ "\nw:"+lett.charAt(j).toLowerCase() + oa[(octave+1)]+"|]"}),
			new ChiQmAsset('abc',{abc: "T:\nM: none\nL: 1/4\n"+lett.charAt(j) + oa[octave]+ "\nw:"+lett.charAt(j).toLowerCase() + oa[(octave+1)]+"|]"}),
		);

		if(lett.charAt(j) == 'B'){
			octave++;
		}

		j++;

		if(j > 6){j=0;}

	}

	chiQmAssetMaps.push(amap4);

	var amap5 = new ChiQmAssetMap( chiqmMemoryAssetl10n.amap5title ,chiqmMemoryAssetl10n.amap5desc);
	octave = 0
	j = 6;
	for(var i = 0;i<29;i++){
		amap5.addAssetPair(
			new ChiQmAsset('abc',{abc: "T:\nM: none\nL: 1/4\n"+lett.charAt(j) + oa[octave]+ "\nw:"+lett.charAt(j).toLowerCase() + oa[(octave+1)]+"|]"}),
			new ChiQmAsset('abc',{abc: "T:\nM: none\nL: 1/4\n"+lett.charAt(j) + oa[octave]+ "\nw:"+lett.charAt(j).toLowerCase() + oa[(octave+1)]+"|]"}),
		);

		if(lett.charAt(j) == 'B'){
			octave++;
		}

		j++;

		if(j > 6){j=0;}

	}

	chiQmAssetMaps.push(amap5);

});

