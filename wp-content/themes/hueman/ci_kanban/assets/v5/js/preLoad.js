var browser = {

    width: $(window).width(),
    height: $(window).height(),

    scrollTop: function() {
        $("html, body").animate({ scrollTop: 0 }, "slow");
    }
};





//================================================================================================================================================================
// Scroll loading
//================================================================================================================================================================
var scrollLoad = {
    // Auto load on scroll things
    autoLoadPage: 1,
    autoLoadLast: false,
    keepScrollLoading: true,
    keepLoadingForever: false, // Will be true when user clicks on "keep loading"
    hasShownLoadForeverBtn: false,

    url: false,
    container: false,
    countMultiplier: false,

    setAutoLoad: function(url, container, countMultiplier, urlContainStringQuery)
    {
        scrollLoad.url              = url;
        scrollLoad.container        = container;
        scrollLoad.countMultiplier  = countMultiplier;

        $(window).scroll(function()
        {
            if(!scrollLoad.keepScrollLoading) return false;
            if($(window).scrollTop() + 950 >= ($(document).height() - ($(window).height()))) scrollLoad.load(false, urlContainStringQuery);
        });
    },


    load: function(callback, urlContainStringQuery) {
        var startCount  = scrollLoad.autoLoadPage * scrollLoad.countMultiplier;
        scrollLoad.keepScrollLoading = false;
        scrollLoad.autoLoadPage++;
        if(scrollLoad.autoLoadLast == scrollLoad.autoLoadPage) return false;
        scrollLoad.autoLoadLast = scrollLoad.autoLoadPage;
        lightbox.openLoading();

        $.get('/' + scrollLoad.url + (urlContainStringQuery ? "&" : "?") + "start=" + startCount + "&isScrollLoad=TRUE", function(result)
        {
            scrollLoad.keepScrollLoading = true;
            if(!result) scrollLoad.keepScrollLoading = false;
            $(scrollLoad.container).append(result);
            lightbox.closeLoading();
            if(callback) callback();
        });
    },

    setLoadForever: function() {
        $("#scrollLoad_showMore_container").remove();
        scrollLoad.keepLoadingForever = true;
        scrollLoad.load(function() {
            stickySticky.noFooter_componentList = ['', 'photos'];
            stickySticky.initialize_sidebar(); // This is so the sidebar sticks normally again
        });
    }
};






//================================================================================================================================================================
// Top of site notification
//================================================================================================================================================================
var topSiteAlert = function(text, cssClass) {
    var element = $("#top_site_notification");
    element.attr('class', '');
    element.addClass(cssClass);
    element.addClass('alert');
    element.text(text);
    element.show();
    setTimeout(function() {element.fadeOut(); element.text(''); }, 4000)
};

var topSiteAlert_sticky = function(text, cssClass) {
    var element = $("#top_site_notification");
    element.attr('class', '');
    element.addClass(cssClass);
    element.addClass('alert');
    element.text(text);
    element.show();
};

var topSiteAlert_hide = function() {
    $("#top_site_notification").hide().text('');
}
