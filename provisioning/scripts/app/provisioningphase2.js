/**
 * View logic for Provisioningactions
 */

/**
 * application logic specific to the Provisioningaction listing page
 */
var page = {
    provisioningactions: new model.ProvisioningactionCollection(),
    collectionView: null,
    provisioningaction: null,
    modelView: null,
    isInitialized: false,
    isInitializing: false,
    fetchParams: {filter: '', orderBy: '', orderDesc: '', page: 1},
    fetchInProgress: false,
    dialogIsOpen: false,
    /**
     *
     */

    init: function() {







        // ensure initialization only occurs once
        if (page.isInitialized || page.isInitializing)
            return;
        page.isInitializing = true;
        if (!$.isReady && console)
            console.warn('page was initialized before dom is ready.  views may not render properly.');
        /* setInterval(function() {
         $('.pager').remove();
         $('#pagination').after('<ul class="pagination  pager" id="myPager"></ul>'); */
       page.pageMe();
       // page.applyPage();

        /* }, 1000); */
         


    },
    pageMe: function() {
        var $this = $('#paginationTable'),
                defaults = {
                    perPage: 4,
                    showPrevNext: true,
                    hidePageNumbers: false
                },
              
        settings = defaults;
        var listElement = $this;
        var perPage = settings.perPage;
        var children = listElement.children('.items');
        
       
        
        var pager = $('.pager');
        
        if (typeof settings.childSelector != "undefined") {
            children = listElement.find(settings.childSelector);
        }

        if (typeof settings.pagerSelector != "undefined") {
            pager = $(settings.pagerSelector);
        }

        var numItems = children.size();
        var numPages = Math.ceil(numItems / perPage);
        pager.data("curr", 0);
        if (settings.showPrevNext) {
            $('<li><a href="#" class="prev_link">«</a></li>').appendTo(pager);
        }

        var curr = 0;
        var myPage = curr + 1;
        $('.pagenum').html('<b class="badge badge-info">Page n. ' + myPage + '/' + numPages + '</b>');
        while (numPages > curr && (settings.hidePageNumbers == false)) {
            $('<li><a href="#" class="page_link">' + (curr + 1) + '</a></li>').appendTo(pager);
            curr++;
        }

        if (settings.showPrevNext) {
            $('<li><a href="#" class="next_link">»</a></li>').appendTo(pager);
        }

        pager.find('.page_link:first').addClass('active');
        pager.find('.prev_link').hide();
        if (numPages <= 1) {
            pager.find('.next_link').hide();
        }
        pager.children().eq(1).addClass("active");
        children.hide();
        children.slice(0, perPage).show();
        pager.find('li .page_link').click(function() {
            var clickedPage = $(this).html().valueOf() - 1;
            goTo(clickedPage, perPage);
            return false;
        });
        pager.find('li .prev_link').click(function() {
            previous();
            return false;
        });
        pager.find('li .next_link').click(function() {
            next();
            return false;
        });
        function previous() {
            var goToPage = parseInt(pager.data("curr")) - 1;
            var myPage = goToPage + 1;
            $('.pagenum').html('<b class="badge badge-info">Page n. ' + myPage + '/' + numPages + '</b>');
            goTo(goToPage);
        }

        function next() {
            var goToPage = parseInt(pager.data("curr")) + 1;
            var myPage = goToPage + 1;
            $('.pagenum').html('<b class="badge badge-info">Page n. ' + myPage + '/' + numPages + '</b>');
            goTo(goToPage);
        }

        function goTo(page) {
            var startAt = page * perPage,
                    endOn = startAt + perPage;
            children.css('display', 'none').slice(startAt, endOn).show();
            if (page >= 1) {
                pager.find('.prev_link').show();
            }
            else {
                pager.find('.prev_link').hide();
            }

            if (page < (numPages - 1)) {
                pager.find('.next_link').show();
            }
            else {
                pager.find('.next_link').hide();
            }
            // console.log(children);
            pager.data("curr", page);
            pager.children().removeClass("active");
            pager.children().eq(page + 1).addClass("active");
            var myPage = page + 1;
            $('.pagenum').html('<b class="badge badge-info">Page n. ' + myPage + '/' + numPages + '</b>');

        }


    }
    
};



