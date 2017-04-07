/******************************************
#fonction javascript g�rant la r�cup�ration des valeurs possibles et leur affichage
******************************************/


var value_suggested = ''; //On stock la value du champ de texte pour �viter de refaire le processus si la valeur n'a pas chang� (appuy� sur une touche autre que caract�re)
function suggest(element, table, field){
	//D�tection du navigateur
	var is_ie = ((navigator.userAgent.toLowerCase().indexOf("msie") != -1) && (navigator.userAgent.toLowerCase().indexOf("opera") == -1));

	/*Fonction utile : d�termine la position absolue exacte d'un objet sur la page*/
	findPos = function(obj){
		var curleft = curtop = 0;
		if (obj.offsetParent) {
			curleft = obj.offsetLeft
			curtop = obj.offsetTop
			while (obj = obj.offsetParent) {
				curleft += obj.offsetLeft
				curtop += obj.offsetTop
			}
		}
		return [curleft,curtop];
	}	
	
	//Cr�ation de la liste des propositions si elle n'existe pas encore
	if(!document.getElementById('suggestsList')){
		var suggestsList = document.createElement('ul');
		suggestsList.id = 'suggestsList';
		
		/*On donne � la liste la m�me largeur que le champ de texte => on doit r�cup�rer sa largeur et son padding*/
		var style = (!is_ie ? window.getComputedStyle(element, null) : element.currentStyle); //R�cup�ration du style
		if(style.width){
			var width = parseInt(style.width.replace(/px/, '')); //On transforme la largeur dans le style en int
			
			//On r�cup�re le padding �ventuel du champ pour le rajouter � la largeur � attribuer � la liste
			var paddingRight = (style.paddingRight ? style.paddingRight : false);
			if(paddingRight){
				paddingRight = parseInt(paddingRight.replace(/px/, ''));
				width += paddingRight;
			}
			
			var paddingLeft = (style.paddingLeft ? style.paddingLeft : false);
			if(paddingLeft){
				paddingLeft = parseInt(paddingLeft.replace(/px/, ''));
				width += paddingLeft;
			}
			
			width = (isNaN(width) ? 150 : width);
			suggestsList.style.width = width+'px'; //On donne � la liste la m�me largeur que celle du champ de texte
		}
		
		//On positionne la liste sous le champ
		suggestsList.style.position = 'absolute';
		var coord = findPos(element); //R�cup�ration des coordonn�es du champ
		suggestsList.style.left = coord[0]+'px';
		suggestsList.style.top = coord[1]+(19)+'px'; //On ajoute 19px de haut pour que la liste soit sur le champ et non par-dessus
		document.body.appendChild(suggestsList); //On ins�re la liste dans le document
	}
	else{
		//Si la liste existe d�j�, on se contente de la rep�rer par rapport � son id
		suggestsList = document.getElementById('suggestsList');
	}
	//Si la valeur a chang�e, on masque la liste, le temps d'actualiser son contenu
	if(element.value != value_suggested){
		suggestsList.style.display = 'none';
	}
	
	//Fonction servant � cacher les suggestions
	closeSuggest = function(nofocus){
		var todelete = document.createElement('div');
		todelete.appendChild(suggestsList);
		if(!nofocus){ element.focus(); }
	};
	
	//Fonction g�rant le parcour des �l�ments � l'aide des touches directionnelles
	selectSuggest = function(direction){
		//On regarde si un �l�ment est selectionn�
		var selected = -1;
		var lis = suggestsList.getElementsByTagName('li');
		for(i=0; i<lis.length; i++){
			if(lis[i].id == 'selectedSuggest'){
				selected = i;
			}
			lis[i].id = '';
		}
		
		selected += direction;
		selected = (selected < -1 ? (lis.length-1) : selected);
		if(selected >= 0 && selected < lis.length){
			lis[selected].id = 'selectedSuggest';
		}
	};
	
	//Remplit le champ avec la suggestion s�lectionn�e
	useSelected = function(){
		//On regarde si un �l�ment est selectionn�
		var lis = suggestsList.getElementsByTagName('li');
		for(i=0; i<lis.length; i++){
			if(lis[i].id == 'selectedSuggest'){
				element.value = lis[i].firstChild.innerHTML;
			}
		}
		closeSuggest();
	};
	
	document.body.onkeyup = function(e){
		var key = (!is_ie ? e.keyCode : window.event.keyCode);
		switch(key){
			case 27: //Esc
				closeSuggest();
				break;
			case 9: //Tab
				closeSuggest(true); //On referme la liste sans redonner le focus au champ
				break;
			case 38: //Up
				selectSuggest(-1);
				break
			case 40: //Down
				selectSuggest(1);
				break;
			case 13: //Enter
				useSelected();
				break;
		}
	};
	document.body.onclick = function(){ closeSuggest(true); };
	
		
	if(element.value != '' && element.value != value_suggested){
		/*R�cup�ration de la liste des suggestions*/
		var suggests = new Array();

	    var XHR = false; 
		try { XHR = new ActiveXObject("Microsoft.XMLHTTP"); }
		catch(e){ XHR = new XMLHttpRequest(); }
		
		//Requ�te AJAX : attention � bien donner le chemin du fichier autocomplete_ajax.php
		XHR.open("GET", 'autocomplete_ajax.php?table='+table+'&field='+field+'&search='+element.value+'&nocache='+Math.floor((new Date()).getTime()), true); //timestamp en parametre pour empecher la mise en cache
		// Attente de l'�tat 4 (-> OK)
		XHR.onreadystatechange = function () {
			// l'�tat est � 4, requ�te re�ue
			if(XHR.readyState == 4){
				var xml = XHR.responseXML; //R�cup�ration du xml contenant les suggestions
				var suggests_xml = xml.getElementsByTagName('suggest');
				for(i=0; i<suggests_xml.length; i++){
					//On remplit l'array des suggestions
					suggests[suggests.length] = suggests_xml[i].firstChild.data;
				}
				
				//On supprime l'ancien contenu de la liste des suggestions, puis on la remplit
				suggestsList.innerHTML = '';
				if(suggests.length){
					for(i=0; i<suggests.length; i++){
						var li = document.createElement('li');
						var a = document.createElement('a');
						a.innerHTML = suggests[i];
						//On ajoute un �v�nement sur le lien pour que son contenu soit mis dans le champ lorsque l'on clique dessus
						a.onclick = function(){
							element.value = this.innerHTML;
							closeSuggest();
						};
						li.appendChild(a);
						suggestsList.appendChild(li);
					}
					//Maintenant que la liste est remplie, on l'affiche
					suggestsList.style.display = '';
				}
				else{
					//S'il n'y a aucune suggestion correspondante, on cache la liste
					closeSuggest();
				}
			}
		}
		XHR.send(null);
	}
	else if(element.value == ''){
		//Si le champ est vide, on cache la liste
		closeSuggest();
	}
	
	//On enregistre la value pour laquelle le traitement a �t� effectuer pour ne pas le refaire s'il n'y a pas de changement
	value_suggested = element.value;
}