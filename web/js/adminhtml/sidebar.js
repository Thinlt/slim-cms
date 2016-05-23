/**
 * Created by thinlt on 5/5/2016.
 */
var msPointerSupported = window.navigator.msPointerEnabled;
var touch = {
    'start': msPointerSupported ? 'MSPointerDown' : 'touchstart',
    'move': msPointerSupported ? 'MSPointerMove' : 'touchmove',
    'end': msPointerSupported ? 'MSPointerUp' : 'touchend'
};

var Sidebar = function (el, max_screen_width){
    var _this = this;
    //static var
    var $bodyEl, $sidedrawerEl;

    this._scWidth = 768;
    if(typeof max_screen_width != 'undefined'){
        this._scWidth = max_screen_width;
    }

    this._isTouchStart = false; this._isTouchEnd = false; this._isTouchMove = false;
    this._startOffsetX = 0; this._transX = 0;
    this._endOffsetX = 0;

    this.$bodyEl = $('body');
    this.$sidedrawerEl = $(el);
    this._width = this.$sidedrawerEl.width();

    // ==========================================================================
    // Animate sub menu
    // ==========================================================================
    var $titleEls = $('.sub-toggle', this.$sidedrawerEl);
    $titleEls.parent('.nav-menu-items').children('.sub-menu').hide();
    $titleEls.on('click', function() {
        $(this).parent('.nav-menu-items').children('.sub-menu').slideToggle(200);
    });

    //init touch event for mobile
    $(window).on(touch.start, function(evt){_this.onTouchMoveStart(evt)});
    $(window).on(touch.move, function(evt){_this.onTouchMove(evt)});
    $(window).on(touch.end, function(evt){_this.onTouchMoveEnd(evt)});
};

Sidebar.prototype = {
    constructor : Sidebar,

    isAllowScreen : function(){
        return ($(window).width() < this._scWidth);
    },
    // public (shared across instances)
    onTouchMoveStart : function (evt){
        if(!this.isAllowScreen()) return;
        this._isTouchStart = true;
        this._isTouchMove = false;
        this._isTouchEnd = false;
        this._overlay = false;

        this._startOffsetX = evt.originalEvent.touches[0].clientX;
        this._movedRight = evt.originalEvent.touches[0].clientX;
    },

    onTouchMove : function(evt){
        if(!this.isAllowScreen()) return;
        var _this = this;
        this._isTouchMove = true;
        this._isTouchStart = false;
        this._isTouchEnd = false;

        this._endOffsetX = evt.originalEvent.touches[0].clientX;
        var dif_x = evt.originalEvent.touches[0].clientX - this._startOffsetX;
        this._transX = Math.abs(dif_x);

        //reset start offset swipe left
        if(this._endOffsetX > this._movedRight){
            this._movedRight = this._endOffsetX;
        }else if(this._startOffsetX != this._movedRight){
            this._startOffsetX = this._movedRight;
        }

        //swipe left
        if(dif_x <= 0){
            if(this.isOpen()){
                this._swipe = 'left';
                if(this.isBounded()){
                    this.$sidedrawerEl.css('transform', 'none');
                    setTimeout(function() {
                        _this.hideSidedrawer();
                    }, 200);
                }else{
                    var _transform = this._width - this._transX;
                    this.$sidedrawerEl.css('transform', 'translate('+_transform+'px'+')');
                }
            }
        }
        //swipe right
        if(dif_x >= 0){
            //only touch from left of screen
            if(this._startOffsetX <= 10 && !this.isOpen()){
                this._swipe = 'right';
                if(!this._overlay){
                    this.showOverlay();
                    this._overlay = true;
                }
                if(this.isBounded()){
                    this.$sidedrawerEl.css('transform', 'translate('+this._width+'px'+')');
                }else{
                    this.$sidedrawerEl.css('transform', 'translate('+this._transX+'px'+')');
                }
            }
        }
    },

    onTouchMoveEnd : function (evt){
        if(!this.isAllowScreen()) return;
        this._isTouchEnd = true;
        this._isTouchStart = false;
        this._isTouchMove = false;
        this._overlay = false;

        if(this.isOpen() && this._swipe == 'left'){
            if(this.isBounded()){
                this.hideSidedrawer();
            }else{
                this.showSidedrawer();
            }
        }
        if(!this.isOpen() && this._swipe == 'right'){
            if(this.isBounded()){
                this.$sidedrawerEl.addClass('active');
            }else{
                this.hideSidedrawer();
            }
        }

        this.onTouchMoveReset();
    },

    onTouchMoveReset : function (evt){
        this.$sidedrawerEl.css('transform', '');
        this._isTouchEnd = false;
        this._isTouchStart = false;
        this._isTouchMove = false;
        this._movedX = 0;
        this._swipe = '';
    },

    isBounded : function(){
        return (this._transX >= this._width/2);
    },

    isOpen : function(){
        if(this.$sidedrawerEl.hasClass('active')){
            return true;
        }else{
            return false;
        }
    },

    // ==========================================================================
    // Toggle Sidedrawer
    // ==========================================================================
    showSidedrawer : function () {
        var _this = this;
        var $overlayEl = $(mui.overlay('on', this.muiOptions()));
        // show element
        this.$sidedrawerEl.appendTo($overlayEl);
        setTimeout(function() {
            _this.$sidedrawerEl.addClass('active');
        }, 20);
        this.onTouchMoveReset(); //reset touch event
    },

    hideSidedrawer : function() {
        this.$bodyEl.toggleClass('hide-sidedrawer');
        this.hideOverlay();
        this.onTouchMoveReset(); //reset touch event
    },

    showOverlay : function(){
        // show overlay
        var $overlayEl = $(mui.overlay('on', this.muiOptions()));
        this.$sidedrawerEl.appendTo($overlayEl);
    },

    hideOverlay : function(){
        $(mui.overlay('off'));
    },

    muiOptions : function(){
        var _this = this;
        // show overlay
        var options = {
            onclose: function() {
                _this.$sidedrawerEl
                    .removeClass('active')
                    .appendTo(document.body);
            }
        };
        return options;
    }

};


var sidebar;
jQuery(document).ready(function(){
    sidebar = new Sidebar('#sidedrawer');
    //active event
    $('.js-show-sidedrawer').on('click', function(){sidebar.showSidedrawer()});
    $('.js-hide-sidedrawer').on('click', function(){sidebar.hideSidedrawer()});


    //nav menu active
    //.nav-menu, .nav-menu-items[data-url=, active-class=], .nav-items-set[.active|none]
    var menu = $('.nav-menu');
    var items = $('.nav-menu-items', menu);
    var current_url = $(location).attr('pathname').replace(/^\/+|\/+$/gm,''); //trim '/'
    items.each(function(i, e){
        if($(e).attr('data-url')){
            var data_url = $(e).attr('data-url').replace(/^\/+|\/+$/gm,''); //trim
            if(checkPath(current_url, data_url)){
                var className = $(e).attr('active-class') || 'active';
                $(e).addClass(className);
                $('.nav-items-set', $(e)).each(function(j){
                    if($(this).hasClass('display-none')){
                        $(this).css({'display':'none'});//.hide();
                    }else if($(this).hasClass('display-remove')){
                        $(this).remove();
                    }{
                        $(this).show();
                    }
                });
            }
        }
    });

    function checkPath(current_url, data_url){
        var url_paths = current_url.split('/');
        var data_paths = data_url.split('/');
        //var match_continue = false;
        if(data_url.match(/\{.+?\}/gm)){
            var i;
            for(i=0; i<data_paths.length; i++){
                if(data_paths[i] && !data_paths[i].match(/\{.+\}/gm) && url_paths[i] && url_paths[i] == data_paths[i]){
                    //match_continue = true;
                    continue;
                }else if(data_paths[i]){
                    var path = data_paths[i].replace(/^\{+|\}+$/gm,'') || ''; //trim {}
                    var paths = path.split(',');
                    var j;
                    for(j=0; j<paths.length; j++){
                        if(url_paths[i] == paths[j] || (url_paths[i] == null && paths[j] == '')){
                            break;
                        }
                    }
                    if(j >= paths.length){
                        return false;
                    }
                }
            }
            if(i >= data_paths.length){
                return true;
            }

        }else if(current_url == data_url){
            return true;
        }
        return false;
    }

});
