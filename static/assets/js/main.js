$(document).ready(function (){
    //stay hidden if javascript is disabled
    $("#site").removeClass('hidden');
    
    /************************
    /*	LAYOUT
    /************************/

    /* set minimum height for content wrapper */
    $('.content-wrapper').css('min-height', $('.wrapper').outerHeight(true) - $('.top-bar').outerHeight(true));


    /************************
    /*	MAIN NAVIGATION
    /************************/

    $('.main-menu .js-sub-menu-toggle').click( function(e){

        e.preventDefault();

        $li = $(this).parent('li');
        if( !$li.hasClass('active')){
            $li.find(' > a .toggle-icon').removeClass('fa-angle-left').addClass('fa-angle-down');
            $li.addClass('active');
        }
        else {
            $li.find(' > a .toggle-icon').removeClass('fa-angle-down').addClass('fa-angle-left');
            $li.removeClass('active');
        } 

        $li.find(' > .sub-menu').slideToggle(300);
    });

    $('.js-toggle-minified').clickToggle(
        function() {
            $('.left-sidebar').addClass('minified');
            $('.content-wrapper').addClass('expanded');

            $('.left-sidebar .sub-menu')
            .css('display', 'none')
            .css('overflow', 'hidden');

            $('.main-menu > li > a > .text').animate({opacity: 0}, 200);

            $('.sidebar-minified').find('i.fa-angle-left').toggleClass('fa-angle-right');
        },
        function() {
            $('.left-sidebar').removeClass('minified');
            $('.content-wrapper').removeClass('expanded');
            $('.main-menu > li > a > .text').animate({opacity: 1}, 600);

            $('.sidebar-minified').find('i.fa-angle-left').toggleClass('fa-angle-right');
        }
    );

    // main responsive nav toggle
    $('.main-nav-toggle').clickToggle(
        function() {
            $('.left-sidebar').slideDown(300)
        },
        function() {
            $('.left-sidebar').slideUp(300);
        }
    );
    
    /********************
     * WIDGET
     *******************/
    $('.widget .btn-remove').click(function(e){
        e.preventDefault();
        $(this).parents('.widget').fadeOut(300, function(){
            $(this).remove();
        });
    });
    var affectedElement = $('.widget-content');

    $('.widget .btn-toggle-expand').clickToggle(
        function (e) {
            e.preventDefault();

            // if has scroll
            if ($('.slimScrollDiv').length > 0) {
                affectedElement = $('.slimScrollDiv');
            }

            $(this).parents('.widget').find(affectedElement).slideUp(300);
            $(this).find('i.fa-chevron-up').toggleClass('fa-chevron-down');
        },
        function (e) {
            e.preventDefault();

            // if has scroll
            if ($('.slimScrollDiv').length > 0) {
                affectedElement = $('.slimScrollDiv');
            }

            $(this).parents('.widget').find(affectedElement).slideDown(300);
            $(this).find('i.fa-chevron-up').toggleClass('fa-chevron-down');
        }
    );

    // widget focus
    $('.widget .btn-focus').clickToggle(
            function (e) {
                e.preventDefault();
                $(this).find('i.fa-eye').toggleClass('fa-eye-slash');
                $(this).parents('.widget').find('.btn-remove').addClass('link-disabled');
                $(this).parents('.widget').addClass('widget-focus-enabled');
                $('<div id="focus-overlay"></div>').hide().appendTo('body').fadeIn(300);

            },
            function (e) {
                e.preventDefault();
                $theWidget = $(this).parents('.widget');

                $(this).find('i.fa-eye').toggleClass('fa-eye-slash');
                $theWidget.find('.btn-remove').removeClass('link-disabled');
                $('body').find('#focus-overlay').fadeOut(function () {
                    $(this).remove();
                    $theWidget.removeClass('widget-focus-enabled');
                });
            }
    );

    /************************
    /*	WINDOW RESIZE
    /************************/

    $(window).bind("resize", resizeResponse);

    function resizeResponse() {

        if( $(window).width() < (992-15)) {
            if( $('.left-sidebar').hasClass('minified') ) {
                $('.left-sidebar').removeClass('minified');
                $('.left-sidebar').addClass('init-minified');
            }

        }else {
            if( $('.left-sidebar').hasClass('init-minified') ) {
                $('.left-sidebar')
                .removeClass('init-minified')
                .addClass('minified');
            }
        }
    }
    //default DataTable initisiation
    $.extend( $.fn.dataTable.defaults, {
        searching: false,
        ordering:  false,
        pageLength: 10,
        rowId: 'id',
        searchDelay: 1000
    });
    $('form.form-validation').on('keydown', 'input, select, textarea', function(e) {
        var self = $(this)
          , form = self.parents('form:eq(0)')
          , focusable
          , next
          ;
        if (e.keyCode == 13) {
            focusable = form.find('input,a,select,button,textarea').filter(':visible');
            next = focusable.eq(focusable.index(this)+1);
            if (next.length) {
                next.focus();
            }
            /*
            else {
                form.submit();
            }*/
            return false;
        }
    });
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd'
    }).on('changeDate', function (e){
        $(this).datepicker('hide');
    });
    $('[data-toggle="tooltip"]')
    
});

// toggle function
$.fn.clickToggle = function( f1, f2 ) {
    return this.each( function() {
        var clicked = false;
        $(this).bind('click', function() {
            if(clicked) {
                clicked = false;
                return f2.apply(this, arguments);
            }

            clicked = true;
            return f1.apply(this, arguments);
        });
    });

}

var ExchangeRate = function() {
    this.initiated = false;
    this.bases = {};
    this.init = function(bases){
        /*
         * Fungsi init menentukan basis rate yang digunakan berdasarkan bulan dan tahun
         * serta nilai kurs yang diperoleh dari server
         * Jika gagal, makan objek KonversiMatauang tidak bisa digunakan
         */
        
        if (typeof bases === 'undefined'){
            this.initiated = false;
            console.log('Rate bases is not defined');
            //alert('Basis rate kurs belum didefinisikan. Perhitungan kurs tidak dapat dilakukan !');
            
            return false;
        }else{
            this.setBases(bases);
        }
    };
    
    this.setBases = function(bases){
        for (var matauang in bases){
            this.bases[matauang] = parseFloat(bases[matauang]);
        }
        
        this.initiated = true;
        console.log('New currency rate bases defined');
    };
    this.rateUpdate = function(matauang,rate){
        this.bases[matauang] = rate;
    };
    this.getRate = function (matauang){
        if (!this.initiated){
            return false;
        }
        var rate = 1;
        for (var mata in this.bases){
            if (mata == matauang){
                rate = this.bases[mata];
                break;
            }
        }
        return rate;
    };
    this.getConvert = function (matauang, nilai_dasar){
        var rate = this.getRate(matauang);
        return (rate * nilai_dasar);
    };
    this.convert = function(matauang, nilaidasar){
        return this.getConvert(matauang, nilaidasar);
    };
    this.getAllRates = function(){
        return this.bases;
    };
};

var KonversiMataUang = new ExchangeRate();
