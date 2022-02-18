fsAddAction('mailbox_update_init', function() {
    var alternativeSignatureCount = parseInt($('#alternative-signature-count').val(), 10);
    for (var i = 0; i < alternativeSignatureCount; i++) {
        var alternativeSignatureSelector = '#alternative-signature-' + i;
        summernoteInit(alternativeSignatureSelector, {
            insertVar: true,
            disableDragAndDrop: false,
            callbacks: {
                onInit: function() {
                    $(alternativeSignatureSelector).parent().children().find('.note-statusbar').remove();
                    $(alternativeSignatureSelector).parent().children().find('.summernote-inservar:first').on('change', function(event) {
                        $(alternativeSignatureSelector).summernote('insertText', $(this).val());
                        $(this).val('');
                    });
                },
                onImageUpload: function(files) {
                    if(!files) {
                        return;
                    }
                    for (var i = 0; i < files.length; i++) {
                        editorSendFile(files[i], undefined, false);
                    }
                }
            }
        });
    }

    $(document).on('click', '.delete-alternative-signature', function(e) {
        var $this = $(e.currentTarget);
        var $group = $this.closest('.form-group');
        $this.addClass('hidden');
        $group.find('.restore-alternative-signature').removeClass('hidden');
        $group.find('.alternative-signature-action').val('DELETE');
        $group.css('opacity', 0.4);
    }).on('click', '.restore-alternative-signature', function(e) {
        var $this = $(e.currentTarget);
        var $group = $this.closest('.form-group');
        $this.addClass('hidden');
        $group.find('.delete-alternative-signature').removeClass('hidden');
        $group.find('.alternative-signature-action').val('KEEP');
        $group.css('opacity', 1);
    });

    $('.add-alternative-email-signature').on('click', function() {
        var $counter = $('#alternative-signature-count');
        var $wrapper = $('.alternative-signatures');
        var $template = $('.alternative-signature-template');

        var iteration = parseInt($counter.val(), 10);
        var index = iteration - 1;

        var newAlternativeSignature = $template.html();
        newAlternativeSignature = newAlternativeSignature.replaceAll('$index', index + 1);
        newAlternativeSignature = newAlternativeSignature.replaceAll('$iteration', iteration + 1);

        $counter.val(iteration + 1);
        $wrapper.append(newAlternativeSignature);

        var alternativeSignatureSelector = '#alternative-signature-' + (index + 1);
        summernoteInit(alternativeSignatureSelector, {
            insertVar: true,
            disableDragAndDrop: false,
            callbacks: {
                onInit: function() {
                    $(alternativeSignatureSelector).parent().children().find('.note-statusbar').remove();
                    $(alternativeSignatureSelector).parent().children().find('.summernote-inservar:first').on('change', function(event) {
                        $(alternativeSignatureSelector).summernote('insertText', $(this).val());
                        $(this).val('');
                    });
                },
                onImageUpload: function(files) {
                    if(!files) {
                        return;
                    }
                    for (var i = 0; i < files.length; i++) {
                        editorSendFile(files[i], undefined, false);
                    }
                }
            }
        });
    });
});

fsAddAction('conv_editor_init', function() {
    $('#selected-signature').on('change', function(e) {
        console.log('yo');
        var $this = $(e.currentTarget);
        $.ajax({
            url: laroute.route('mailbox.custom_signatures', {'id': $this.attr('data-mailbox_id'), 'signatureId': $this.val()}),
            type: 'GET',
            success: function(response) {
                $('#editor_signature').html(response.content);
            }
        });
    });
});
