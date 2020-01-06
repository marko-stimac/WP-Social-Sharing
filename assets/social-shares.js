; (function ($) {

	/**
	 * jQuery function to prevent default anchor event and make a share popup
	 *
	 * @param  {[object]} e           [Mouse event]
	 * @param  {[integer]} intWidth   [Popup width defalut 500]
	 * @param  {[integer]} intHeight  [Popup height defalut 400]
	 * @param  {[boolean]} blnResize  [Is popup resizeabel default true]
	 *
	 * https://codepen.io/patrickkahl/pen/DxmfG
	 */
	$.fn.customerPopup = function (e, intWidth, intHeight, blnResize) {

		e.preventDefault();

		// Set values for window
		intWidth = intWidth || '500';
		intHeight = intHeight || '400';
		strResize = (blnResize ? 'yes' : 'no');

		// Set title and open popup with focus on it
		var strTitle = ((typeof this.attr('title') !== 'undefined') ? this.attr('title') : 'Social Share'),
			strParam = 'width=' + intWidth + ',height=' + intHeight + ',resizable=' + strResize,
			objWindow = window.open(this.attr('href'), strTitle, strParam).focus();
	}


	$(document).ready(function () {
		var $socialShares = $('.ss');
		$socialShares.find('a').on("click", function (e) {
			$(this).customerPopup(e);
		});
	});

}(jQuery));
