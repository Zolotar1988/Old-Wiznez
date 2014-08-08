var tagsystem = {

    // # Query the server
    fetch_suggestions: function (event, element) {
        // If it's a special key, just return
        if (event.which == 13 || event.which == 38 || event.which == 40)
            return true;

        var el = $(element);
        var tag = el.val();
        var container = $("#tag_suggestion_container-" + el.attr('data-container'));
        var callback = function (response) {
            response = JSON.parse(response);
            tagsystem.populate_suggestions(tag, response.data, container)
        };

        if (!tag) {
            container.find('.tag_suggestion_input_suggestions').hide();
            return true;
        }

        tagsystem.tag_suggestion(tag, callback);
        return true;
    },


    // # Checks if a special key is pressed (enter, up, down)
    checkForSpecialKeyPress: function (event, element) {
//        if (event.which == 32) return false; // No space allowed

        if (event.which != 13 && event.which != 38 && event.which != 40 && event.which != 188)
            return true;

        var el = $(element);
        var parent_container = $("#tag_suggestion_container-" + el.attr('data-container'));
        var suggestions_container = parent_container.find('.tag_suggestion_input_suggestions');
        var input = parent_container.find('input');
        var items = suggestions_container.find('li');
        var currentActiveTag = suggestions_container.find('li.selected');
        var next = currentActiveTag.next('li');
        var prev = currentActiveTag.prev('li');
        if (!next.length)
            next = $(items[0]);
        if (!prev.length)
            prev = $(items[items.length - 1]);

        if (event.which == 13 || event.which == 188) {
            // Enter is pressed - Select the current tag
            tagsystem.addTag($(currentActiveTag).html(), input, parent_container);
        }

        if (event.which == 38) {
            // Up is pressed
            prev.addClass('selected');
            currentActiveTag.removeClass('selected');
        }

        if (event.which == 40) {
            // Down is pressed
            next.addClass('selected');
            currentActiveTag.removeClass('selected');
        }

        return false;
    },


    // # Populate the list with server results
    populate_suggestions: function (tag, suggestions, parent_container) {
        var suggestions_container = parent_container.find('.tag_suggestion_input_suggestions');
        suggestions_container.empty();
        suggestions_container.show();
        console.log(suggestions);

        if (!suggestions) {
            tagsystem.noSuggestions(tag, suggestions_container, parent_container);
            tagsystem.makeTagsSelectable(suggestions_container, parent_container);
            return false;
        }

        $.each(suggestions, function (index, value) {
            console.log(value);
            suggestions_container.prepend('<li>' + value + '</li>');
        });
        tagsystem.makeTagsSelectable(suggestions_container, parent_container);
    },


    // # When there are no suggestions
    noSuggestions: function (tag, container, parent_container) {
        container.html('<li>' + tag + '</li>');
    },


    // # This makes the tags actually clickable and "hoverable"
    makeTagsSelectable: function (container, parent_container) {
        var input = parent_container.find('input');
        var items = container.find('li');
        $(items[0]).addClass('selected');

        $.each(items, function (index, item) {
            var selector = $(item);
            selector.hover(function () {
                tagsystem.selectTag($(this), input, parent_container);
            });
            selector.click(function () {
                tagsystem.addTag($(this).html(), input, parent_container);
            });
        });
    },


    // # This is just to give the hover effet to a tag, selector is JQuery obj
    selectTag: function (tag_selector, input, parent_container) {
        var otherSuggestedTags = parent_container.find('li');
        otherSuggestedTags.removeClass('selected');
        tag_selector.addClass('selected');
    },


    // # This makes a tag selected and does things nescessary
    addTag: function (tag, input, parent_container) {
        var tagsCount = $("#tag_suggestion_container-interests .tag").length;
        if(!tag || tag == 'undefined' || typeof tag == 'undefined') return false;
        if(tagsCount > 4) return false;

        // Remove required from field
        $("#profile_interestsInput").removeAttr('required');

        var tags_container = parent_container.find('.tag_suggestion_tags');
        var tags_added = tags_container.find('.tag');
        var tag_alreadyAdded = false;

        input.val('');
        input.focus();
        parent_container.find('.tag_suggestion_input_suggestions').hide();

        $.each(tags_added, function (index, item) {
            var selector = $(item);
            if (selector.attr('data-tag') == tag)
                tag_alreadyAdded = true;
        });

        if (tag_alreadyAdded) return true;

        tags_container.find('.tag_placeholder:first-child').remove();
        tags_container.prepend('<div class="tag" data-tag="' + tag + '" data-container="' + parent_container.attr('id') + '"><span>' + tag + '</span><a class="close" href="#" onclick="tagsystem.removeTag(this); return false"><i class="icon-close3"></i></a></div>');
//        var newTag=tags_container.find('.tag').first();
//        newTag.addClass('tag_justAdded').addClass('tag_highlightTransition');
//        newTag.removeClass('tag_justAdded');
        tagsystem.saveTags();
        tagsystem.uxForWhenReachedDesiredTagCount();
        $("#interestsSoFarCount").text(parseInt($("#interestsSoFarCount").text()) + 1);
    },


    // # Remove a tag
    removeTag: function (element) {
        var el      = $(element);
        var parent  = el.closest('.tag');
        parent.remove();
        tagsystem.saveTags();
        tagsystem.uxForWhenReachedDesiredTagCount();
        $("#interestsSoFarCount").text(parseInt($("#interestsSoFarCount").text()) - 1);
    },


    // When the user reached the max tags, do something
    uxForWhenReachedDesiredTagCount: function() {
        var tagsCount = $("#tag_suggestion_container-interests .tag").length;
        return tagsCount;
    },


    // # When the input is deselected. The timeout in the end is to not deselect it when a tag is chosen
    tagField_blurred: function (element) {
        var el = $(element);
        var container = $("#tag_suggestion_container-" + el.attr('data-container'));
        var suggestions_container = container.find('.tag_suggestion_input_suggestions');
        setTimeout(function () {
                suggestions_container.hide();
            },
            300);
    },

    // # When the input is selected
    tagField_focus: function (element) {
        var el = $(element);
        var container = $("#tag_suggestion_container-" + el.attr('data-container'));
        var suggestions_container = container.find('.tag_suggestion_input_suggestions');
        suggestions_container.show();
    },

    tag_suggestion: function (tag, callback) {
        if(!tag.length) return false;
        $.get('/tagsystem/tagsystem_interface_controller/tag_suggestion?tag=' + tag, function (response) {
            callback(response);
        });
    },

    saveTags: function()
    {
        var interests_added = $("#tag_suggestion_container-interests .tag_suggestion_tags .tag");
        var interests = [];

        $.each(interests_added, function (index, item) {
            var selector = $(item);
            interests.push(selector.attr('data-tag'));
        });

        $.post('/tagsystem/tagsystem_actions_controller/save_tags', { interests:interests });
    }
};