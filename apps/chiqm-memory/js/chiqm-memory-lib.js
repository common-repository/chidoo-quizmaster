class ChiQmAsset{
	constructor(assetType, options){
		this.assetType = assetType; // abc, image, text
	
		/*
		 * type-specific options:
		 * 'abc':   abc:  'abcstring'
		 * 'image': src:  'image.png', taken from assets subfolder, not implemented yet
		 * 'text':  text: 'any text string'
		 */
		this.options = options;
	}
	
	getAssetType(){ return this.assetType; }
	getAssetValue(){ 
		if(this.assetType == 'abc'){
			return this.options.abc;
		}
		else if(this.assetType == 'image'){
			return this.options.src;
		}
		else if(this.assetType == 'text'){
			return this.options.text;
		}
	}
}

class ChiQmAssetMap{
	constructor(name,desc){
		this.name = name;
		this.description = desc;
		this.assets = []; // array of ChiQmAsset pairs
	}

	getName(){return this.name;	}
	getDescription(){return this.description;	}

	addAssetPair(a,b){
		this.assets.push([a,b]);
	}

	getAssetPairAt(at){
		return this.assets[at];
	}

	getAssets(){
		return this.assets;
	}

	setAssets(assets){
		this.assets = assets;
	}
}

class ChiQmMemory {

	constructor(gameElement,numPairs,assetMap){

		this.gameElement = gameElement;
		this.allCards = [];
		this.allSiblings = [];

		this.assetMap = new ChiQmAssetMap(assetMap.name,assetMap.description);

		this.numCards = numPairs;
		if(assetMap != null){
			var r = Math.floor(Math.random() * assetMap.getAssets().length);
			if((r + this.numCards) >= assetMap.getAssets().length){
				r = r - this.numCards;
			}
			if(r < 0){r = 0;}
			var a = assetMap.getAssets();
			var slic = a.slice(r,( parseInt(r) + parseInt(this.numCards)) );
			this.assetMap.setAssets(slic); 
		}
		
		this.matchedPairs = 0;
		this.tries = 0;
		this.failedTries = 0;
		this.energyMode = false;

		this.flippedCards = [];

		this.init();

		/*
		 * 0 = nothing flipped
		 * 1 = one flipped
		 * 2 = two flipped
		 * 3 = two flipped and match
		 * 4 = two flipped and mismatch
		 */
		this.flipState = 0; 	

	}

	setEnergyMode(mode){
		this.energyMode = mode;
	}

	getEnergyMode(){
		return this.energyMode;
	}


	getFlipState(){
		return this.flipState;
	}

	setFlipState(state){
		this.flipState = state;
	}

	// fisher-yates, https://bost.ocks.org/mike/shuffle/
	shuffle(array) {
		var m = array.length, t, i;
		while (m) {
			i = Math.floor(Math.random() * m--);
			t = array[m];
			array[m] = array[i];
			array[i] = t;
		}
		return array;
	}

	init(){

		for(var i = 0; i < this.numCards; i++){
			
			var tmp;
			var tmpSib;
			
			if(this.assetMap != null){
				var ap = this.assetMap.getAssetPairAt(i);
				tmp = new ChiQmMemoryCard(ap[0],this.gameElement,i);
				tmpSib = new ChiQmMemoryCard(ap[1],this.gameElement,i);
			} else {
				tmp = new ChiQmMemoryCard("T:\nM: none\nL: 1/4\n"+a.charAt(i)+"2|]",this.gameElement,i);
				tmpSib = new ChiQmMemoryCard("T:\nM: none\nL: 1/4\n"+a.charAt(i)+"2|]",this.gameElement,i);
			}

			tmp.setSibling(tmpSib);
			tmpSib.setSibling(tmp);
		
			this.allCards.push(tmp);
			this.allSiblings.push(tmpSib);

			this.allCards[i].setGame(this);
			this.allSiblings[i].setGame(this);
		}
		
		this.allCards = this.shuffle(this.allCards);
		this.allSiblings = this.shuffle(this.allSiblings);

		var ary = this.allCards.concat(this.allSiblings);
		ary = this.shuffle(ary);

		var g = jQuery('#'+this.gameElement);
		g.hide();
		for(var i = 0; i < ary.length; i++){
			ary[i].render();
			ary[i].flip();
		}
		this.setupEventHandlers();

		this.setFlipState(1);
		g.fadeIn(2000);

		window.setTimeout(function(a,g){
			for(var i = 0; i < a.length; i++){
				a[i].flip();
			}
			g.setFlipState(0);
		},3000,ary,this);
	}

	reset(){

		for(var i = 0; i < this.allCards.length; i++){
			delete this.allCards[i];
			delete this.allSiblings[i];
		};
		delete this.allCards;
		delete this.allSiblings;
	
	}

	setupEventHandlers(){
		var myg = document.querySelector("#game");
		myg.addEventListener('two_cards_open', e => this.compareFlippedCards(e));
		myg.addEventListener('two_cards_no_match', e => this.twoCardsNoMatch(e));
		myg.addEventListener('two_cards_do_match', e => this.twoCardsDoMatch(e));
	}

	// TODO: check code redundancy
	twoCardsDoMatch(e){
		var g = e.detail.game;
		window.setTimeout(function(gl){
			jQuery('#'+gl.gameElement).effect("highlight",{color:"#bbffbb"});
			jQuery('#'+gl.gameElement).effect("pulsate");
		},800,g);

		g.matchedPairs++;

		var perc = (100 - (g.tries / (g.numCards * 2)) * 100);
		perc = perc.toFixed(0);
		
		if(g.getEnergyMode() == true && perc <= 0){
			document.getElementById("gameoverlay").style.display = "block";
			var op = jQuery('#gameoverlay div p').first();
			var quot = g.tries / g.matchedPairs;
			op.html('<div class="olheader">'+chiqmMemoryLibl10n.low_energy+'!!!</div><div class="olcontent">'+chiqmMemoryLibl10n.num_attempts+': '+g.tries+'<br />'+chiqmMemoryLibl10n.matched_pairs+': '+g.matchedPairs+'<br />'+chiqmMemoryLibl10n.failed_attempts+': '+g.failedTries+'<br />'+chiqmMemoryLibl10n.your_quota+': '+quot.toFixed(2)+'</div>');
	
		}
		else if(g.matchedPairs == g.numCards){
			document.getElementById("gameoverlay").style.display = "block";
			var op = jQuery('#gameoverlay div p').first();
			var quot = g.tries / g.numCards;
			op.html('<div class="olheader">'+chiqmMemoryLibl10n.great+'!!!</div><div class="olcontent">'+chiqmMemoryLibl10n.num_attempts+': '+g.tries+'<br />'+chiqmMemoryLibl10n.matched_pairs+': '+g.matchedPairs+'<br />'+chiqmMemoryLibl10n.failed_attempts+': '+(g.tries - g.numCards)+'<br />'+chiqmMemoryLibl10n.your_quota+': '+quot.toFixed(2)+'</div>');
		}
	}


	// TODO: check code redundancy, see above
	twoCardsNoMatch(e){
		var g = e.detail.game;
		g.failedTries++;
		window.setTimeout(function(gl){
			jQuery('#'+gl.gameElement).effect("highlight",{color:"#ffbbbb"});
			jQuery('#'+gl.gameElement).effect("shake");

		var perc = (100 - (g.tries / (gl.numCards * 2)) * 100);
		perc = perc.toFixed(0);
		if(gl.getEnergyMode() == true && perc <= 0){
			document.getElementById("gameoverlay").style.display = "block";
			var op = jQuery('#gameoverlay div p').first();
			if(gl.matchedPairs != 0){
				var quot = gl.tries / gl.matchedPairs;
				op.html('<div class="olheader">'+chiqmMemoryLibl10n.low_energy+'!!!</div><div class="olcontent">'+chiqmMemoryLibl10n.num_attempts+': '+gl.tries+'<br />'+chiqmMemoryLibl10n.matched_pairs+': '+gl.matchedPairs+'<br />'+chiqmMemoryLibl10n.failed_attempts+': '+gl.failedTries+'<br />'+chiqmMemoryLibl10n.your_quota+': '+quot.toFixed(2)+'</div>');
			} else {
				op.html('<div class="olheader">'+chiqmMemoryLibl10n.low_energy+'!!!</div><div class="olcontent">'+chiqmMemoryLibl10n.num_attempts+': '+gl.tries+'<br />'+chiqmMemoryLibl10n.matched_pairs+': '+gl.matchedPairs+'<br />'+chiqmMemoryLibl10n.failed_attempts+': '+gl.failedTries+'<br />'+chiqmMemoryLibl10n.your_quota+': 0</div>');
			}
	
		}


		},800,g);
	}

	compareFlippedCards(e){
		var g = e.detail.game;
		g.tries++;

		// calculate energy
		var perc = (100 - (g.tries / (g.numCards * 2)) * 100);
		if(perc < 0){perc = 0;}
		var enVal = document.getElementsByClassName('chiqm-energy-value')[0];
		perc = perc.toFixed(0);
		enVal.style.width = (100 - perc) + '%';
		enVal.style.marginLeft = perc + '%';
		document.getElementById('chiqm-energy-percentage').innerHTML = perc + ' %';

		if(g.flippedCards[0].getElemId() == g.flippedCards[1].getSibling().getElemId()){
			g.flipState = 3;
			
			var gElem = document.getElementById(g.gameElement);
			gElem.dispatchEvent(new CustomEvent('two_cards_do_match', { bubbles:true, detail:{game: g} }));

			window.setTimeout(function(gl){
				gl.flipState = 0;
				gl.flippedCards = [];
			},500,g);

		} else {
			g.flipState = 4;
			
			var gElem = document.getElementById(g.gameElement);
			gElem.dispatchEvent(new CustomEvent('two_cards_no_match', { bubbles:true, detail:{game: g} }));

			window.setTimeout(function(gl){
				gl.flippedCards[0].flip();
				gl.flippedCards[1].flip();
				gl.flipState = 0;
				gl.flippedCards = [];
			},2000,g);
		}
	}

	handleClickEvent(card){

		return function(){
			var g = card.getGame();
			switch(g.flipState){

				case 0:
						if(card.isFlipped()){
							card.flip();
							g.flippedCards[0] = card;
							g.flipState++;
						} 
					break;
				case 1:
						if(card.isFlipped()){
							card.flip();
							g.flippedCards[1] = card;
							g.flipState++;
							var gElem = document.getElementById(g.gameElement);
							gElem.dispatchEvent(new CustomEvent('two_cards_open', { bubbles:true, detail:{game: g} }));
						}
					break;
				default:
					break;
			}
		}
	}
}

class ChiQmMemoryCard {
	constructor(asset,relem,index){
		this.asset = asset;
		this.relem = document.getElementById(relem);
	
    var now = new Date().getTime();
    var random = Math.floor(Math.random() * 100000);
   	this.elemId = "chiqm-memory-card-" + now + random;
		this.index = index;

		this.dside = 0;

		this.elem = document.createElement('div');
		this.elem.setAttribute("id",this.elemId);
		this.elem.setAttribute("data-index",this.index);
		this.elem.setAttribute("data-side",this.dside);
		this.elem.setAttribute("class","chiqm-memory-card");

		var dc = document.createElement('div');
		dc.setAttribute("class","svgwrap");
		dc.style.display = "none";
		this.elem.appendChild(dc);

		this.game = null;
		this.sibling = null;
	}

	setSibling(sib){
		this.sibling = sib;
	}

	getSibling(){
		return this.sibling;
	}


	setGame(g){
		this.game = g;
	}

	getGame(){
		return this.game;
	}

	getDataSide(){
		return this.dside;
	}

	isFlipped(){
		return (this.dside == 1) ? true : false;
	}

	setDataSide(dside){
		this.dside = dside;
	}

	getRenderElement(){
		return this.relem;
	}

	getElemId(){
		return this.elemId;
	}

	getIndex(){
		return this.index;
	}

	renderPreview(target){
		if(this.asset.getAssetType() == 'abc'){
			if(window.matchMedia("screen and (min-width: 960px)").matches){
				ABCJS.renderAbc(target,this.asset.getAssetValue(),{scale:0.65});
			} else {
				ABCJS.renderAbc(target,this.asset.getAssetValue(),{scale:0.45});
			}
		}
		else if(this.asset.getAssetType() == 'text'){
			target.setAttribute('class',target.getAttribute("class")+" chiqm-preview-text");
			target.innerHTML = '<div>'+this.asset.getAssetValue()+'</div>';
		}

	}

	render(){
		this.elem.addEventListener("click", this.getGame().handleClickEvent(this) );
		if(this.asset.getAssetType() == 'abc'){
			if(window.matchMedia("screen and (min-width: 960px)").matches){
				ABCJS.renderAbc(this.elem.firstChild,this.asset.getAssetValue());
			} else {
				ABCJS.renderAbc(this.elem.firstChild,this.asset.getAssetValue(),{scale:0.65});
			}
		}
		else if(this.asset.getAssetType() == 'text'){
			this.elem.firstChild.setAttribute('class',this.elem.firstChild.getAttribute("class")+" chiqm-asset-text");
			this.elem.firstChild.innerHTML = this.asset.getAssetValue();
		}
		this.relem.appendChild(this.elem);
		this.flipBack();
	}

	handleCardClicked(e){
		var g = this.getGame();
		this.elem.dispatchEvent(new CustomEvent('card_clicked', { bubbles:true, detail:{game: g, card: this} }));
	}

	flipBack(){
		var j = jQuery('#'+this.elemId);
		if(this.getDataSide() == 0){
			j.find('div').first().fadeOut(300);
			j.css('transform','rotatey(180deg)');
			j.css('transition-duration',"1ms");
			j.css('background','#00ff00');
			j.data('side',"1");
			this.setDataSide(1)
		}
	}

	flip(){
		var j = jQuery('#'+this.elemId);
		if(this.getDataSide() == 0){
			j.find('div').first().fadeOut(300);
			j.css('transform','rotatey(180deg)');
			j.css('transition-duration',"1s");
			j.css('background','#00ff00')
			j.data('side',"1");
			this.setDataSide(1)
		} else {
			j.css('transform','rotatey(0deg)');
			j.css('transition-duration',"1s");
			j.css('background','#ffffee');
			j.data('side',"0");
			this.setDataSide(0)
			setTimeout(function(){j.find('div').first().fadeIn(400)},400);
		}
	}
}


