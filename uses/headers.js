(function ($, window, undefined) 
{
	'use strict';

	var pluginName = 'Headers';
	var defaults = 
    {
			fixedOffset: 0
		};

	function Plugin (el, options) 
	{
		// To avoid scope issues, use 'base' instead of 'this'
		// to reference this class from internal events and functions.
		var base = this;

		// Access to jQuery and DOM versions of element
		base.$el = $(el);
		base.el = el;

		// Cache DOM refs for performance reasons
		base.$window = $(".viewport");
		base.$clonedHeader = null;
		base.$originalHeader = null;

		// Keep track of state
		base.isCloneVisible = false;
		base.leftOffset = null;
		base.topOffset = null;

		base.init = function () 
		{
			base.options = $.extend({}, defaults, options);

			base.$el.each(function () 
			{
				var $this = $(this);

				// remove padding on <table> to fix issue #7
				$this.css('padding', 0);

				base.$originalHeader = $('thead:first', this);
				base.$clonedHeader = base.$originalHeader.clone();

				base.$clonedHeader.css('position', 'fixed');
				base.$clonedHeader.css('top', 0);
				base.$clonedHeader.css('z-index', 1);
				base.$clonedHeader.css('display', 'none');

				base.$originalHeader.after(base.$clonedHeader);

			});

			base.updateWidth();
			base.toggleHeaders();

			base.$window.scroll(base.toggleHeaders);
			base.$window.resize(base.toggleHeaders);
			base.$window.resize(base.updateWidth);
		};

		base.toggleHeaders = function () 
		{
			base.$el.each(function () 
			{
				var $this = $(this);

				var newTopOffset = isNaN(base.options.fixedOffset) ?
					base.options.fixedOffset.height() : base.options.fixedOffset;

				var offset = $this.offset();
				var scrollTop = base.$window.scrollTop() + newTopOffset;
				var scrollLeft = base.$window.scrollLeft();

				if ((scrollTop > offset.top) && (scrollTop < offset.top + $this.height())) 
				{
					var newLeft = offset.left - scrollLeft;
					if (base.isCloneVisible && (newLeft === base.leftOffset) && (newTopOffset === base.topOffset) ) 
					{
						return;
					}

					base.$clonedHeader.css('top', newTopOffset);
					base.$clonedHeader.css('margin-top', 0);
					base.$clonedHeader.css('left', newLeft);
					base.$clonedHeader.css('display', 'block');

					base.isCloneVisible = true;
					base.leftOffset = newLeft;
					base.topOffset = newTopOffset;
				}
				else 
				if (base.isCloneVisible) 
          {
            base.$clonedHeader.css('display', 'none');
            base.isCloneVisible = false;
          }
			});
		};

		base.updateWidth = function () {
			// Copy cell widths and classes from original header
			$('td', base.$clonedHeader).each(function (index) {
				var $origCell = $('td', base.$originalHeader).eq(index);
				$(this).width($origCell.width() + 1);
			});
			base.$clonedHeader.css('width', base.$originalHeader.width()+1);
		};

		// Run initializer
		base.init();
	}

	// A really lightweight plugin wrapper around the constructor,
	// preventing against multiple instantiations
	$.fn[pluginName] = function ( options ) 
	{
		return this.each(function () 
		{
			if (!$.data(this, 'plugin_' + pluginName)) 
			{
				$.data(this, 'plugin_' + pluginName, new Plugin( this, options ));
			}
		});
	};

})(jQuery, window);
