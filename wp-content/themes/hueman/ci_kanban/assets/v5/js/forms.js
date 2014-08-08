function forms_updateCharacterCount(fieldId)
{
    var field       = $("#" + fieldId),
        maxLength   = field.attr('maxlength'),
        charCount   = field.val().length;

        $("#characterCounter-for-" + fieldId).text(charCount + '/' + maxLength);;
}


function forms_newArrayInput(field)
{
    $('<div class="miwm-row"><input name="' + field + '[]" type="text" class="sized_fullWidth"><a class="remove_field" href="#">Remove</a></div>').insertBefore("#input-block-" + field + " .form_addFieldBtn");
}