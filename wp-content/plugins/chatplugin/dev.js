jQuery.each(JSON.parse(jQuery.cookie('chat_conversationTabsAndPosition')), function (index, value) {
    console.log(value.partnerId);
});