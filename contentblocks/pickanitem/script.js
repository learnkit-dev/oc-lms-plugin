+function ($) { "use strict";
    var TypePickAnItem = function (element, options) {
        this.$el = $(element)
        this.options = options || {}
        this.init()
    }

    TypePickAnItem.prototype.constructor = TypePickAnItem

    TypePickAnItem.prototype.init = function() {
        let self = this;

        this.$el.find('[data-option]').on('click', function () {
            let value = $(this).attr('data-option');
            self.$el.find('input[type="hidden"]').val(value);
            self.$el.find('[data-option]').removeClass('bg-indigo-600 text-white');
            $(this).addClass('bg-indigo-600 text-white');
        });
    }

    TypePickAnItem.DEFAULTS = {
        someParam: null
    }

    // PLUGIN DEFINITION
    // ============================

    var old = $.fn.typePickAnItem

    $.fn.typePickAnItem = function (option) {
        var args = Array.prototype.slice.call(arguments, 1), items, result

        items = this.each(function () {
            var $this   = $(this)
            var data    = $this.data('oc.typePickAnItem')
            var options = $.extend({}, TypePickAnItem.DEFAULTS, $this.data(), typeof option == 'object' && option)
            if (!data) $this.data('oc.typePickAnItem', (data = new TypePickAnItem(this, options)))
            if (typeof option == 'string') result = data[option].apply(data, args)
            if (typeof result != 'undefined') return false
        })

        return result ? result : items
    }

    $.fn.typePickAnItem.Constructor = TypePickAnItem

    $.fn.typePickAnItem.noConflict = function () {
        $.fn.typePickAnItem = old
        return this
    }

    // Add this only if required
    $(document).render(function (){
        $('[data-content-type="pick-an-item"]').typePickAnItem()
    })

}(window.jQuery);