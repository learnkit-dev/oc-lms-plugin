+function ($) { "use strict";
    var LearnKitChart = function (element, options) {
        this.$el = $(element)
        this.options = options || {}
        this.init()
    }

    LearnKitChart.prototype.constructor = LearnKitChart

    LearnKitChart.prototype.init = function () {

        let chart = this.$el[0];
        let chartData = this.options.chartData;

        let chartObject = new Chart(chart, chartData);
    }

    LearnKitChart.DEFAULTS = {
        someParam: null
    }

    // PLUGIN DEFINITION
    // ============================

    var old = $.fn.learnkitChart

    $.fn.learnkitChart = function (option) {
        var args = Array.prototype.slice.call(arguments, 1), items, result

        items = this.each(function () {
            var $this   = $(this)
            var data    = $this.data('oc.learnkitChart')
            var options = $.extend({}, LearnKitChart.DEFAULTS, $this.data(), typeof option == 'object' && option)
            if (!data) $this.data('oc.learnkitChart', (data = new LearnKitChart(this, options)))
            if (typeof option == 'string') result = data[option].apply(data, args)
            if (typeof result != 'undefined') return false
        })

        return result ? result : items
    }

    $.fn.learnkitChart.Constructor = LearnKitChart

    $.fn.learnkitChart.noConflict = function () {
        $.fn.learnkitChart = old
        return this
    }

    // Add this only if required
    $(document).render(function (){
        $('[data-content-block-type="chart"]').learnkitChart()
    })

}(window.jQuery);