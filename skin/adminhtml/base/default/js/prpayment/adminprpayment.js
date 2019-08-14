function prPaymentAssignPredefined(){
	if(predefinedForm.validate()){
		var predefinedDeal = $$('input:checked[type="radio"][name="id"]').pluck('title');
		var avgPrice = $('avgPrice').value;
		if(predefinedDeal != ''){
			window.location = predefinedDeal + 'avg_price/' + avgPrice ;
		}
	}
}

function getAvgPriceFromDeals(){
	var sum = 0;
	var total = 0;
	for(var i = 1; i <= 8; i++){
		sum += parseFloat($('deal' + i).value.replace(",", "."));
		total++;
	}
	return sum / total;
}

function getDiffDiscountsFromDeals(){
	var avgPrice = getAvgPriceFromDeals();
	var ret = [0, 0, 0, 0, 0, 0, 0, 0];
	
	if(avgPrice > 0){
		for(var i = 1; i <= 8; i++){
			ret[i - 1] = (parseFloat($('deal' + i).value.replace(",", ".")) / avgPrice) - 1;
		}
	}
	return ret;
}

function updateDiffDiscountsFromDeals(redrawRoulette){
	if(redrawRoulette){
		$('roulette').hide();
		$('recalculate').show();
	}
	var diffDiscounts = getDiffDiscountsFromDeals();
	var realDiscounts = getRealDiscountsFromDeals();
	for(var i = 1; i <= 8; i++){
		var value = ((diffDiscounts[i - 1]) * 100).toFixed(2);
		$('result-deal-' + i).removeClassName('prpayment-design-deal-green').removeClassName('prpayment-design-deal-red');

		text = value;
		if(value < 0){
			$('result-deal-' + i).addClassName('prpayment-design-deal-green');
		}else if(value > 0){
			$('result-deal-' + i).addClassName('prpayment-design-deal-red');
			text = '+' + value;
		}
		if(value == -100){
			$('result-deal-' + i).update('GRATIS');
		}else{
			$('result-deal-' + i).update(text + '%');
		}

		$('real_discount_' + i).value = realDiscounts[i - 1];
	}
	updateLblAvgPriceFromDeals();
	updateLblBalanceFromDeals();
	updateLblAttractionFromDeals();
	updateExampleIcon();
}

function getPricesFromDeals(){
	var ret = [0, 0, 0, 0, 0, 0, 0, 0];
	
	for(var i = 1; i <= 8; i++){
		ret[i - 1] = parseFloat($('deal' + i).value.replace(",", "."));
	}
	return ret;
}


function updateLblAvgPriceFromDeals(){
	var avgPrice = getAvgPriceFromDeals();
	$('lblAvgPrice').update(avgPrice.toFixed(2));
	$('avg_price').value = avgPrice.toFixed(2);
}

function updateLblBalanceFromDeals(){
	var realDiscounts = getRealDiscountsFromDeals();
	var realDiscounts100 = realDiscounts.map(function (x) {
	    return x * 100;
	});
	
	$('lblBalance').update(calcBalance(getPricesFromDeals()).toFixed());
}

function updateLblAttractionFromDeals(){
  var attraction = calcAttraction(getPricesFromDeals());
  if (attraction < 10) {
    $$('.deal-attraction').each(Element.hide);
    $$('.deal-attraction-medium').each(Element.show);
  } else if (attraction < 150) {
    $$('.deal-attraction').each(Element.hide);
    $$('.deal-attraction-high').each(Element.show);
  } else {
    $$('.deal-attraction').each(Element.hide);
    $$('.deal-attraction-very-high').each(Element.show);
  }
}


function arrSum(arrData){
	return arrData.reduce(function(pv, cv) { return pv + cv; }, 0);
}

function calcBalance(arrData){
	return 2 * calcVariance(arrData) / 10;
}

function calcAttraction(arrData){
	var num100 = 0;
  var ret = [0, 0, 0, 0, 0, 0, 0, 0];

	for(var i = 1; i <= 8; i++){
		if (parseFloat($('deal' + i).value.replace(",", ".")) == 0) {
  		num100++;
    }
	}

	correction = 0;
	switch(num100){
		case 0:
			correction = 0;
			break;
		case 1:
			correction = 10;
			break;
		case 2:
			correction = 30;
			break;
		case 3:
			correction = 50;
			break;
		default:
			correction = 100;
			break;
	}
	return calcVariance(arrData) + correction;
}

function calcVariance(arrData){
	var sum = arrSum(arrData);
	var avg = sum / arrData.length;
	
	return arrSum(arrData.map(function (x) {return x * x ;})) / arrData.length - avg * avg;
}

function setAttraction(attractionNumber, price){
	if(!price) return;

	var predefinedAttraction = [
    [-0.05,	-0.05, -0.05,	0.00, 0.00, 0.05, 0.05, 0.05],
    [-0.25, -0.20, -0.15,	0.00, 0.00, 0.15,	0.20,	0.25],
    [-0.25, -0.25,	0.00, 0.00, 0.00, 0.00, 0.25, 0.25],
    [-0.40, -0.40, -0.20, -0.20, 0.20, 0.20, 0.40, 0.40],
    [-0.50, -0.40, -0.30,	-0.20, 0.20, 0.30, 0.40, 0.50],
    [-1.00,	0.142857, 0.142857, 0.142857, 0.142857, 0.142857, 0.142857, 0.142857],
    [-1.00, 0.00, 0.00, 0.00,	0.25, 0.25,	0.25, 0.25]
	];

	var diffDiscounts = predefinedAttraction[attractionNumber];
	for(var i = 1; i <= 8; i++){
		$('deal' + i).value = (price + price * diffDiscounts[i - 1]).toFixed(2);
	}

	updateDiffDiscountsFromDeals();
	recalculate(500);
}


function getAvgDiscount(discounts){
	var sum = 0;
	var total = 0;
	for(var i = 0; i < 8; i++){
		sum += parseFloat(discounts[i]);
		total++;
	}
	return sum / total;
}

function getDiffDiscounts(discounts){
	var avgDiscount = getAvgDiscount(discounts);
	var ret = [0, 0, 0, 0, 0, 0, 0, 0];
	
	for(var i = 0; i < 8; i++){
		ret[i] = (parseFloat(discounts[i]) / avgDiscount) - 1;
	}
	return ret;
}

function recalculate(waitFor){
	var newImg = new Image;
	newImg.src = 'http://payrandom.zlworks.com/api/v1/roulette?sizes=600x600&free_text=' + jsTranslate.freeText + '&prices=' + JSON.stringify(getPricesFromDeals());

	
	// Timer to avoid flicking when loading from cache
	var timer = setTimeout(
		function() {
			$('roulette').hide();
			$('recalculate').hide();
			$('prpayment-loading').show();
		}, waitFor
	);
	
	newImg.onload = function() {
		clearTimeout(timer);
		$('roulette').show();
		$('prpayment-loading').hide();
		$('roulette-img').src = this.src;
	}
}


function updateFixedPrice(){
	if($$('[name="radioFixedPrice"]:checked')[0].value == '1'){
		$('fixed_price').addClassName('required-entry');
		$('fixed_price').addClassName('validate-fixed-price');
	}else{
		$('fixed_price').removeClassName('required-entry');
		$('fixed_price').removeClassName('validate-fixed-price');
		if ($('advice-required-entry-fixed_price')) {
      $('fixed_price').removeClassName('validation-failed');
      $('advice-required-entry-fixed_price').remove();
    }
	}
}

function updateExampleIcon(){
	var freeText = jsTranslate.freeText;
	var upToText = jsTranslate.upToText;
	var fromText = jsTranslate.fromText;
	var fromFreeText = jsTranslate.fromFreeText;

	if($('dealIcon').value == 'percentages'){
		var realDiscounts = getRealDiscountsFromDeals();
		jsText = (Math.max.apply(null, realDiscounts) * 100).toFixed(0) + '%';

		var newImg = new Image;
		newImg.src = 'http://payrandom.zlworks.com/api/v1/watermark?sizes=85x85&offer_text=' + encodeURIComponent(jsText) + '&upper_text=' + encodeURIComponent(upToText);

		newImg.onload = function() {
			$('iconExample').src = this.src;
			$('iconExample').show();
		}
	}else if($('dealIcon').value == 'prices'){
		var prices = getPricesFromDeals();
		var price = Math.min.apply(null, prices)
		if(price == 0){
			jsText = freeText;
      fromText = fromFreeText;
		}else{
			jsText = (price).toFixed(2) + '€';
		}
		var newImg = new Image;
		newImg.src = 'http://payrandom.zlworks.com/api/v1/watermark?sizes=85x85&offer_text=' + encodeURIComponent(jsText) + '&upper_text='  + encodeURIComponent(fromText);

		newImg.onload = function() {
			$('iconExample').src = this.src;
			$('iconExample').show();
		}

	}else{
		$('iconExample').hide();
	}
}


function getRealDiscountsFromDeals(){
	var ret = [0, 0, 0, 0, 0, 0, 0, 0];

	for(var i = 1; i <= 8; i++){
		ret[i - 1] = 1 - parseFloat($('deal' + i).value.replace(",", ".")) / parseFloat($('real_price').value);
	}

	return ret;
}

function getPricesFromDeals(){
	var ret = [0, 0, 0, 0, 0, 0, 0, 0];
	
	for(var i = 1; i <= 8; i++){
		ret[i - 1] = parseFloat($('deal' + i).value.replace(",", "."));
	}
	return ret;
}
