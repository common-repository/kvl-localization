(function ($) {

    function formatCountry(data) {

        if (!data.id) {
            return data.text;
        }

        flagUri = data.id.split(":");
        flagUri = flagUri[flagUri.length - 1];

        return '<span><img src="' + flagUri + '" class="img-flag" /> ' + data.text + '</span>';
    };

    $('select.kvl-selectpicker').select2({
        formatResult: formatCountry,
        formatSelection: formatCountry
    });

    $('select.kvl-selectpicker.kvl-selectpicker-front').select2({
        formatResult: formatCountry,
        formatSelection: formatCountry,
        minimumResultsForSearch: Infinity
    });

    function localeSelected() {
        var data2 = $(this).val();
        locale = data2.split(':')[0];

        //../wp-content/plugins/key-value-localization/functionality.php
        $.post(null,
            {
                kvl_locale: locale,
                kvl_wpnonce: $("#kvl_wpnonce").val(),
            },
            function (data, status) {
                location.reload();
            });

    }

    $('#language_picker').change(localeSelected);

})(jQuery);

