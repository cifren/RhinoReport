$(document).ready(function () {
    $(document).on('click', '#filter-btn', function () {
        var $btn = $(this);
        var btnArg = $btn.attr('name') + '=' + $btn.attr('value');
        var urlArg = $btn.closest('form').serialize();
        var newUrl = window.location.pathname + '?' + urlArg + '&' + btnArg;

        //change the url but doesnt reload the page
        window.history.pushState("report", "Report", newUrl);
        var $filterContainer = $('.reportFilter');
        var url = $filterContainer.attr('data-urlRemote');
        var $formFilter = $filterContainer.find('form');
        $.ajax({
            url: url,
            data: $formFilter.serialize(),
            dataType: 'json',
            success: function (data) {
                DataEventDispatcher.dispachtEvent(data);
            }
        })
        return false;
    });
});

var DataEventDispatcher = {
    event: 2,
    handlers: [],
    addEventListener: function (id, fn) {
        var obj = {
            id: id,
            fn: fn
        };
        this.handlers.push(obj);
    },
    removeEventListener: function (id) {
        $.each(this.handlers, function (key, item) {
            if (item.id === id) {
                this.handlers.splice(key, 1);
            }
        });
    },
    dispachtEvent: function (data) {
        $.each(this.handlers, function (key, item) {
            var fn = item.fn;
            var id = item.id;
            fn.call(this, data[id]);
        });
    }
};