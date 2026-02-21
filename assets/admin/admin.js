jQuery(document).ready(function($) {
    const deactivateLink = $('tr[data-plugin="' + wfeAdminVars.pluginSlug + '"] .deactivate a');
    
    deactivateLink.on('click', function(e) {
        e.preventDefault();
        $('#wfe-deactivation-modal').fadeIn();
    });

    $('#wfe-keep-data').on('click', function() {
        window.location.href = wfeAdminVars.keepDataUrl + '&plugin=' + encodeURIComponent(wfeAdminVars.pluginSlug) + '&action=deactivate';
    });

    $('#wfe-remove-data').on('click', function() {
        window.location.href = wfeAdminVars.removeDataUrl + '&plugin=' + encodeURIComponent(wfeAdminVars.pluginSlug) + '&action=deactivate';
    });

    $('#wfe-close-modal').on('click', function() {
        $('#wfe-deactivation-modal').fadeOut();
    });


    $('#sortable-fields').sortable({
        placeholder: "ui-state-highlight",
        update: function(event, ui) {
            $('#sortable-fields tr').each(function(index) {
                $(this).find('.field-order').text(index + 1);
            });
        }
    });

    const tableBody = $('#sortable-fields');

    function collectFieldData() {
        const fields = [];

        tableBody.find('tr').each(function (index) {
            const $row = $(this);
            const id = $row.data('id');

            fields.push({
                id: id,
                field_order: index + 1,
                enabled: $row.find('.wfe-enabled-toggle').is(':checked') ? 1 : 0,
                required: $row.find('.wfe-required-toggle').is(':checked') ? 1 : 0
            });
        });

        return fields;
    }
    function saveFieldSettings() {
        const fields = collectFieldData();

        $.post(wfeAdminVars.ajaxUrl, {
            action: 'wfe_save_field_order',
            _ajax_nonce: wfeAdminVars.nonce,
            fields: fields
        }, function (response) {
            if (response.success) {
                console.log('Saved');
            } else {
                alert(response.data.message || 'Save failed.');
            }
        });
    }

    tableBody.sortable({
        handle: '.wfe-drag-handle',
        update: saveFieldSettings
    });

    tableBody.on('change', '.wfe-enabled-toggle, .wfe-required-toggle', saveFieldSettings);

    $('#save-field-order').on('click', function() {
        const fields = [];

        $('#sortable-fields tr').each(function(index) {
            const $row = $(this);
            const id = $row.data('id');
            const required = $row.find('.required-switch').is(':checked') ? 1 : 0;
            const enabled = $row.find('.enabled-switch').is(':checked') ? 1 : 0;

            fields.push({
                id,
                field_order: index + 1,
                required,
                enabled
            });
        });

        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'wfe_update_fields',
                fields,
                _ajax_nonce: wfeAdminVars.nonce
            },
            success: function(response) {
                if (response.success) {
                    alert('Fields updated successfully!');
                } else {
                    alert(response.data?.message || 'Update failed');
                }
            }
        });
    });

});
