+function ($) { "use strict";
    var LearnKitChart = function (element, options) {
        this.$el = $(element)
        this.options = options || {}
        this.init()
    }

    LearnKitChart.prototype.constructor = LearnKitChart

    LearnKitChart.prototype.init = function () {

        let chart = this.$el[0];

        let chartObject = new Chart(chart, {
            type: 'pie',
            data: {
                labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                datasets: [{
                    label: '# of Votes',
                    data: [12, 19, 3, 5, 2, 3],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });

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