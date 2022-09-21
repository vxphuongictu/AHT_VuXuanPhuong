/**
 *  Ajax Autocomplete for jQuery, version 1.2.27
 *  (c) 2015 Tomas Kirda
 *
 *  Ajax Autocomplete for jQuery is freely distributable under the terms of an MIT-style license.
 *  For details, see the web site: https://github.com/devbridge/jQuery-Autocomplete
 *
 *  Modified by Damian Góra: http://damiangora.com
 *  Minify: https://www.toptal.com/developers/javascript-minifier/
 */

/*jslint  browser: true, white: true, single: true, this: true, multivar: true */
/*global define, window, document, jQuery, exports, require */

// Expose plugin as an AMD module if AMD loader is present:
(function (factory) {
    "use strict";
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as an anonymous module.
        define(['jquery'], factory);
    } else if (typeof exports === 'object' && typeof require === 'function') {
        // Browserify
        factory(require('jquery'));
    } else {
        // Browser globals
        factory(jQuery);
    }
}(function ($) {
    'use strict';

    var utils = (function () {
            return {
                escapeRegExChars: function (value) {
                    return value.replace(/[|\\{}()[\]^$+*?.]/g, "\\$&");
                },
                createNode: function (containerClass) {
                    var div = document.createElement('div');
                    div.className = containerClass;
                    div.style.position = 'absolute';
                    div.style.display = 'none';
                    div.setAttribute('unselectable', 'on');
                    return div;
                },
                highlight: function (suggestionValue, phrase) {

                    if (dgwt_wcas.is_premium) {
                        var i,
                            tokens = phrase.split(/ /),
                            highlighted = false,
                            last = '';

                        if (tokens) {
                            last = tokens[tokens.length - 1];
                            tokens = tokens.sort(function (a, b) {
                                return b.length - a.length;
                            });

                            for (i = 0; i < tokens.length; i++) {
                                if (tokens[i] && tokens[i].length >= 1) {

                                    var token = tokens[i].replace(/[\^\@]/g, '');

                                    if (token.length > 0) {
                                        if (token.trim().length === 1 && tokens[i] !== last) {
                                            var pattern = '((\\s|^)' + utils.escapeRegExChars(token.trim()) + '\\s)';
                                        } else if (token.trim().length === 1 && tokens[i] === last) {
                                            var pattern = '((\\s|^)' + utils.escapeRegExChars(token.trim()) + ')';
                                        } else {
                                            var pattern = '(' + utils.escapeRegExChars(token.trim()) + ')';
                                        }

                                        suggestionValue = suggestionValue.replace(new RegExp(pattern, 'gi'), '\^\^$1\@\@');
                                        highlighted = true;
                                    }
                                }
                            }
                        }

                        if (highlighted) {
                            suggestionValue = suggestionValue.replace(/\^\^/g, '<strong>');
                            suggestionValue = suggestionValue.replace(/@@/g, '<\/strong>');
                        }


                    } else {
                        var pattern = '(' + utils.escapeRegExChars(phrase) + ')';
                        suggestionValue = suggestionValue.replace(new RegExp(pattern, 'gi'), '<strong>$1<\/strong>');
                    }

                    return suggestionValue;
                },
                debounce: function (func, wait) {
                    var timeout,
                        debounceID = new Date().getUTCMilliseconds();

                    // First query in the chain
                    if (ajaxDebounceState.id.length === 0) {
                        ajaxDebounceState.id = debounceID;
                        func();
                        return;
                    }

                    ajaxDebounceState.id = debounceID;

                    timeout = setTimeout(function () {

                        if (debounceID !== ajaxDebounceState.id) {
                            clearTimeout(timeout);
                            return;
                        }

                        // Last query in the chain
                        func();
                        ajaxDebounceState.id = '';

                    }, wait);
                },
                mouseHoverDebounce: function (func, selector, wait) {
                    var timeout;

                    timeout = setTimeout(function () {

                        if ($(selector + ':hover').length > 0) {
                            func();
                        } else {
                            clearTimeout(timeout);
                            return;
                        }


                    }, wait);
                },
                isTextSelected: function () {
                    var selected = false,
                        selObj = document.getSelection();

                    if (typeof selObj == 'object') {
                        if (selObj.toString().length > 0) {
                            selected = true;
                        }
                    }
                    return selected
                },
                getActiveInstance: function () {
                    var $el = $('.dgwt-wcas-search-wrapp.dgwt-wcas-active'),
                        instance;
                    if ($el.length > 0) {
                        $el.each(function () {
                            var $input = $(this).find('.dgwt-wcas-search-input');
                            if (typeof $input.data('autocomplete') == 'object') {
                                instance = $input.data('autocomplete');
                                return false;
                            }
                        });
                    }

                    return instance;
                },
                hashCode: function (s) {
                    var h = 0, i = s.length;
                    while (i > 0) {
                        h = (h << 5) - h + s.charCodeAt(--i) | 0;
                    }
                    return h < 0 ? h * -1 : h;
                }
            };
        }()),
        ajaxDebounceState = {
            id: '',
            callback: null,
            ajaxSettings: null,
            object: null,
        },
        keys = {
            ESC: 27,
            TAB: 9,
            RETURN: 13,
            LEFT: 37,
            UP: 38,
            RIGHT: 39,
            DOWN: 40
        },
        noop = $.noop;

    function DgwtWcasAutocompleteSearch(el, options) {
        var that = this;

        // Shared variables:
        that.element = el;
        that.el = $(el);
        that.suggestions = [];
        that.badQueries = [];
        that.selectedIndex = -1;
        that.currentValue = that.element.value;
        that.timeoutId = null;
        that.cachedResponse = {};
        that.cachedDetails = {};
        that.cachedPrices = {};
        that.detailsRequestsSent = [];
        that.onChangeTimeout = null;
        that.onChange = null;
        that.isLocal = false;
        that.suggestionsContainer = null;
        that.detailsContainer = null;
        that.autoAligmentprocess = null;
        that.noSuggestionsContainer = null;
        that.latestActivateSource = '';
        that.actionTriggerSource = '';
        that.options = $.extend(true, {}, DgwtWcasAutocompleteSearch.defaults, options);
        that.classes = {
            selected: 'dgwt-wcas-suggestion-selected',
            suggestion: 'dgwt-wcas-suggestion',
            suggestionsContainerOrientTop: 'dgwt-wcas-suggestions-wrapp--top',
            inputFilled: 'dgwt-wcas-search-filled',
            darkenOverlayMounted: 'js-dgwt-wcas-search-darkoverl-mounted',
            fixed: 'dgwt-wcas-suggestions-wrapp-fixed'
        };
        that.hint = null;
        that.hintValue = '';
        that.selection = null;
        that.overlayMobileState = 'off';
        that.overlayDarkenedState = 'off';
        that.isMouseDownOnSearchElements = false;

        // Voice search
        that.voiceSearchRecognition = null;
        that.voiceSearchStarted = null;

        // Initialize and set options:
        that.initialize();
        that.setOptions(options);

    }

    DgwtWcasAutocompleteSearch.utils = utils;

    $.DgwtWcasAutocompleteSearch = DgwtWcasAutocompleteSearch;

    DgwtWcasAutocompleteSearch.defaults = {
        ajaxSettings: {},
        autoSelectFirst: false,
        appendTo: 'body',
        serviceUrl: null,
        lookup: null,
        onSelect: null,
        containerDetailsWidth: 'auto',
        showDetailsPanel: false,
        showImage: false,
        showPrice: false,
        showSKU: false,
        showDescription: false,
        showSaleBadge: false,
        showFeaturedBadge: false,
        dynamicPrices: false,
        saleBadgeText: 'sale',
        featuredBadgeText: 'featured',
        minChars: 3,
        maxHeight: 600,
        dpusbBreakpoint: 550, // (details panel under search bar - breakpoint) If search bar width is lower than this option, suggestions wrapper and details panel will show under search bar with the same width
        deferRequestBy: 0,
        params: {},
        formatResult: _formatResult,
        delimiter: null,
        zIndex: 999999999,
        type: 'GET',
        noCache: false,
        isRtl: false,
        onSearchStart: noop,
        onSearchComplete: noop,
        onSearchError: noop,
        preserveInput: false,
        searchFormClass: 'dgwt-wcas-search-wrapp',
        containerClass: 'dgwt-wcas-suggestions-wrapp',
        containerDetailsClass: 'dgwt-wcas-details-wrapp',
        darkenedOverlayClass: 'dgwt-wcas-darkened-overlay',
        searchInputClass: 'dgwt-wcas-search-input',
        preloaderClass: 'dgwt-wcas-preloader',
        closeTrigger: 'dgwt-wcas-close',
        formClass: 'dgwt-wcas-search-form',
        voiceSearchClass: 'dgwt-wcas-voice-search',
        voiceSearchSupportedClass: 'dgwt-wcas-voice-search-supported',
        voiceSearchActiveClass: 'dgwt-wcas-voice-search-active',
        voiceSearchDisabledClass: 'dgwt-wcas-voice-search-disabled',
        tabDisabled: false,
        dataType: 'text',
        currentRequest: null,
        triggerSelectOnValidInput: true,
        isPremium: false,
        overlayMobile: false,
        preventBadQueries: true,
        lookupFilter: _lookupFilter,
        paramName: 'query',
        transformResult: _transformResult,
        noSuggestionNotice: 'No results',
        forceFixPosition: false,
        positionFixed: false,
        debounceWaitMs: 400,
        sendGAEvents: true,
        enableGASiteSearchModule: false,
        showProductVendor: false,
        disableHits: false,
        disableSubmit: false,
        voiceSearchEnabled: false,
        voiceSearchLang: '',
    }

    function _lookupFilter(suggestion, originalQuery, queryLowerCase) {
        return suggestion.value.toLowerCase().indexOf(queryLowerCase) !== -1;
    }

    function _transformResult(response) {
        return typeof response === 'string' ? JSON.parse(response) : response;
    }

    function _formatResult(suggestionValue, currentValue, highlight, options) {
        // Do not replace anything if there current value is empty
        if (!currentValue) {
            return suggestionValue;
        }

        if (highlight) {
            suggestionValue = utils.highlight(suggestionValue, currentValue);
        }

        if (!options.convertHtml) {
            return suggestionValue;
        }

        return suggestionValue.replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&apos;')
            .replace(/&lt;sup/g, '<sup')
            .replace(/&lt;\/sup/g, '</sup')
            .replace(/sup&gt;/g, 'sup>')
            .replace(/&lt;(\/?(strong|b|br|span))&gt;/g, '<$1>')
            .replace(/&lt;(strong|span)\s+class\s*=\s*&quot;([^&]+)&quot;&gt;/g, '<$1 class="$2">');

    }

    DgwtWcasAutocompleteSearch.prototype = {
        initialize: function () {
            var that = this;

            // Remove autocomplete attribute to prevent native suggestions:
            that.element.setAttribute('autocomplete', 'off');

            that.options.params = that.applyCustomParams(that.options.params);

            that.createContainers();

            that.registerEventsSearchBar();
            that.registerEventsSuggestions();
            that.registerEventsDetailsPanel();
            that.registerIconHandler();
            that.registerFlexibleLayout();
            that.initVoiceSearch();

            that.fixPosition = function () {
                that.adjustContainerWidth();
                if (that.visible) {
                    that.fixPositionSuggestions();
                    if (that.canShowDetailsPanel()) {
                        that.fixPositionDetailsPanel();
                    }
                }
                that.positionOverlayDarkened();
            };

            // Fix position on resize
            $(window).on('resize.autocomplete', function () {
                var that = utils.getActiveInstance();
                clearTimeout(window.dgwt_wcas.resizeOnlyOnce);
                if (typeof that != 'undefined') {
                    window.dgwt_wcas.resizeOnlyOnce = setTimeout(function () {
                        that.fixPosition();
                    }, 100);
                }
            });

            // Fix position on scroll
            $(window).on('scroll.autocomplete', function () {
                var that = utils.getActiveInstance();
                clearTimeout(window.dgwt_wcas.scrollOnlyOnce);
                if (typeof that != 'undefined') {
                    window.dgwt_wcas.scrollOnlyOnce = setTimeout(function () {
                        that.fixPosition();
                    }, 100);
                }
            });

            // Trigger only when x axis is changed
            var windowWidth = $(window).width();
            $(window).on('resize.autocomplete', function () {
                var newWidth = $(window).width();
                if (newWidth != windowWidth) {
                    that.toggleMobileOverlayMode();
                    windowWidth = newWidth;
                }
            });

            if (that.isBreakpointReached('mobile-overlay')) {
                that.activateMobileOverlayMode();
            }

            that.hideAfterClickOutsideListener();

            // Mark as initialized
            that.suggestionsContainer.addClass('js-dgwt-wcas-initialized');

            if (that.detailsContainer && that.detailsContainer.length > 0) {
                that.detailsContainer.addClass('js-dgwt-wcas-initialized');
            }
        },
        createContainers: function (type) {
            var that = this,
                options = that.options;

            // Suggestions
            if ($('.' + options.containerClass).length == 0) {

                that.suggestionsContainer = $(DgwtWcasAutocompleteSearch.utils.createNode(options.containerClass));

                that.suggestionsContainer.appendTo(options.appendTo || 'body');

                that.suggestionsContainer.addClass('woocommerce');

                // Add conditional classes
                if (options.showImage === true) {
                    that.suggestionsContainer.addClass('dgwt-wcas-has-img');
                }

                // Price
                if (options.showPrice === true) {
                    that.suggestionsContainer.addClass('dgwt-wcas-has-price');
                }

                // Description
                if (options.showDescription === true) {
                    that.suggestionsContainer.addClass('dgwt-wcas-has-desc');
                }

                // SKU
                if (options.showSKU === true) {
                    that.suggestionsContainer.addClass('dgwt-wcas-has-sku');
                }

                // Headings
                if (options.showHeadings === true) {
                    that.suggestionsContainer.addClass('dgwt-wcas-has-headings');
                }

            } else {

                that.suggestionsContainer = $('.' + that.options.containerClass);

            }

            // Details Panel
            if (that.canShowDetailsPanel()) {

                if ($('.' + options.containerDetailsClass).length == 0) {
                    that.detailsContainer = $(DgwtWcasAutocompleteSearch.utils.createNode(options.containerDetailsClass));
                    that.detailsContainer.appendTo(options.appendTo || 'body');

                    that.detailsContainer.addClass('woocommerce');
                } else {

                    that.detailsContainer = $('.' + options.containerDetailsClass);

                }

            }
        },
        registerEventsSearchBar: function () {
            var that = this;

            // Extra tasks on submit
            that.getForm().on('submit.autocomplete', function (e) {

                if (that.options.disableSubmit) {
                    e.preventDefault();
                    return false;
                }

                // Prevent submit empty form
                var $input = $(this).find('.' + that.options.searchInputClass);
                if ($input.length && $input.val().length === 0) {
                    e.preventDefault();
                    return false;
                }

                // If variation suggestion exist, click it instead submit search results page
                if (that.suggestions.length > 0) {

                    $.each(that.suggestions, function (i, suggestion) {

                        if (
                            typeof suggestion.type != 'undefined'
                            && suggestion.type == 'product_variation'
                        ) {
                            that.select(i);
                            e.preventDefault();
                            return false;
                        }
                    });

                }

                // Clean before submit
                that.closeOverlayMobile();

            });

            // Position preloader
            if (document.readyState === 'complete') {
                that.positionPreloaderAndMic();
            } else {
                $(window).on('load', function () {
                    that.positionPreloaderAndMic();
                });
            }

            that.el.on('keydown.autocomplete', function (e) {
                that.onKeyPress(e);
            });
            that.el.on('keyup.autocomplete', function (e) {
                that.onKeyUp(e);
            });
            that.el.on('blur.autocomplete', function () {
                that.onBlur();
            });
            that.el.on('focus.autocomplete', function (e) {
                that.onFocus(e);
            });
            that.el.on('change.autocomplete', function (e) {
                that.onKeyUp(e);
            });
            that.el.on('input.autocomplete', function (e) {
                that.onKeyUp(e);
            });

        },
        registerEventsSuggestions: function () {
            var that = this,
                suggestionSelector = '.' + that.classes.suggestion,
                suggestionsContainer = that.getSuggestionsContainer();

            // Register these events only once
            if (suggestionsContainer.hasClass('js-dgwt-wcas-initialized')) {
                return;
            }

            // Select suggestion and enable details panel on hovering over it
            $(document).on('mouseenter.autocomplete', suggestionSelector, function () {
                var that = utils.getActiveInstance();

                if (typeof that == 'undefined') {
                    return;
                }

                var currentIndex = $(this).data('index');
                var selector = '.dgwt-wcas-suggestion[data-index="' + currentIndex + '"]';

                var timeOffset = that.canShowDetailsPanel() ? 100 : 1;

                if (that.selectedIndex != currentIndex) {
                    utils.mouseHoverDebounce(function () {
                        if (that.selectedIndex !== currentIndex) {
                            that.latestActivateSource = 'mouse';
                            that.getDetails(that.suggestions[currentIndex]);
                            that.activate(currentIndex);
                        }
                    }, selector, timeOffset);

                }
            });

            var alreadyClicked = false;
            // Redirect to the new URL after click a suggestions
            $(document).on('click.autocomplete', suggestionSelector, function (e) {
                if (!alreadyClicked) {
                    var that = utils.getActiveInstance();
                    that.actionTriggerSource = 'click';

                    alreadyClicked = true;
                    setTimeout(function () {
                        alreadyClicked = false;
                    }, 500);

                    if (typeof e.ctrlKey === 'undefined' || e.ctrlKey === false) {
                        that.select($(this).data('index'));
                        e.preventDefault();
                    }
                } else {
                    e.preventDefault();
                }
            });

            // FIX issue with touchpads for some laptops (marginal cases)
            $(document).on('mousedown.autocomplete', suggestionSelector, function (e) {
                var _this = this;
                if (e.button === 0) {
                    setTimeout(function () {
                        if (!alreadyClicked) {
                            var that = utils.getActiveInstance();
                            that.select($(_this).data('index'));
                        }
                    }, 250);
                }
            });

            // Mark cursor position for onBlur event
            $('.' + that.options.containerClass).on('mousedown.autocomplete', function (e) {
                var that = utils.getActiveInstance();
                that.isMouseDownOnSearchElements = true;
            });

        },
        registerEventsDetailsPanel: function () {
            var that = this,
                detailsContainer = that.getDetailsContainer();

            if (!that.canShowDetailsPanel() || detailsContainer.hasClass('js-dgwt-wcas-initialized')) {
                return;
            }

            // Update quantity
            $(document).on('change.autocomplete', '[name="js-dgwt-wcas-quantity"]', function (e) {
                var $input = $(this).closest('.js-dgwt-wcas-pd-addtc').find('[data-quantity]');
                $input.attr('data-quantity', $(this).val());
            });

            // Mark cursor position for onBlur event
            $('.' + that.options.containerDetailsClass).on('mousedown.autocomplete', function (e) {
                var that = utils.getActiveInstance();
                that.isMouseDownOnSearchElements = true;
            });

        },
        registerIconHandler: function () {
            var that = this,
                $formWrapper = that.getFormWrapper(),
                $form = that.getForm();

            $formWrapper.on('click.autocomplete', '.js-dgwt-wcas-search-icon-handler', function (e) {

                var $input = $formWrapper.find('.' + that.options.searchInputClass);

                if ($formWrapper.hasClass('dgwt-wcas-layout-icon-open')) {
                    that.hide();
                    $form.hide(true);

                    $formWrapper.removeClass('dgwt-wcas-layout-icon-open');


                } else {
                    var $arrow = $formWrapper.find('.dgwt-wcas-search-icon-arrow');
                    $form.hide();
                    $arrow.hide();
                    $formWrapper.addClass('dgwt-wcas-layout-icon-open');
                    that.positionIconSearchMode($formWrapper);

                    $form.fadeIn(50, function () {
                        $arrow.show();
                        that.positionPreloaderAndMic($formWrapper);
                        $input.focus();
                    });

                    setTimeout(function () {
                        that.fixPosition();
                    }, 110);

                }

            });

            if ($('.js-dgwt-wcas-initialized').length == 0 && $('.js-dgwt-wcas-search-icon-handler').length > 0) {

                $(document).on('click.autocomplete', function (event) {

                    if ($('.dgwt-wcas-layout-icon-open').length) {

                        var $target = $(event.target);

                        if (!($target.closest('.' + that.options.searchFormClass).length > 0
                            || $target.closest('.' + that.options.containerClass).length > 0
                            || $target.closest('.' + that.options.containerDetailsClass).length > 0
                        )) {
                            that.hideIconModeSearch();
                        }

                    }
                });
            }
        },
        registerFlexibleLayout: function () {
            var that = this;

            // Trigger only when x axis is changed
            var windowWidth = $(window).width();
            $(window).on('resize.autocomplete', function () {
                var newWidth = $(window).width();
                if (newWidth != windowWidth) {
                    that.reloadFlexibleLayout();
                    windowWidth = newWidth;
                }
            });

            if (document.readyState == 'complete') {
                that.reloadFlexibleLayout();
            } else {
                $(window).on('load.autocomplete', function () {
                    that.reloadFlexibleLayout();
                });
            }

        },
        activateMobileOverlayMode: function () {
            var that = this,
                $formWrapper = that.getFormWrapper();

            if (
                $formWrapper.hasClass('js-dgwt-wcas-mobile-overlay-enabled')
                && !$formWrapper.find('.js-dgwt-wcas-enable-mobile-form').length
            ) {

                $formWrapper.prepend('<div class="js-dgwt-wcas-enable-mobile-form dgwt-wcas-enable-mobile-form"></div>');
                $formWrapper.addClass('dgwt-wcas-mobile-overlay-trigger-active');

                var $el = $formWrapper.find('.js-dgwt-wcas-enable-mobile-form');

                $el.on('click.autocomplete', function (e) {

                    if (that.options.mobileOverlayDelay > 0) {
                        setTimeout(function () {
                            that.showMobileOverlay();
                        }, that.options.mobileOverlayDelay);
                    } else {
                        that.showMobileOverlay();
                    }

                });

            }

        },
        deactivateMobileOverlayMode: function () {
            var that = this,
                $formWrapper = that.getFormWrapper(),
                $suggestionsWrapper = that.getSuggestionsContainer();

            var $el = $formWrapper.find('.js-dgwt-wcas-enable-mobile-form');

            if ($formWrapper.hasClass('js-dgwt-wcas-mobile-overlay-enabled')
                && $el.length
            ) {
                that.closeOverlayMobile();
                $el.remove();
                $formWrapper.removeClass('dgwt-wcas-mobile-overlay-trigger-active');
            }

        },
        toggleMobileOverlayMode: function () {
            var that = this,
                $formWrapper = that.getFormWrapper(),
                isMobOverlayEnabled = false;

            // Break early if this search bar shouldn't open in overlay mobile mode
            if (!$formWrapper.hasClass('js-dgwt-wcas-mobile-overlay-enabled')) {
                return;
            }

            // Determine the search should open in mobile overlay
            if ($formWrapper.find('.js-dgwt-wcas-enable-mobile-form').length) {
                isMobOverlayEnabled = true;
            }

            // Toggled?
            if (
                (!isMobOverlayEnabled && that.isBreakpointReached('mobile-overlay'))
                || (isMobOverlayEnabled && !that.isBreakpointReached('mobile-overlay'))
            ) {

                var $suggestionsWrapper = that.getSuggestionsContainer();

                that.close(false);

                if ($suggestionsWrapper.length) {
                    $suggestionsWrapper.html('');
                }

                that.hideIconModeSearch();
            }

            // Activate overlay on mobile feature
            if (!isMobOverlayEnabled && that.isBreakpointReached('mobile-overlay')) {
                that.activateMobileOverlayMode();
            }

            // Deactivate overlay on mobile feature
            if (isMobOverlayEnabled && !that.isBreakpointReached('mobile-overlay')) {
                that.deactivateMobileOverlayMode();
            }
        },
        showMobileOverlay: function () {
            var that = this;

            if (that.overlayMobileState === 'on') {
                return;
            }

            that.overlayMobileState = 'on';

            var zIndex = 99999999999,
                $wrapper = that.getFormWrapper(),
                $suggestionsWrapp = that.getSuggestionsContainer(),
                $overlayWrap,
                html = '';

            $('html').addClass('dgwt-wcas-overlay-mobile-on');
            $('html').addClass('dgwt-wcas-open-' + that.getSearchStyle());
            html += '<div class="js-dgwt-wcas-overlay-mobile dgwt-wcas-overlay-mobile">';
            html += '<div class="dgwt-wcas-om-bar js-dgwt-wcas-om-bar">';
            html += '<button class="dgwt-wcas-om-return js-dgwt-wcas-om-return">'
            if (typeof dgwt_wcas.back_icon == 'string') {
                html += dgwt_wcas.back_icon;
            }
            html += '</button>';
            html += '</div>';
            html += '</div>';

            // Create overlay
            $(that.options.mobileOverlayWrapper).append(html);
            $overlayWrap = $('.js-dgwt-wcas-overlay-mobile');
            $overlayWrap.css('zIndex', zIndex);

            $wrapper.after('<span class="js-dgwt-wcas-om-hook"></span>');
            $wrapper.appendTo('.js-dgwt-wcas-om-bar');
            $suggestionsWrapp.appendTo('.js-dgwt-wcas-om-bar');
            $wrapper.addClass('dgwt-wcas-search-wrapp-mobile');

            if ($wrapper.hasClass('dgwt-wcas-has-submit')) {
                $wrapper.addClass('dgwt-wcas-has-submit-off');
                $wrapper.removeClass('dgwt-wcas-has-submit');
            }

            $wrapper.find('.' + that.options.searchInputClass).focus();

            $(document).on('click.autocomplete', '.js-dgwt-wcas-om-return', function (e) {
                that.closeOverlayMobile($overlayWrap);
            });
        },
        closeOverlayMobile: function ($overlayWrap) {
            var that = this;

            if (!$('html').hasClass('dgwt-wcas-overlay-mobile-on')) {
                that.overlayMobileState = 'off';
                return;
            }

            var $suggestionsWrapp = that.getSuggestionsContainer();

            var $clonedForm = $('.js-dgwt-wcas-om-bar').find('.' + that.options.searchFormClass);

            if ($clonedForm.hasClass('dgwt-wcas-has-submit-off')) {
                $clonedForm.removeClass('dgwt-wcas-has-submit-off');
                $clonedForm.addClass('dgwt-wcas-has-submit');
            }

            $clonedForm.removeClass('dgwt-wcas-search-wrapp-mobile');
            $('html').removeClass('dgwt-wcas-overlay-mobile-on');
            $('html').removeClass('dgwt-wcas-open-' + that.getSearchStyle());
            $suggestionsWrapp.appendTo('body');
            $suggestionsWrapp.removeAttr('body-scroll-lock-ignore');
            $('.js-dgwt-wcas-om-hook').after($clonedForm);
            $('.js-dgwt-wcas-overlay-mobile').remove();
            $('.js-dgwt-wcas-om-hook').remove();

            setTimeout(function () {
                $clonedForm.find('.' + that.options.searchInputClass).val('');
                var $closeBtn = $clonedForm.find('.dgwt-wcas-close');
                if ($clonedForm.length > 0) {
                    $closeBtn.removeClass('dgwt-wcas-close');
                    $closeBtn.html('');
                }

                that.hide();

            }, 150);


            that.overlayMobileState = 'off';
        },
        reloadFlexibleLayout: function () {
            var that = this,
                $searchWrapp = that.getFormWrapper(),
                flexibleMode = 0;

            /**
             * flexibleMode
             * 0 = not set
             * 1 = Icon on mobile, search bar on desktop
             * 2 = Icon on desktop, search bar on mobile
             */

            if ($searchWrapp.hasClass('js-dgwt-wcas-layout-icon-flexible')) {
                flexibleMode = 1;
            }

            if ($searchWrapp.hasClass('js-dgwt-wcas-layout-icon-flexible-inv')) {
                flexibleMode = 2;
            }
            if (flexibleMode > 0) {

                if (
                    (flexibleMode === 1 && that.isBreakpointReached('search-layout'))
                    || (flexibleMode === 2 && !that.isBreakpointReached('search-layout'))
                ) {
                    $searchWrapp.addClass('js-dgwt-wcas-layout-icon');
                    $searchWrapp.addClass('dgwt-wcas-layout-icon');
                } else {
                    $searchWrapp.removeClass('js-dgwt-wcas-layout-icon');
                    $searchWrapp.removeClass('dgwt-wcas-layout-icon');
                }

                $searchWrapp.addClass('dgwt-wcas-layout-icon-flexible-loaded');
            }
        },
        onFocus: function (e) {
            var that = this,
                $formWrapper = that.getFormWrapper(),
                options = that.options;
            // Mark as active
            $('.' + options.searchFormClass).removeClass('dgwt-wcas-active');
            $formWrapper.addClass('dgwt-wcas-active');

            // Mark as focus
            $('body').addClass('dgwt-wcas-focused');
            $formWrapper.addClass('dgwt-wcas-search-focused');

            if ($(e.target).closest('.dgwt-wcas-search-wrapp-mobile').length == 0) {
                that.enableOverlayDarkened();
            }

            that.fixPosition();
            if (that.el.val().length >= that.options.minChars) {
                that.onValueChange();
            }

        },
        onBlur: function () {
            var that = this,
                options = that.options,
                value = that.el.val(),
                query = that.getQuery(value);

            // Remove focused classes
            $('body').removeClass('dgwt-wcas-focused');
            $('.' + options.searchFormClass).removeClass('dgwt-wcas-search-focused');

            if (!that.isMouseDownOnSearchElements) {

                that.hide();

                if (that.selection && that.currentValue !== query) {
                    (options.onInvalidateSelection || $.noop).call(that.element);
                }
            }
        },
        abortAjax: function () {
            var that = this;
            if (that.currentRequest) {
                that.currentRequest.abort();
                that.currentRequest = null;
            }
        },
        setOptions: function (suppliedOptions) {
            var that = this,
                $suggestionsContainer = that.getSuggestionsContainer(),

                options = $.extend({}, that.options, suppliedOptions);

            that.isLocal = Array.isArray(options.lookup);

            if (that.isLocal) {
                options.lookup = that.verifySuggestionsFormat(options.lookup);
            }

            $suggestionsContainer.css({
                'max-height': !that.canShowDetailsPanel() ? options.maxHeight + 'px' : 'none',
                'z-index': options.zIndex
            });

            // Add classes
            if (that.canShowDetailsPanel()) {
                var $detailsContainer = that.getDetailsContainer();

                $detailsContainer.css({
                    'z-index': (options.zIndex - 1)
                });
            }

            options.onSearchComplete = function () {
                var $searchForm = that.getFormWrapper();
                $searchForm.removeClass('dgwt-wcas-processing');
                that.preloader('hide', 'form', 'dgwt-wcas-inner-preloader');
                that.showCloseButton();
            };

            this.options = options;
        },
        clearCache: function () {
            this.cachedResponse = {};
            this.cachedDetails = {};
            this.cachedPrices = {};
            this.badQueries = [];
        },
        clear: function (cache) {
            if (cache) {
                this.clearCache();
            }
            this.currentValue = '';
            this.suggestions = [];
        },
        close: function (focus) {
            var that = this,
                $el = that.el.closest('.' + that.options.searchFormClass).find('.' + that.options.searchInputClass),
                $wrapp = that.getFormWrapper();

            that.hide();
            that.clear(false);
            that.hideCloseButton();
            $el.val('');
            $wrapp.removeClass(that.classes.inputFilled);

            if (focus) {
                $el.focus();
            }
        },
        fixPositionSuggestions: function () {
            var that = this,
                $suggestions = that.getSuggestionsContainer(),
                $formEl = that.getForm(),
                $input = that.el,
                formData = that.getElementInfo($formEl),
                inputData = that.getElementInfo($input),
                offset = {
                    top: inputData.top + inputData.height,
                    left: formData.left
                };

            // Set different vertical coordinates when the search bar is in the fixed position
            if (that.ancestorHasPositionFixed($formEl)) {
                offset.top = inputData.topViewPort + inputData.height;
                $suggestions.addClass(that.classes.fixed);
            } else {
                $suggestions.removeClass(that.classes.fixed);
            }

            that.getSuggestionsContainer().css(offset);
        },
        fixPositionDetailsPanel: function () {
            var that = this,
                $searchBar = that.getFormWrapper(),
                $suggestions = that.getSuggestionsContainer(),
                $detailsPanel = that.getDetailsContainer(),
                $formEl = that.getForm(),
                $input = that.el,
                formData = that.getElementInfo($formEl),
                inputData = that.getElementInfo($input),
                offset = {
                    top: inputData.top + inputData.height,
                    left: formData.left + $suggestions.outerWidth(false)
                };

            // Set different vertical coordinate when the search bar is in the fixed position
            if(that.ancestorHasPositionFixed($searchBar)) {
                offset.top = inputData.topViewPort + inputData.height;
                $detailsPanel.addClass(that.classes.fixed);
            }else{
                $detailsPanel.removeClass(that.classes.fixed)
            }

            // Stick the details panel to the right side of the suggestion wrapper and to the bottom border of the search form
            $detailsPanel.css(offset);

            $('body').removeClass('dgwt-wcas-full-width dgwt-wcas-details-outside dgwt-wcas-details-right dgwt-wcas-details-left dgwt-wcas-details-notfit');

            // Details Panel Mode 1: Both suggestions wrapper and details panel wrapper have the same width as the search bar
            if ($searchBar.outerWidth() >= that.options.dpusbBreakpoint) {
                $('body').addClass('dgwt-wcas-full-width');

                if (that.options.isRtl === true) {
                    offset.left = formData.left + $detailsPanel.outerWidth(false);
                    $suggestions.css('left', offset.left);
                    $detailsPanel.css('left', formData.left);
                }

                return;
            }

            // Details Panel Mode 2: The suggestions' wrapper has the same width as the search bar.
            // Details panel clings to the left or right side of the suggestion wrapper.
            var windowWidth = $(window).width(),
                cDWidth = $detailsPanel.outerWidth(),
                cOffset = $detailsPanel.offset();

            $('body').addClass('dgwt-wcas-details-outside dgwt-wcas-details-right');

            // Is the details panel fits the space of the right side?
            // Not? Try to move to the left side
            if (windowWidth < (cOffset.left + cDWidth)) {
                $('body').removeClass('dgwt-wcas-details-right');
                $('body').addClass('dgwt-wcas-details-left');
                offset.left = $suggestions.offset().left - $detailsPanel.outerWidth(false);
                $detailsPanel.css('left', offset.left);
                cOffset = $detailsPanel.offset();
            }

            // Is the details' panel fits the space of the left side?
            // Not? Try to hide it by adding class "dgwt-wcas-details-notfit"
            if (cOffset.left < 1) {
                $('body').removeClass('dgwt-wcas-details-left dgwt-wcas-details-right');
                $('body').addClass('dgwt-wcas-details-notfit');
            }
        },
        fixHeight: function () {
            var that = this;

            if (!that.canShowDetailsPanel()) {
                return false;
            }

            var $suggestionsWrapp = that.getSuggestionsContainer(),
                $detailsWrapp = that.getDetailsContainer();

            $suggestionsWrapp.css('height', 'auto');
            $detailsWrapp.css('height', 'auto');

            var sH = $suggestionsWrapp.outerHeight(false),
                dH = $detailsWrapp.outerHeight(false),
                minHeight = 340;

            $suggestionsWrapp.find('.dgwt-wcas-suggestion:last-child').removeClass('dgwt-wcas-suggestion-no-border-bottom');

            if (sH <= minHeight && dH <= minHeight) {
                return false;
            }

            $suggestionsWrapp.find('.dgwt-wcas-suggestion:last-child').addClass('dgwt-wcas-suggestion-no-border-bottom');

            if (dH < sH) {
                $detailsWrapp.css('height', (sH) + 'px');
            }

            if (sH < dH) {
                $suggestionsWrapp.css('height', dH + 'px');
            }

            return false;
        },
        automaticAlignment: function () {
            var that = this,
                $input = that.getFormWrapper().find('.dgwt-wcas-search-input'),
                $suggestionsContainer = that.getSuggestionsContainer(),
                $detailsWrapp = that.getDetailsContainer();

            if (that.autoAligmentprocess != null) {
                return;
            }

            var markers = [$input.width(), $suggestionsContainer.height()];
            if (that.canShowDetailsPanel()) {
                markers[2] = $detailsWrapp.height();
            }

            that.autoAligmentprocess = setInterval(function () {

                var newMarkers = [$input.width(), $suggestionsContainer.height()];
                if (that.canShowDetailsPanel()) {
                    newMarkers[2] = $detailsWrapp.height();
                }

                for (var i = 0; i < markers.length; i++) {

                    if (markers[i] != newMarkers[i]) {

                        that.fixHeight();
                        that.fixPosition();
                        markers = newMarkers;
                        break;
                    }
                }


                if (that.canShowDetailsPanel()) {

                    var innerDetailsHeight = $detailsWrapp.find('.dgwt-wcas-details-inner').height();


                    if ((innerDetailsHeight - $detailsWrapp.height()) > 2) {
                        that.fixHeight();
                    }
                }

            }, 10);

        },
        getElementInfo: function ($el) {
            var data = {},
                viewPort,
                offset;

            viewPort = $el[0].getBoundingClientRect();
            offset = $el.offset();

            data.left = offset.left;
            data.top = offset.top;
            data.width = $el.outerWidth(false);
            data.height = $el.outerHeight(false);
            data.right = data.left + data.width;
            data.bottom = data.top + data.height;
            data.topViewPort = viewPort.top;
            data.bottomViewPort = viewPort.top + data.height;

            return data;
        },
        getFormWrapper: function () {
            var that = this;
            return that.el.closest('.' + that.options.searchFormClass);
        },
        getForm: function () {
            var that = this;
            return that.el.closest('.' + that.options.formClass);
        },
        getSuggestionsContainer: function () {
            var that = this;
            return $('.' + that.options.containerClass);
        },
        getDetailsContainer: function () {
            var that = this;
            return $('.' + that.options.containerDetailsClass);
        },
        scrollDownSuggestions: function () {
            var that = this,
                $el = that.getSuggestionsContainer();
            $el[0].scrollTop = $el[0].scrollHeight;
        },
        isCursorAtEnd: function () {
            var that = this,
                valLength = that.el.val().length,
                selectionStart = that.element.selectionStart,
                range;

            if (typeof selectionStart === 'number') {
                return selectionStart === valLength;
            }
            if (document.selection) {
                range = document.selection.createRange();
                range.moveStart('character', -valLength);
                return valLength === range.text.length;
            }
            return true;
        },
        onKeyPress: function (e) {
            var that = this,
                $wrapp = that.getFormWrapper();

            that.addActiveClassIfMissing();

            // If suggestions are hidden and user presses arrow down, display suggestions:
            if (!that.visible && e.keyCode === keys.DOWN && that.currentValue) {
                that.suggest();
                return;
            }

            if (!that.visible) {
                // Hide the search icon mode on ESC when there are no suggestions
                if (e.keyCode === keys.ESC && $wrapp.hasClass('dgwt-wcas-layout-icon-open')) {
                    that.hideIconModeSearch();
                }

                // Hide the darkened overlay on ESC when there are no suggestions
                if (e.keyCode === keys.ESC && that.isMountedOverlayDarkened()) {
                    that.disableOverlayDarkened();
                    that.el.blur();
                }

                return;
            }

            // Open selected suggestion in new tab
            if ((e.ctrlKey || e.metaKey) && e.keyCode === keys.RETURN) {
                if (that.selectedIndex > -1) {
                    that.openInNewTab(that.selectedIndex);
                }
                return;
            }

            switch (e.keyCode) {
                case keys.ESC:
                    that.close();
                    break;
                case keys.RIGHT:
                    if (that.hint && that.options.onHint && that.isCursorAtEnd()) {
                        that.selectHint();
                        break;
                    }
                    return;
                case keys.TAB:
                    break;
                case keys.RETURN:

                    if (that.selectedIndex === -1) {
                        if (that.options.disableSubmit) {
                            return false;
                        }
                        that.hide();
                        return;
                    }
                    that.actionTriggerSource = 'enter';
                    that.select(that.selectedIndex);
                    break;
                case keys.UP:
                    that.moveUp();
                    break;
                case keys.DOWN:
                    that.moveDown();
                    break;
                default:
                    return;
            }

            // Cancel event if function did not return:
            e.stopImmediatePropagation();
            e.preventDefault();
        },
        onKeyUp: function (e) {
            var that = this;

            switch (e.keyCode) {
                case keys.UP:
                case keys.DOWN:
                    return;
            }

            clearTimeout(that.onChangeTimeout);

            if (that.currentValue !== that.el.val()) {
                that.findBestHint();
                if (that.options.deferRequestBy > 0) {
                    // Defer lookup in case when value changes very quickly:
                    that.onChangeTimeout = setTimeout(function () {
                        that.onValueChange();
                    }, that.options.deferRequestBy);
                } else {
                    that.onValueChange();
                }
            }
        },
        onValueChange: function () {
            if (this.ignoreValueChange) {
                this.ignoreValueChange = false;
                return;
            }

            var that = this,
                options = that.options,
                value = that.el.val(),
                query = that.getQuery(value),
                $wrapp = that.getFormWrapper();

            if (that.selection && that.currentValue !== query) {
                that.selection = null;
                (options.onInvalidateSelection || $.noop).call(that.element);
            }

            clearTimeout(that.onChangeTimeout);
            that.currentValue = value;
            that.selectedIndex = -1;

            // Check existing suggestion for the match before proceeding:
            if (options.triggerSelectOnValidInput && that.isExactMatch(query)) {
                that.select(0);
                return;
            }

            // Mark as filled
            if (query.length > 0) {
                if (!$wrapp.hasClass(that.classes.inputFilled)) {
                    $wrapp.addClass(that.classes.inputFilled);
                }
            } else {
                $wrapp.removeClass(that.classes.inputFilled);
            }

            if (query.length < options.minChars) {
                that.hideCloseButton();
                that.hide();
            } else {
                that.getSuggestions(query);
            }
        },
        isExactMatch: function (query) {
            var suggestions = this.suggestions;

            return (suggestions.length === 1 && suggestions[0].value.toLowerCase() === query.toLowerCase());
        },
        canShowDetailsPanel: function () {
            var that = this,
                show = that.options.showDetailsPanel;

            if ($(window).width() < 768 || ('ontouchend' in document)) {
                show = false;
            }
            return show;
        },
        isBreakpointReached: function (context) {
            var that = this,
                breakpoint = 0;

            switch (context) {
                case 'search-layout':
                    breakpoint = that.options.layoutBreakpoint;
                    if (that.isSetParam('layout_breakpoint')) {
                        breakpoint = Number.parseInt(that.getParam('layout_breakpoint'));
                    }
                    break;
                case 'mobile-overlay':
                    breakpoint = that.options.mobileOverlayBreakpoint;
                    if (that.isSetParam('mobile_overlay_breakpoint')) {
                        breakpoint = Number.parseInt(that.getParam('mobile_overlay_breakpoint'));
                    }
                    break;
            }

            return $(window).width() <= breakpoint;
        },
        getQuery: function (value) {
            var delimiter = this.options.delimiter,
                parts;

            if (!delimiter) {
                return value.trim();
            }
            parts = value.split(delimiter);
            return $.trim(parts[parts.length - 1]);
        },
        getSuggestionsLocal: function (query) {
            var that = this,
                options = that.options,
                queryLowerCase = query.toLowerCase(),
                filter = options.lookupFilter,
                limit = parseInt(options.lookupLimit, 10),
                data;

            data = {
                suggestions: $.grep(options.lookup, function (suggestion) {
                    return filter(suggestion, query, queryLowerCase);
                })
            };

            if (limit && data.suggestions.length > limit) {
                data.suggestions = data.suggestions.slice(0, limit);
            }

            return data;
        },
        getSuggestions: function (q) {
            var response,
                that = this,
                options = that.options,
                serviceUrl = options.serviceUrl,
                searchForm = that.getFormWrapper(),
                params,
                cacheKey,
                ajaxSettings,
                iconSearchActive = that.isActiveIconModeSearch();

            options.params[options.paramName] = q;

            if (typeof dgwt_wcas.current_lang != 'undefined') {
                options.params['l'] = dgwt_wcas.current_lang;
            }

            that.preloader('show', 'form', 'dgwt-wcas-inner-preloader');
            searchForm.addClass('dgwt-wcas-processing');

            if (options.onSearchStart.call(that.element, options.params) === false) {
                return;
            }

            params = options.ignoreParams ? null : options.params;

            if ($.isFunction(options.lookup)) {
                options.lookup(q, function (data) {
                    that.suggestions = data.suggestions;
                    that.suggest();
                    that.selectFirstSuggestion(data.suggestions);
                    options.onSearchComplete.call(that.element, q, data.suggestions);
                });
                return;
            }

            if (that.isLocal) {
                response = that.getSuggestionsLocal(q);
            } else {
                if ($.isFunction(serviceUrl)) {
                    serviceUrl = serviceUrl.call(that.element, q);
                }
                cacheKey = serviceUrl + '?' + $.param(params || {});
                response = that.cachedResponse[cacheKey];
            }

            if (response && Array.isArray(response.suggestions)) {
                that.suggestions = response.suggestions;
                that.suggest();
                that.selectFirstSuggestion(response.suggestions);
                options.onSearchComplete.call(that.element, q, response.suggestions);
            } else if (!that.isBadQuery(q)) {
                that.abortAjax();

                ajaxSettings = {
                    url: serviceUrl,
                    data: params,
                    type: options.type,
                    dataType: options.dataType
                };

                $.extend(ajaxSettings, options.ajaxSettings);

                ajaxDebounceState.object = that;
                ajaxDebounceState.ajaxSettings = ajaxSettings;

                utils.debounce(function () {

                    var that = ajaxDebounceState.object,
                        ajaxSettings = ajaxDebounceState.ajaxSettings;


                    that.currentRequest = $.ajax(ajaxSettings).done(function (data) {

                        // Interrupt if the icon mode was closed in the meantime
                        if (iconSearchActive && !that.isActiveIconModeSearch()) {
                            return;
                        }

                        var result;
                        that.currentRequest = null;
                        result = that.options.transformResult(data, q);

                        if (typeof result.suggestions !== 'undefined') {
                            that.processResponse(result, q, cacheKey);
                            that.selectFirstSuggestion(result.suggestions);

                            if (
                                result.suggestions.length === 1
                                && typeof result.suggestions[0].type !== 'undefined'
                                && result.suggestions[0].type === 'no-results'
                            ) {
                                that.gaEvent(q, 'Autocomplete Search without results');
                            } else {
                                that.gaEvent(q, 'Autocomplete Search with results');
                            }

                        }

                        that.fixPosition();

                        that.options.onSearchComplete.call(that.element, q, result.suggestions);

                        that.updatePrices();

                    }).fail(function (jqXHR, textStatus, errorThrown) {
                        that.options.onSearchError.call(that.element, q, jqXHR, textStatus, errorThrown);
                    });


                }, options.debounceWaitMs);


            } else {
                options.onSearchComplete.call(that.element, q, []);
            }

        },
        getDetails: function (suggestion) {
            var that = this;

            // Disable details panel
            if (!that.canShowDetailsPanel()) {
                return false;
            }

            // Brake if there are no suggestions
            if (suggestion == null || typeof suggestion.type == 'undefined') {
                return;
            }

            // Disable on more product suggestion
            if (typeof suggestion.type == 'string' && suggestion.type === 'more_products') {
                return;
            }

            that.fixHeight();

            var $containerDetails = that.getDetailsContainer(),
                currentObjectID = that.prepareSuggestionObjectID(suggestion),
                result;

            // Check cache
            result = that.cachedDetails[currentObjectID];

            if (result != null) {

                // Load response from cache
                that.detailsPanelSetScene(currentObjectID);
                that.fixHeight();
                that.fixPosition();

            } else {

                var data = {
                    action: dgwt_wcas.action_result_details,
                    items: []
                };

                $.each(that.suggestions, function (i, suggestion) {

                    if (
                        typeof suggestion.type != 'undefined'
                        && suggestion.type != 'more_products'
                        && suggestion.type != 'headline'
                    ) {
                        var itemData = {
                            objectID: that.prepareSuggestionObjectID(suggestion),
                            value: suggestion.value != null ? suggestion.value : ''
                        };
                        data.items.push(itemData);
                    }
                });


                that.detailsPanelClearScene();
                that.preloader('show', 'details', '');

                // Prevent duplicate ajax requests
                if ($.inArray(currentObjectID, that.detailsRequestsSent) != -1) {
                    return;
                } else {
                    that.detailsRequestsSent.push(currentObjectID);
                }

                $.ajax({
                    data: data,
                    type: 'post',
                    url: dgwt_wcas.ajax_details_endpoint,
                    success: function (response) {

                        var result = typeof response === 'string' ? JSON.parse(response) : response;

                        if (typeof result.items != 'undefined') {
                            for (var i = 0; i < result.items.length; i++) {
                                var cacheKey = result.items[i]['objectID'];
                                that.cachedDetails[cacheKey] = {html: result.items[i]['html']}
                                that.detailsPanelAddToScene(cacheKey);

                                if (typeof result.items[i]['price'] != 'undefined' && result.items[i]['price'].length > 0) {
                                    that.cachedPrices[cacheKey] = result.items[i]['price'];
                                }
                            }
                        }

                        that.preloader('hide', 'details', '');

                        var currentObjectID = that.prepareSuggestionObjectID(that.suggestions[that.selectedIndex]);

                        if (that.cachedDetails[currentObjectID] != null) {
                            that.detailsPanelSetScene(currentObjectID);
                        } else {
                            // @TODO Maybe display some error or placeholder
                            that.detailsPanelClearScene();
                        }
                        that.fixPosition();
                        that.fixHeight();

                        that.updatePrices(true);
                    },
                    error: function (jqXHR, exception) {

                        that.preloader('hide', 'details', '');

                        that.detailsPanelClearScene();
                        that.fixPosition();
                        that.fixHeight();
                    },
                });
            }

            $(document).trigger('dgwtWcasDetailsPanelLoaded', that);
        },
        updatePrices: function (noAjax) {
            var that = this,
                i, j,
                productsToLoad = [];

            if (!(that.options.showPrice && that.options.dynamicPrices)) {
                return;
            }

            if (that.suggestions.length == 0) {
                return;
            }

            for (i = 0; i < that.suggestions.length; i++) {

                if (
                    typeof that.suggestions[i].type != 'undefined'
                    && (that.suggestions[i].type == 'product' || that.suggestions[i].type == 'product_variation')
                ) {
                    var key = 'product__' + that.suggestions[i].post_id;

                    if (typeof that.cachedPrices[key] != 'undefined') {

                        that.updatePrice(i, that.cachedPrices[key]);

                    } else {

                        that.applyPreloaderForPrice(i);

                        productsToLoad.push(that.suggestions[i].post_id);
                    }
                }

            }


            if (!noAjax && productsToLoad.length > 0) {

                var data = {
                    action: typeof dgwt_wcas.action_get_prices == 'undefined' ? 'dgwt_wcas_get_prices' : dgwt_wcas.action_get_prices,
                    items: productsToLoad
                };

                $.ajax({
                    data: data,
                    type: 'post',
                    url: dgwt_wcas.ajax_prices_endpoint,
                    success: function (response) {

                        if (typeof response.success != 'undefined' && response.success && response.data.length > 0) {
                            for (i = 0; i < response.data.length; i++) {

                                var postID = response.data[i].id,
                                    price = response.data[i].price;

                                if (that.suggestions.length > 0) {
                                    for (j = 0; j < that.suggestions.length; j++) {
                                        if (
                                            typeof that.suggestions[j].type != 'undefined'
                                            && (that.suggestions[j].type == 'product' || that.suggestions[j].type == 'product_variation')
                                            && that.suggestions[j].post_id == postID
                                        ) {

                                            var key = 'product__' + postID;

                                            that.cachedPrices[key] = price;

                                            that.updatePrice(j, price);

                                        }
                                    }
                                }
                            }
                        }

                    },
                    error: function (jqXHR, exception) {

                    },
                });

            }

        },
        updatePrice: function (index, price) {
            var that = this;

            if (typeof that.suggestions[index] != 'undefined') {

                that.suggestions[index].price = price;

                var $price = $('.dgwt-wcas-suggestions-wrapp').find('[data-index="' + index + '"] .dgwt-wcas-sp');

                if ($price.length) {
                    $price.html(price);
                }
            }

        },
        applyCustomParams: function (params) {
            var that = this;

            // Custom params (global)
            if (typeof dgwt_wcas.custom_params == 'object') {
                var cp = dgwt_wcas.custom_params;
                for (var property in cp) {
                    params[property] = cp[property];
                }
            }

            // Custom params (local)
            var inputCustomParams = that.el.data('custom-params');

            if (typeof inputCustomParams === 'object') {
                for (var property in inputCustomParams) {
                    params[property] = inputCustomParams[property];
                }
            }

            return params;
        },
        isSetParam: function (param) {
            var that = this;
            return typeof that.options.params[param] != 'undefined';
        },
        getParam: function (param) {
            var that = this;
            return that.isSetParam(param) ? that.options.params[param] : '';
        },
        applyPreloaderForPrice: function (index) {
            var that = this;

            if (typeof that.suggestions[index] != 'undefined') {
                var $price = $('.dgwt-wcas-suggestions-wrapp').find('[data-index="' + index + '"] .dgwt-wcas-sp');
                if ($price.length) {
                    $price.html('<div class="dgwt-wcas-preloader-price"><div class="dgwt-wcas-preloader-price-inner"> <div></div><div></div><div></div></div></div>');
                }
            }
        },
        prepareSuggestionObjectID: function (suggestion) {
            var objectID = '';

            if (typeof suggestion != 'undefined' && typeof suggestion.type != 'undefined') {

                //Products and post types
                if (suggestion.post_id != null) {
                    objectID = suggestion.type + '__' + suggestion.post_id;

                    if (suggestion.type === 'product_variation') {
                        objectID += '__' + suggestion.variation_id;
                    }

                    // Post types
                    if (typeof suggestion.post_type != 'undefined') {
                        objectID = suggestion.type + '__' + suggestion.post_id + '__' + suggestion.post_type;
                    }

                }

                //Terms
                if (suggestion.term_id != null && suggestion.taxonomy != null) {
                    objectID = suggestion.type + '__' + suggestion.term_id + '__' + suggestion.taxonomy;
                }
            }

            return objectID;
        },
        detailsPanelSetScene: function (objectID) {
            var that = this,
                $containerDetails = that.getDetailsContainer(),
                objectHash = utils.hashCode(objectID),
                $el = $containerDetails.find('.dgwt-wcas-details-inner[data-object="' + objectHash + '"]');

            if ($el.length) {
                that.preloader('hide', 'details', '');
                that.detailsPanelClearScene();
                $el.addClass('dgwt-wcas-details-inner-active');
            }
        },
        detailsPanelAddToScene: function (objectID) {
            var that = this,
                $containerDetails = that.getDetailsContainer(),
                object = that.cachedDetails[objectID],
                objectHash = utils.hashCode(objectID),
                html = '';

            if (typeof object != 'undefined' && typeof object.html == 'string') {
                html = object.html.replace('<div ', '<div data-object="' + objectHash + '" ');
            }

            if ($containerDetails.find('.dgwt-wcas-details-inner[data-object="' + objectHash + '"]').length == 0) {
                $containerDetails.append(html);
            }
        },
        detailsPanelClearScene: function () {
            var that = this,
                $containerDetails = that.getDetailsContainer(),
                $views = $containerDetails.find('.dgwt-wcas-details-inner');

            if ($views.length) {
                $views.removeClass('dgwt-wcas-details-inner-active');
            }
        },
        selectFirstSuggestion: function (suggestions) {
            var that = this,
                index = 0,
                noResults = false;

            if (!that.canShowDetailsPanel()) {
                return;
            }

            if (suggestions != 'undefined' && suggestions.length > 0) {
                $.each(that.suggestions, function (i, suggestion) {

                    if (
                        typeof suggestion.type != 'undefined'
                        && suggestion.type != 'more_products'
                        && suggestion.type != 'headline'
                        && suggestion.type != 'no-results'
                    ) {
                        index = i;
                        return false;
                    }

                    if (typeof suggestion.type === 'undefined' || suggestion.type === 'no-results') {
                        noResults = true;
                    }

                });
            }

            if (noResults) {
                return;
            }

            that.latestActivateSource = 'system';
            that.getDetails(suggestions[index]);
            that.activate(index);
        },
        isBadQuery: function (q) {
            if (!this.options.preventBadQueries) {
                return false;
            }

            var badQueries = this.badQueries,
                i = badQueries.length;

            while (i--) {
                if (q.indexOf(badQueries[i]) === 0) {
                    return true;
                }
            }

            return false;
        },
        hide: function (clear) {
            var that = this,
                $suggestions = that.getSuggestionsContainer(),
                $detailsPanel = that.getDetailsContainer();

            if ($.isFunction(that.options.onHide) && that.visible) {
                that.options.onHide.call(that.element, container);
            }

            that.visible = false;
            that.selectedIndex = -1;
            clearTimeout(that.onChangeTimeout);
            $suggestions.hide();
            $suggestions.removeClass(that.classes.suggestionsContainerOrientTop);
            $suggestions.removeClass(that.classes.fixed);

            if (that.canShowDetailsPanel()) {
                $detailsPanel.hide();
                $detailsPanel.removeClass(that.classes.fixed);
            }

            $('body').removeClass('dgwt-wcas-open');
            if (!$('html').hasClass('dgwt-wcas-overlay-mobile-on')) {
                $('html').removeClass('dgwt-wcas-open-' + that.getSearchStyle());
            }
            $('body').removeClass('dgwt-wcas-block-scroll');
            $('body').removeClass('dgwt-wcas-is-details');
            $('body').removeClass('dgwt-wcas-full-width');
            $('body').removeClass('dgwt-wcas-nores');
            $('body').removeClass('dgwt-wcas-details-outside');
            $('body').removeClass('dgwt-wcas-details-right');
            $('body').removeClass('dgwt-wcas-details-left');

            if (that.autoAligmentprocess != null) {
                clearInterval(that.autoAligmentprocess);
                that.autoAligmentprocess = null;
            }

            that.isMouseDownOnSearchElements = false;

            if (typeof clear == 'boolean' && clear) {

                that.hideCloseButton();

                that.currentValue = '';
                that.suggestions = [];

            }

            that.signalHint(null);
        },
        positionIconSearchMode: function ($formWrapper) {
            var that = this,
                formLeftValue = -20,
                $form = that.getForm(),
                formWidth = $form.width(),
                windowWidth = $(window).width();

            var iconLeftOffset = $formWrapper[0].getBoundingClientRect().left;

            var iconLeftRatio = (iconLeftOffset + 10) / windowWidth;

            formLeftValue = Math.floor(-1 * (formWidth * iconLeftRatio));

            // Prevent shifting to the left more than the icon position (also positioned from the left)
            formLeftValue = Math.max(formLeftValue, -1 * iconLeftOffset);

            $form.css({'left': formLeftValue + 'px'});

        },
        isActiveIconModeSearch: function () {
            var active = false,
                $openedElements = $('.dgwt-wcas-layout-icon-open');
            if ($openedElements.length > 0) {
                active = true;
            }
            return active;
        },
        hideIconModeSearch: function () {
            var that = this;

            if (that.isActiveIconModeSearch() && !utils.isTextSelected()) {
                $('.dgwt-wcas-layout-icon-open').removeClass('dgwt-wcas-layout-icon-open');
            }
        },
        hideAfterClickOutsideListener: function () {
            var that = this;
            if (!('ontouchend' in document)) {

                $(document).mouseup(function (e) {
                    if (!that.visible) {
                        return;
                    }

                    var outsideForm = !($(e.target).closest('.' + that.options.searchFormClass).length > 0 || $(e.target).hasClass(that.options.searchFormClass)),
                        outsideContainer = !($(e.target).closest('.' + that.options.containerClass).length > 0 || $(e.target).hasClass(that.options.containerClass));

                    if (!that.canShowDetailsPanel()) {

                        if (outsideForm && outsideContainer) {
                            that.hide();
                        }

                    } else {

                        var outsidecontainerDetails = !($(e.target).closest('.' + that.options.containerDetailsClass).length > 0 || $(e.target).hasClass(that.options.containerDetailsClass));

                        if (outsideForm && outsideContainer && outsidecontainerDetails) {
                            that.hide();
                        }

                    }

                });

            }
        },
        suggest: function () {
            if (!this.suggestions.length) {
                this.hide();
                return;
            }

            var that = this,
                options = that.options,
                groupBy = options.groupBy,
                formatResult = options.formatResult,
                value = that.getQuery(that.currentValue),
                className = that.classes.suggestion,
                classSelected = that.classes.selected,
                container = that.getSuggestionsContainer(),
                containerDetails = that.getDetailsContainer(),
                noSuggestionsContainer = $(that.noSuggestionsContainer),
                beforeRender = options.beforeRender,
                html = '',
                category,
                formatGroup = function (suggestion, index) {
                    var currentCategory = suggestion.data[groupBy];

                    if (category === currentCategory) {
                        return '';
                    }

                    category = currentCategory;

                    return '<div class="autocomplete-group"><strong>' + category + '</strong></div>';
                };

            if (options.triggerSelectOnValidInput && that.isExactMatch(value)) {
                that.select(0);
                return;
            }

            $('body').removeClass('dgwt-wcas-nores');

            // Build suggestions inner HTML:
            $.each(that.suggestions, function (i, suggestion) {

                var parent = '',
                    dataAttrs = '',
                    is_img = false,
                    url = typeof suggestion.url == 'string' && suggestion.url.length ? suggestion.url : '#';

                if (groupBy) {
                    html += formatGroup(suggestion, value, i);
                }

                if (typeof suggestion.type == 'undefined' || (suggestion.type != 'product') && suggestion.type != 'product_variation') {

                    var classes = className,
                        innerClass = 'dgwt-wcas-st',
                        prepend = '',
                        append = '',
                        title = '',
                        highlight = true,
                        isImg;

                    if (suggestion.taxonomy === 'product_cat') {
                        classes += ' dgwt-wcas-suggestion-tax dgwt-wcas-suggestion-cat';
                        if (!options.showHeadings) {
                            prepend += '<span class="dgwt-wcas-st--direct-headline">' + dgwt_wcas.labels['tax_' + suggestion.taxonomy] + '</span>';
                        }
                        if (typeof suggestion.breadcrumbs != 'undefined' && suggestion.breadcrumbs) {
                            title = suggestion.breadcrumbs + ' &gt; ' + suggestion.value;
                            append += '<span class="dgwt-wcas-st-breadcrumbs">' + dgwt_wcas.labels.in + ' ' + suggestion.breadcrumbs + '</span>';
                            //@TODO RTL support
                        }

                    } else if (suggestion.taxonomy === 'product_tag') {
                        classes += ' dgwt-wcas-suggestion-tax dgwt-wcas-suggestion-tag';
                        if (!options.showHeadings) {
                            prepend += '<span class="dgwt-wcas-st--direct-headline">' + dgwt_wcas.labels['tax_' + suggestion.taxonomy] + '</span>';
                        }
                    } else if (options.isPremium && suggestion.taxonomy === options.taxonomyBrands) {
                        classes += ' dgwt-wcas-suggestion-tax dgwt-wcas-suggestion-brand';
                        if (!options.showHeadings) {
                            prepend += '<span class="dgwt-wcas-st--direct-headline">' + dgwt_wcas.labels['tax_' + suggestion.taxonomy] + '</span>';
                        }
                    } else if (options.isPremium && suggestion.type === 'taxonomy') {
                        classes += ' dgwt-wcas-suggestion-tax dgwt-wcas-suggestion-tax-' + suggestion.taxonomy;
                        if (!options.showHeadings) {
                            prepend += '<span class="dgwt-wcas-st--direct-headline">' + dgwt_wcas.labels['tax_' + suggestion.taxonomy] + '</span>';
                        }
                    } else if (options.isPremium && suggestion.type === 'vendor') {
                        classes += ' dgwt-wcas-suggestion-vendor dgwt-wcas-suggestion-vendor';
                        if (!options.showHeadings) {
                            prepend += '<span class="dgwt-wcas-st--direct-headline">' + dgwt_wcas.labels.vendor + '</span>';
                        }
                    } else if (options.isPremium && suggestion.type === 'post' && typeof suggestion.post_type !== 'undefined' && suggestion.post_type === 'post') {
                        classes += ' dgwt-wcas-suggestion-pt dgwt-wcas-suggestion-pt-post';
                        if (!options.showHeadings) {
                            prepend += '<span class="dgwt-wcas-st--direct-headline">' + dgwt_wcas.labels.post + '</span>';
                        }
                    } else if (options.isPremium && suggestion.type === 'post' && typeof suggestion.post_type !== 'undefined' && suggestion.post_type === 'page') {
                        classes += ' dgwt-wcas-suggestion-pt dgwt-wcas-suggestion-pt-page';
                        if (!options.showHeadings) {
                            prepend += '<span class="dgwt-wcas-st--direct-headline">' + dgwt_wcas.labels.page + '</span>';
                        }
                    } else if (suggestion.type === 'more_products') {
                        classes += ' js-dgwt-wcas-suggestion-more dgwt-wcas-suggestion-more';
                        innerClass = 'dgwt-wcas-st-more';
                        suggestion.value = dgwt_wcas.labels.show_more + ' (' + suggestion.total + ')';
                        highlight = false;
                    } else if (options.showHeadings && suggestion.type === 'headline') {
                        classes += ' js-dgwt-wcas-suggestion-headline dgwt-wcas-suggestion-headline';
                        if (typeof dgwt_wcas.labels[suggestion.value + '_plu'] != 'undefined') {
                            suggestion.value = dgwt_wcas.labels[suggestion.value + '_plu'];
                        }
                        highlight = false;
                    } else {
                        classes += ' dgwt-wcas-suggestion-nores';
                        suggestion.value = dgwt_wcas.labels.no_results;
                        highlight = false;
                        if (that.canShowDetailsPanel()) {
                            that.detailsPanelClearScene();
                        }
                        $('body').addClass('dgwt-wcas-nores');
                    }

                    // Image
                    if (typeof suggestion.image_src != 'undefined' && suggestion.image_src) {
                        isImg = true;
                    }

                    title = title.length > 0 ? ' title="' + title + '"' : '';

                    html += '<a href="' + url + '" class="' + classes + '" data-index="' + i + '">';

                    if (isImg) {
                        html += '<span class="dgwt-wcas-si"><img src="' + suggestion.image_src + '" /></span>';
                        html += '<div class="dgwt-wcas-content-wrapp">';
                    }

                    html += '<span' + title + ' class="' + innerClass + '">';

                    if (suggestion.type === 'vendor') {
                        html += '<span class="dgwt-wcas-st-title">' + prepend + formatResult(suggestion.value, value, highlight, options) + append + '</span>';

                        // Vendor city
                        if (suggestion.shop_city) {
                            html += '<span class="dgwt-wcas-vendor-city"><span> - </span>' + formatResult(suggestion.shop_city, value, true, options) + '</span>';
                        }

                        // Description
                        if (typeof suggestion.desc != 'undefined' && suggestion.desc) {
                            html += '<span class="dgwt-wcas-sd">' + formatResult(suggestion.desc, value, true, options) + '</span>';
                        }

                    } else {
                        html += prepend + formatResult(suggestion.value, value, highlight, options) + append;
                    }

                    html += '</span>';

                    html += isImg ? '</div>' : '';
                    html += '</a>';
                } else {

                    // Image
                    if (options.showImage === true && typeof suggestion.thumb_html != 'undefined') {
                        is_img = true;
                    }

                    var sugVarClass = suggestion.type === 'product_variation' ? ' dgwt-wcas-suggestion-product-var' : '';
                    // One suggestion HTML
                    dataAttrs += typeof suggestion.post_id != 'undefined' ? 'data-post-id="' + suggestion.post_id + '" ' : '';
                    dataAttrs += typeof suggestion.taxonomy != 'undefined' ? 'data-taxonomy="' + suggestion.taxonomy + '" ' : '';
                    dataAttrs += typeof suggestion.term_id != 'undefined' ? 'data-term-id="' + suggestion.term_id + '" ' : '';
                    html += '<a href="' + url + '" class="' + className + ' dgwt-wcas-suggestion-product' + sugVarClass + '" data-index="' + i + '" ' + dataAttrs + '>';

                    // Image
                    if (is_img) {
                        html += '<span class="dgwt-wcas-si">' + suggestion.thumb_html + '</span>';
                    }


                    html += is_img ? '<div class="dgwt-wcas-content-wrapp">' : '';


                    // Open Title wrapper
                    html += '<div class="dgwt-wcas-st">';

                    // Custom content before title (3rd party)
                    if (typeof suggestion.title_before != 'undefined' && suggestion.title_before) {
                        html += suggestion.title_before;
                    }

                    // Title
                    html += '<span class="dgwt-wcas-st-title">' + formatResult(suggestion.value, value, true, options) + parent + '</span>';

                    // Custom content after title (3rd party)
                    if (typeof suggestion.title_after != 'undefined' && suggestion.title_after) {
                        html += suggestion.title_after;
                    }

                    // SKU
                    if (options.showSKU === true && typeof suggestion.sku != 'undefined' && suggestion.sku.length > 0) {
                        html += '<span class="dgwt-wcas-sku">(' + dgwt_wcas.labels.sku_label + ' ' + formatResult(suggestion.sku, value, true, options) + ')</span>';
                    }

                    // Description
                    if (options.showDescription === true && typeof suggestion.desc != 'undefined' && suggestion.desc) {
                        html += '<span class="dgwt-wcas-sd">' + formatResult(suggestion.desc, value, true, options) + '</span>';
                    }

                    // Vendor
                    if (options.showProductVendor === true && typeof suggestion.vendor != 'undefined' && suggestion.vendor) {
                        var vendorBody = '<span class="dgwt-wcas-product-vendor"><span class="dgwt-wcas-product-vendor-label">' + dgwt_wcas.labels.vendor_sold_by + ' </span>' + suggestion.vendor + '</span>'

                        if (typeof suggestion.vendor_url != 'undefined' && suggestion.vendor_url) {
                            // Since version v1.12.0 suggestions tag was changed from <div> to <a> and vendor links are no longer supported.
                            html += '<span class="dgwt-wcas-product-vendor-link" data-url="' + suggestion.vendor_url + '">' + vendorBody + '</span>';
                        } else {
                            html += vendorBody;
                        }

                    }

                    // Custom content after description (3rd party)
                    if (typeof suggestion.content_after != 'undefined' && suggestion.content_after) {
                        html += suggestion.content_after;
                    }

                    // Close title wrapper
                    html += '</div>';


                    var showPrice = options.showPrice === true && typeof suggestion.price != 'undefined',
                        showMetaBefore = typeof suggestion.meta_before != 'undefined',
                        showMetaAfter = typeof suggestion.meta_after != 'undefined',
                        showMeta = showPrice || showMetaBefore || showMetaAfter;
                    // @todo show sale and featured badges

                    // Meta
                    html += showMeta ? '<div class="dgwt-wcas-meta">' : '';

                    // Custom content before meta (3rd party)
                    if (showMetaBefore) {
                        html += suggestion.meta_before;
                    }

                    // Price
                    if (showPrice) {
                        html += '<span class="dgwt-wcas-sp">' + suggestion.price + '</span>';
                    }

                    // Custom content after meta (3rd party)
                    if (showMetaAfter) {
                        html += suggestion.meta_after;
                    }

                    // Close Meta
                    html += showMeta ? '</div>' : '';

                    html += is_img ? '</div>' : '';
                    html += '</a>';

                }
            });

            this.adjustContainerWidth();

            noSuggestionsContainer.detach();
            container.html(html);

            if ($.isFunction(beforeRender)) {
                beforeRender.call(that.element, container, that.suggestions);
            }

            container.show();

            // Add class on show
            $('body').addClass('dgwt-wcas-open');
            $('html').addClass('dgwt-wcas-open-' + that.getSearchStyle());

            // Reset the latest mousedown position
            that.isMouseDownOnSearchElements = false;

            that.automaticAlignment();

            if (that.canShowDetailsPanel()) {
                $('body').addClass('dgwt-wcas-is-details');
                containerDetails.show();
                that.fixHeight();
            }

            // Select first value by default:
            if (options.autoSelectFirst) {
                that.selectedIndex = 0;
                container.scrollTop(0);
                container.children('.' + className).first().addClass(classSelected);
            }

            that.visible = true;
            that.fixPosition();

            that.findBestHint();
        },
        getSearchStyle() {
            var that = this,
                $searchWrapp = that.getFormWrapper(),
                style = 'solaris'; //Default style

            $($searchWrapp.attr('class').split(/\s+/)).each(function (index) {
                if (/dgwt-wcas-style-/i.test(this)) {
                    style = this.replace(/dgwt-wcas-style-/i, '');
                }
            });

            return style;
        },
        adjustContainerWidth: function () {
            var that = this,
                $searchBar = that.getFormWrapper(),
                $suggestions = that.getSuggestionsContainer(),
                $detailsPanel = that.getDetailsContainer(),
                $baseElement = that.getForm(),
                baseWidth = $baseElement.outerWidth();

            if (!$searchBar.length) {
                return;
            }

            // Mode 1 - suggestions wrapper width is the same as search bar width
            $suggestions.css('width', baseWidth + 'px');

            // Mode 2 - keep the suggestions wrapper and the details panel together under the search bar
            if (that.canShowDetailsPanel() && baseWidth >= that.options.dpusbBreakpoint) {
                var measurementError = 0;

                // Width 50:50
                $suggestions.css('width', baseWidth / 2);
                $detailsPanel.css('width', baseWidth / 2);

                // Fix browsers subtleties such as calculating sizes as float numbers.
                measurementError = baseWidth - ($suggestions.outerWidth() + $detailsPanel.outerWidth());
                if (measurementError != 0) {
                    $detailsPanel.css('width', $detailsPanel.outerWidth() + measurementError);
                }
            }
        },
        positionPreloaderAndMic: function ($formWrapper) {
            var that = this;

            var $submit = typeof $formWrapper == 'object' ? $formWrapper.find('.dgwt-wcas-search-submit') : $('.dgwt-wcas-search-submit');

            if ($submit.length > 0) {
                $submit.each(function () {
                    var $preloader = $(this).closest('.dgwt-wcas-search-wrapp').find('.dgwt-wcas-preloader');
                    var $isVoiceSearchSupported = $(this).closest('.dgwt-wcas-search-wrapp').hasClass(that.options.voiceSearchSupportedClass);
                    var $voiceSearch = $(this).closest('.dgwt-wcas-search-wrapp').find('.' + that.options.voiceSearchClass);

                    if ($isVoiceSearchSupported) {
                        if (dgwt_wcas.is_rtl == 1) {
                            $voiceSearch.css('left', (6 + $(this).outerWidth()) + 'px');
                            $preloader.css('left', (46 + $(this).outerWidth()) + 'px');
                        } else {
                            $voiceSearch.css('right', $(this).outerWidth() + 'px');
                            $preloader.css('right', 40 + $(this).outerWidth() + 'px');
                        }
                    } else {
                        if (dgwt_wcas.is_rtl == 1) {
                            $preloader.css('left', (6 + $(this).outerWidth()) + 'px');
                        } else {
                            $preloader.css('right', $(this).outerWidth() + 'px');
                        }
                    }
                });
            }

        },
        findBestHint: function () {
            var that = this,
                value = that.el.val().toLowerCase(),
                bestMatch = null;

            if (!value) {
                return;
            }

            $.each(that.suggestions, function (i, suggestion) {
                var foundMatch = suggestion.value.toLowerCase().indexOf(value) === 0;
                if (foundMatch) {
                    bestMatch = suggestion;
                }
                return !foundMatch;
            });

            that.signalHint(bestMatch);
        },
        signalHint: function (suggestion) {
            var hintValue = '',
                that = this;
            if (suggestion) {
                hintValue = that.currentValue + suggestion.value.substr(that.currentValue.length);
            }
            if (that.hintValue !== hintValue) {
                that.hintValue = hintValue;
                that.hint = suggestion;
                (this.options.onHint || $.noop)(hintValue);
            }
        },
        /*
         * Manages preloader
         *
         * @param action (show or hide)
         * @param container (parent selector)
         * @param cssClass
         */
        preloader: function (action, place, cssClass) {
            var that = this,
                html,
                $container,
                defaultClass = 'dgwt-wcas-preloader-wrapp',
                cssClasses = cssClass == null ? defaultClass : defaultClass + ' ' + cssClass;

            if (place === 'form') {

                // Return early if the preloader is disable via the settings for a search bar
                if (dgwt_wcas.show_preloader != 1) {
                    return;
                }

                $container = that.getFormWrapper().find('.dgwt-wcas-preloader');
            } else if (place === 'details') {
                $container = that.getDetailsContainer();
            }

            if ($container.length == 0) {
                return;
            }

            // Handle preloader for a search bar
            if (place === 'form') {
                if (action === 'hide') {
                    $container.removeClass(cssClass);
                    $container.html('');
                } else {
                    $container.addClass(cssClass);
                    if (typeof dgwt_wcas.preloader_icon == 'string') {
                        $container.html(dgwt_wcas.preloader_icon);
                    }
                }
                return;
            }

            // Handle preloader for a details panel
            var $preloader = $container.find('.' + defaultClass);

            // Action hide
            if (action === 'hide') {
                if ($preloader.length) {
                    $preloader.remove();
                }
                return
            }

            // Action show
            if (action === 'show') {
                var rtlSuffix = that.options.isRtl ? '-rtl' : '';
                html = '<div class="' + cssClasses + '"><img class="dgwt-wcas-placeholder-preloader" src="' + dgwt_wcas.img_url + 'placeholder' + rtlSuffix + '.png" /></div>';
                that.detailsPanelClearScene();
                if ($preloader.length) {
                    $preloader.remove();
                }
                $container.prepend(html);
            }
        },
        verifySuggestionsFormat: function (suggestions) {

            // If suggestions is string array, convert them to supported format:
            if (suggestions.length && typeof suggestions[0] === 'string') {
                return $.map(suggestions, function (value) {
                    return {value: value, data: null};
                });
            }

            return suggestions;
        },
        processResponse: function (result, originalQuery, cacheKey) {
            var that = this,
                options = that.options;

            result.suggestions = that.verifySuggestionsFormat(result.suggestions);

            // Cache results if cache is not disabled:
            if (!options.noCache) {
                that.cachedResponse[cacheKey] = result;
                if (options.preventBadQueries && !result.suggestions.length) {
                    that.badQueries.push(originalQuery);
                }
            }

            // Return if originalQuery is not matching current query:
            if (originalQuery !== that.getQuery(that.currentValue)) {
                return;
            }

            that.suggestions = result.suggestions;
            that.suggest();
        },
        activate: function (index) {
            var that = this,
                activeItem,
                selected = that.classes.selected,
                container = that.getSuggestionsContainer(),
                children = container.find('.' + that.classes.suggestion);

            container.find('.' + selected).removeClass(selected);

            that.selectedIndex = index;

            if (that.selectedIndex !== -1 && children.length > that.selectedIndex) {
                activeItem = children.get(that.selectedIndex);
                $(activeItem).addClass(selected);
                return activeItem;
            }

            return null;
        },
        selectHint: function () {
            var that = this,
                i = $.inArray(that.hint, that.suggestions);

            that.select(i);
        },
        select: function (i) {
            var that = this;

            if (that.options.disableHits) {
                return;
            }

            that.closeOverlayMobile();
            that.hide();
            that.onSelect(i);
        },
        moveUp: function () {
            var that = this;

            if (that.selectedIndex === -1) {
                return;
            }

            that.latestActivateSource = 'key';

            if (that.selectedIndex === 0) {
                that.getSuggestionsContainer().children('.' + that.classes.suggestion).first().removeClass(that.classes.selected);
                that.selectedIndex = -1;
                that.ignoreValueChange = false;
                that.el.val(that.currentValue);
                that.findBestHint();
                return;
            }

            that.adjustScroll(that.selectedIndex - 1, 'up');
        },
        moveDown: function () {
            var that = this;

            if (that.selectedIndex === (that.suggestions.length - 1)) {
                return;
            }

            that.latestActivateSource = 'key';

            that.adjustScroll(that.selectedIndex + 1, 'down');
        },
        adjustScroll: function (index, direction) {
            var that = this;


            if (that.suggestions[index].type === 'headline') {
                index = direction === 'down' ? index + 1 : index - 1;
            }

            if (typeof that.suggestions[index] == 'undefined') {
                return;
            }

            var activeItem = that.activate(index);

            that.getDetails(that.suggestions[index]);

            if (that.suggestions[index].type === 'more_products') {

                return;
            }

            if (!activeItem || that.canShowDetailsPanel()) {
                return;
            }

            var offsetTop,
                upperBound,
                lowerBound,
                $suggestionContainer = that.getSuggestionsContainer(),
                heightDelta = $(activeItem).outerHeight(false);

            offsetTop = activeItem.offsetTop;
            upperBound = $suggestionContainer.scrollTop();
            lowerBound = upperBound + that.options.maxHeight - heightDelta;

            if (offsetTop < upperBound) {
                $suggestionContainer.scrollTop(offsetTop);
            } else if (offsetTop > lowerBound) {
                $suggestionContainer.scrollTop(offsetTop - that.options.maxHeight + heightDelta);
            }

            if (!that.options.preserveInput) {
                // During onBlur event, browser will trigger "change" event,
                // because value has changed, to avoid side effect ignore,
                // that event, so that correct suggestion can be selected
                // when clicking on suggestion with a mouse
                that.ignoreValueChange = true;
                //that.el.val(that.getValue(that.suggestions[index].value));
            }
            that.signalHint(null);
        },
        onSelect: function (index) {
            var that = this,
                onSelectCallback = that.options.onSelect,
                suggestion = that.suggestions[index];

            if (typeof suggestion.type != 'undefined') {
                if (
                    suggestion.type === 'more_products'
                    || (that.actionTriggerSource === 'enter' && that.latestActivateSource != 'key' && suggestion.type != 'product_variation')
                ) {
                    that.el.closest('form').trigger('submit');
                    return;
                }
            }

            that.currentValue = that.getValue(suggestion.value);

            if (that.currentValue !== that.el.val() && !that.options.preserveInput) {
                that.el.val(that.currentValue.replace(/(<([^>]+)>)/gi, ' ').replace(/\s\s+/g, ' '));
            }

            if (suggestion.url.length > 0) {
                window.location.href = suggestion.url;
            }

            that.signalHint(null);
            that.suggestions = [];
            that.selection = suggestion;
            if ($.isFunction(onSelectCallback)) {
                onSelectCallback.call(that.element, suggestion);
            }
        },
        openInNewTab: function (index) {
            var that = this,
                suggestion = that.suggestions[index];
            if (suggestion.url.length > 0) {
                window.open(suggestion.url, '_blank').focus();
            }
        },
        getValue: function (value) {
            var that = this,
                delimiter = that.options.delimiter,
                currentValue,
                parts;

            if (!delimiter) {
                return value;
            }

            currentValue = that.currentValue;
            parts = currentValue.split(delimiter);

            if (parts.length === 1) {
                return value;
            }

            return currentValue.substr(0, currentValue.length - parts[parts.length - 1].length) + value;
        },
        dispose: function () {
            var that = this,
                $el = that.el,
                $formWrapper = that.getFormWrapper(),
                $suggestionsWrapper = that.getSuggestionsContainer(),
                $mobileHandler = $formWrapper.find('.js-dgwt-wcas-enable-mobile-form');

            // Remove all events
            if ($formWrapper.length) {
                var $items = $formWrapper.find('*');
                $items.each(function () {
                    $(this).off('.autocomplete');
                });
            }

            $el.removeData('autocomplete');
            $(window).off('resize.autocomplete', that.fixPosition);

            $formWrapper.removeClass('dgwt-wcas-active');

            that.close(false);

            // Remove mobile handler
            if ($mobileHandler.length) {
                $mobileHandler.remove();
            }

            if ($suggestionsWrapper.length) {
                $suggestionsWrapper.html('');
            }
        },
        isMountedOverlayDarkened: function () {
            var that = this,
                $wrapp = that.getFormWrapper(),
                mounted = false;

            if ($wrapp.hasClass(that.classes.darkenOverlayMounted)) {
                mounted = true;
            }
            return mounted;
        },
        enableOverlayDarkened: function () {
            var that = this,
                options = that.options,
                $formWrapper;

            if (!that.isMountedOverlayDarkened()) {
                return;
            }

            $formWrapper = that.getFormWrapper();

            $formWrapper.addClass('dgwt-wcas-search-darkoverl-on');
            $('body').addClass('dgwt-wcas-darkoverl-on');

            // Create node
            if ($('.' + options.darkenedOverlayClass).length == 0) {
                var html = '<div class="' + options.darkenedOverlayClass + '"><div></div><div></div><div></div><div></div></div>';
                $('body').append(html);
                var $darkenedOverlay = $('.' + that.options.darkenedOverlayClass);

                that.positionOverlayDarkened();

                $darkenedOverlay.on('click.autocomplete', function (e) {
                    that.disableOverlayDarkened();
                });

            }

            that.overlayDarkenedState = 'on';

        },
        disableOverlayDarkened: function () {
            var that = this,
                options = that.options,
                $wrapps;

            if (!that.isMountedOverlayDarkened()) {
                return;
            }

            $wrapps = $('.dgwt-wcas-search-darkoverl-on');

            if ($wrapps.length) {
                $wrapps.removeClass('dgwt-wcas-search-darkoverl-on');
            }
            $('body').removeClass('dgwt-wcas-darkoverl-on');


            var $el = $('.' + options.darkenedOverlayClass);
            if ($el.length > 0) {
                $el.remove();
                that.overlayDarkenedState = 'off';
            }
        },
        positionOverlayDarkened: function () {
            var that = this,
                fixed = false,
                $darkenedOverlay = $('.' + that.options.darkenedOverlayClass);

            if ($darkenedOverlay.length > 0) {

                if (that.ancestorHasPositionFixed(that.getFormWrapper())) {
                    fixed = true;
                    $darkenedOverlay.addClass('dgwt-wcas-suggestions-wrapp-fixed');
                } else {
                    $darkenedOverlay.removeClass('dgwt-wcas-suggestions-wrapp-fixed');
                }

                $darkenedOverlay.children('div').each(function (i) {
                    that.positionOverlayDarkenedDiv($(this), i + 1, fixed);
                });
            }
        },
        positionOverlayDarkenedDiv: function ($el, orient, fixed) {
            var that = this,
                elData,
                $baseEl = that.getFormWrapper(),
                css,
                secureOffset = 200; // Secure buffer


            // Different position for search icon layout
            if ($baseEl.hasClass('js-dgwt-wcas-layout-icon')) {
                $baseEl = that.getForm();
            }

            elData = that.getElementInfo($baseEl);

            /**
             * Note 1: If fixed == true, it means position should be calculated related to screen,
             *         otherwise it will be calculated related to document
             *
             * Note 2: It concerns cases 1,3 and 4. 1px is subtracted to achieve an exact match of document height.
             *         I don't know why it's needed.
             */

            switch (orient) {
                case 1:
                    css = {
                        left: (-secureOffset) + 'px',
                        top: (-secureOffset) + 'px',
                        width: (elData.left + secureOffset) + 'px',
                        height: ($(document).outerHeight(false) + secureOffset - 1) + 'px'
                    };
                    break;
                case 2:
                    var topSpan = fixed ? elData.topViewPort : elData.top;
                    css = {
                        left: (-secureOffset) + 'px',
                        top: (-secureOffset) + 'px',
                        width: ($(window).outerWidth(false) + secureOffset) + 'px',
                        height: (topSpan + secureOffset) + 'px',
                    };
                    break;
                case 3:
                    css = {
                        left: (elData.left + elData.width) + 'px',
                        top: (-secureOffset) + 'px',
                        width: ($(window).outerWidth(false) - elData.right) + 'px',
                        height: ($(document).outerHeight(false) + secureOffset - 1) + 'px'
                    };
                    break;
                case 4:
                    var topSpan = fixed ? elData.topViewPort : elData.top;
                    css = {
                        left: (-secureOffset) + 'px',
                        top: (topSpan + elData.height) + 'px',
                        width: ($(window).outerWidth(false) + secureOffset) + 'px',
                        height: ($(document).outerHeight(false) - elData.bottom - 1) + 'px'
                    };
                    break;
            }

            if (css) {
                $el.css(css);
            }
        },
        showCloseButton: function () {
            var that = this,
                iconBody = typeof dgwt_wcas.close_icon != 'undefined' ? dgwt_wcas.close_icon : '',
                $actionsEl = that.getFormWrapper().find('.' + that.options.preloaderClass);

            if (that.el.val().length < that.options.minChars) {
                return;
            }

            // Click close icon
            if (!$actionsEl.hasClass(that.options.closeTrigger)) {
                $actionsEl.on('click.autocomplete', function () {
                    that.close(true);
                });
            }

            $actionsEl.addClass(that.options.closeTrigger);
            $actionsEl.html(iconBody);

        },
        hideCloseButton: function () {
            var that = this,
                $btn = that.getFormWrapper().find('.' + that.options.closeTrigger);

            if ($btn.length) {
                $btn.removeClass(that.options.closeTrigger);
                $btn.html('');
            }

            $btn.off('click.autocomplete');
        },
        addActiveClassIfMissing: function () {
            var activeEl = document.activeElement;
            if (
                typeof activeEl == 'object'
                && $(activeEl).length
                && $(activeEl).hasClass('dgwt-wcas-search-input')
            ) {

                var $search = $(activeEl).closest('.dgwt-wcas-search-wrapp');

                if ($search.length && !$search.hasClass('dgwt-wcas-active')) {
                    $search.addClass('dgwt-wcas-active');
                }
            }
        },
        ancestorHasPositionFixed: function ($element) {

            var $checkElements = $element.add($element.parents());
            var isFixed = false;

            $checkElements.each(function () {

                if ($(this).css("position") === "fixed") {
                    isFixed = true;
                    return false;
                }
            });
            return isFixed;
        },
        gaEvent: function (label, category) {
            var that = this;
            var gaObj = window.hasOwnProperty('GoogleAnalyticsObject') && window.hasOwnProperty(window['GoogleAnalyticsObject']) ? window[window['GoogleAnalyticsObject']] : false;
            if (that.options.sendGAEvents) {
                try {
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'autocomplete_search', {
                            'event_label': label,
                            'event_category': category
                        });
                    } else if (gaObj !== false) {
                        var tracker = gaObj.getAll()[0];
                        if (tracker) tracker.send({
                            hitType: 'event',
                            eventCategory: category,
                            eventAction: 'autocomplete_search',
                            eventLabel: label
                        });
                    }
                } catch (error) {
                }
            }
            if (that.options.enableGASiteSearchModule) {
                try {
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'page_view', {
                            'page_path': '/?s=' + encodeURI(label) + '&post_type=product&dgwt_wcas=1',
                        });
                    } else if (gaObj !== false) {
                        var tracker2 = gaObj.getAll()[0];
                        if (tracker2) {
                            tracker2.set('page', '/?s=' + encodeURI(label) + '&post_type=product&dgwt_wcas=1');
                            tracker2.send('pageview');
                        }
                    }
                } catch (error) {
                }
            }

            $(document).trigger('dgwtWcasGAEvent', {'term': label, 'category': category});
        },
        initVoiceSearch: function () {
            var that = this;
            if (!that.options.voiceSearchEnabled) {
                return false;
            }
            var $formWrapper = that.getFormWrapper();
            var $input = $formWrapper.find('.' + that.options.searchInputClass);
            var $voiceSearch = $formWrapper.find('.' + that.options.voiceSearchClass);
            var SpeechRecognition = SpeechRecognition || webkitSpeechRecognition;

            if (!SpeechRecognition) {
                return false;
            }

            that.voiceSearchSetState('inactive', $voiceSearch);
            $formWrapper.addClass(that.options.voiceSearchSupportedClass);

            that.voiceSearchRecognition = new SpeechRecognition();
            that.voiceSearchRecognition.lang = that.options.voiceSearchLang;
            that.voiceSearchRecognition.continuous = false;
            that.voiceSearchRecognition.interimResults = true;
            that.voiceSearchRecognition.maxAlternatives = 1;

            $voiceSearch.on('click', function () {
                if (
                    $formWrapper.hasClass('dgwt-wcas-mobile-overlay-trigger-active') &&
                    !$('html').hasClass('dgwt-wcas-overlay-mobile-on')
                ) {
                    $formWrapper.find('.js-dgwt-wcas-enable-mobile-form').click();
                    $formWrapper.find('.' + that.options.searchInputClass).blur();
                }
                if (that.voiceSearchStarted) {
                    that.voiceSearchAbort();
                    return;
                }
                if (that.voiceSearchIsInitialized()) {
                    that.voiceSearchAbort();
                }
                that.voiceSearchRecognition.start();
            });

            that.voiceSearchRecognition.onstart = function (event) {
                that.voiceSearchSetState('active', $voiceSearch);
            }

            that.voiceSearchRecognition.onresult = function (event) {
                const result = event.results[0];
                const text = result[0].transcript;
                $input.val(text);
                if (result.isFinal) {
                    $input.trigger('change');
                    if (!'ontouchend' in document) {
                        $input.focus();
                    }
                    that.voiceSearchSetState('inactive', $voiceSearch);
                }
            }

            that.voiceSearchRecognition.onspeechend = function () {
                that.voiceSearchSetState('inactive', $voiceSearch);
                that.voiceSearchRecognition.stop();
            }

            that.voiceSearchRecognition.onnomatch = function (event) {
                that.voiceSearchSetState('inactive', $voiceSearch);
            }

            that.voiceSearchRecognition.onerror = function (event) {
                switch (event.error) {
                    case 'aborted':
                    case 'no-speech':
                        that.voiceSearchSetState('inactive', $voiceSearch);
                        break;
                    case 'network':
                        break;
                    case 'not-allowed':
                    case 'service-not-allowed':
                        that.voiceSearchSetState('off', $voiceSearch);
                        break;
                }
            }
        },
        voiceSearchAbort: function () {
            var that = this;
            if (that.voiceSearchIsInitialized()) {
                that.voiceSearchRecognition.abort();
                that.voiceSearchStarted = false;
            }
        },
        voiceSearchIsInitialized: function () {
            var that = this;
            return that.voiceSearchRecognition !== null;
        },
        voiceSearchSetState: function (state, $voiceSearch) {
            var that = this;
            switch (state) {
                case 'active':
                    that.voiceSearchStarted = true;
                    if (typeof dgwt_wcas.voice_search_active_icon == 'string') {
                        $voiceSearch.html(dgwt_wcas.voice_search_active_icon);
                    }
                    // $voiceSearch.removeClass(that.options.voiceSearchDisabledClass).addClass(that.options.voiceSearchActiveClass);
                    break;
                case 'inactive':
                    that.voiceSearchStarted = false;
                    if (typeof dgwt_wcas.voice_search_inactive_icon == 'string') {
                        $voiceSearch.html(dgwt_wcas.voice_search_inactive_icon);
                    }
                    // $voiceSearch.removeClass(that.options.voiceSearchActiveClass + ' ' + that.options.voiceSearchDisabledClass);
                    break;
                case 'off':
                    that.voiceSearchStarted = false;
                    // $voiceSearch.removeClass(that.options.voiceSearchActiveClass).addClass(that.options.voiceSearchDisabledClass);
                    if (typeof dgwt_wcas.voice_search_disabled_icon == 'string') {
                        $voiceSearch.html(dgwt_wcas.voice_search_disabled_icon);
                    }
                    break;
            }
        },
    };

    // Create chainable jQuery plugin:
    $.fn.dgwtWcasAutocomplete = function (options, args) {
        var dataKey = 'autocomplete';
        // If function invoked without argument return
        // instance of the first matched element:
        if (!arguments.length) {
            return this.first().data(dataKey);
        }

        return this.each(function () {
            var inputElement = $(this),
                instance = inputElement.data(dataKey);

            if (typeof options === 'string') {
                if (instance && typeof instance[options] === 'function') {
                    instance[options](args);
                }
            } else {
                // If instance already exists, destroy it:
                if (instance && instance.dispose) {
                    instance.dispose();
                }

                instance = new DgwtWcasAutocompleteSearch(this, options);
                inputElement.data(dataKey, instance);
            }
        });
    };

    // Don't overwrite if it already exists
    if (!$.fn.autocomplete) {
        $.fn.autocomplete = $.fn.dgwtWcasAutocomplete;
    }

    (function () {

        function isIOS() {
            return [
                    'iPad Simulator',
                    'iPhone Simulator',
                    'iPod Simulator',
                    'iPad',
                    'iPhone',
                    'iPod'
                ].includes(navigator.platform)
                // iPad on iOS 13 detection
                || (navigator.userAgent.includes("Mac") && "ontouchend" in document)
        }

        function isIE11() {
            return !!navigator.userAgent.match(/Trident\/7\./);
        }

        /*-----------------------------------------------------------------
        /* IE11 polyfills
        /*-----------------------------------------------------------------*/
        if (isIE11()) {
            // https://polyfill.io/v3/polyfill.min.js?features=Array.prototype.includes%2CString.prototype.includes
            (function (self, undefined) {
                function Call(t, l) {
                    var n = arguments.length > 2 ? arguments[2] : [];
                    if (!1 === IsCallable(t)) throw new TypeError(Object.prototype.toString.call(t) + "is not a function.");
                    return t.apply(l, n)
                }

                function CreateMethodProperty(e, r, t) {
                    var a = {value: t, writable: !0, enumerable: !1, configurable: !0};
                    Object.defineProperty(e, r, a)
                }

                function Get(n, t) {
                    return n[t]
                }

                function IsCallable(n) {
                    return "function" == typeof n
                }

                function RequireObjectCoercible(e) {
                    if (null === e || e === undefined) throw TypeError(Object.prototype.toString.call(e) + " is not coercible to Object.");
                    return e
                }

                function SameValueNonNumber(e, n) {
                    return e === n
                }

                function ToBoolean(o) {
                    return Boolean(o)
                }

                function ToObject(e) {
                    if (null === e || e === undefined) throw TypeError();
                    return Object(e)
                }

                function GetV(t, e) {
                    return ToObject(t)[e]
                }

                function GetMethod(e, n) {
                    var r = GetV(e, n);
                    if (null === r || r === undefined) return undefined;
                    if (!1 === IsCallable(r)) throw new TypeError("Method not callable: " + n);
                    return r
                }

                function Type(e) {
                    switch (typeof e) {
                        case"undefined":
                            return "undefined";
                        case"boolean":
                            return "boolean";
                        case"number":
                            return "number";
                        case"string":
                            return "string";
                        case"symbol":
                            return "symbol";
                        default:
                            return null === e ? "null" : "Symbol" in self && (e instanceof self.Symbol || e.constructor === self.Symbol) ? "symbol" : "object"
                    }
                }

                function IsRegExp(e) {
                    if ("object" !== Type(e)) return !1;
                    var n = "Symbol" in self && "match" in self.Symbol ? Get(e, self.Symbol.match) : undefined;
                    if (n !== undefined) return ToBoolean(n);
                    try {
                        var t = e.lastIndex;
                        return e.lastIndex = 0, RegExp.prototype.exec.call(e), !0
                    } catch (l) {
                    } finally {
                        e.lastIndex = t
                    }
                    return !1
                }

                function OrdinaryToPrimitive(r, t) {
                    if ("string" === t) var e = ["toString", "valueOf"]; else e = ["valueOf", "toString"];
                    for (var i = 0; i < e.length; ++i) {
                        var n = e[i], a = Get(r, n);
                        if (IsCallable(a)) {
                            var o = Call(a, r);
                            if ("object" !== Type(o)) return o
                        }
                    }
                    throw new TypeError("Cannot convert to primitive.")
                }

                function SameValueZero(n, e) {
                    return Type(n) === Type(e) && ("number" === Type(n) ? !(!isNaN(n) || !isNaN(e)) || (1 / n === Infinity && 1 / e == -Infinity || (1 / n == -Infinity && 1 / e === Infinity || n === e)) : SameValueNonNumber(n, e))
                }

                function ToInteger(n) {
                    if ("symbol" === Type(n)) throw new TypeError("Cannot convert a Symbol value to a number");
                    var t = Number(n);
                    return isNaN(t) ? 0 : 1 / t === Infinity || 1 / t == -Infinity || t === Infinity || t === -Infinity ? t : (t < 0 ? -1 : 1) * Math.floor(Math.abs(t))
                }

                function ToLength(n) {
                    var t = ToInteger(n);
                    return t <= 0 ? 0 : Math.min(t, Math.pow(2, 53) - 1)
                }

                function ToPrimitive(e) {
                    var t = arguments.length > 1 ? arguments[1] : undefined;
                    if ("object" === Type(e)) {
                        if (arguments.length < 2) var i = "default"; else t === String ? i = "string" : t === Number && (i = "number");
                        var r = "function" == typeof self.Symbol && "symbol" == typeof self.Symbol.toPrimitive ? GetMethod(e, self.Symbol.toPrimitive) : undefined;
                        if (r !== undefined) {
                            var n = Call(r, e, [i]);
                            if ("object" !== Type(n)) return n;
                            throw new TypeError("Cannot convert exotic object to primitive.")
                        }
                        return "default" === i && (i = "number"), OrdinaryToPrimitive(e, i)
                    }
                    return e
                }

                function ToString(t) {
                    switch (Type(t)) {
                        case"symbol":
                            throw new TypeError("Cannot convert a Symbol value to a string");
                        case"object":
                            return ToString(ToPrimitive(t, String));
                        default:
                            return String(t)
                    }
                }

                CreateMethodProperty(Array.prototype, "includes", function e(r) {
                    "use strict";
                    var t = ToObject(this), o = ToLength(Get(t, "length"));
                    if (0 === o) return !1;
                    var n = ToInteger(arguments[1]);
                    if (n >= 0) var a = n; else (a = o + n) < 0 && (a = 0);
                    for (; a < o;) {
                        var i = Get(t, ToString(a));
                        if (SameValueZero(r, i)) return !0;
                        a += 1
                    }
                    return !1
                });
                CreateMethodProperty(String.prototype, "includes", function e(t) {
                    "use strict";
                    var r = arguments.length > 1 ? arguments[1] : undefined, n = RequireObjectCoercible(this),
                        i = ToString(n);
                    if (IsRegExp(t)) throw new TypeError("First argument to String.prototype.includes must not be a regular expression");
                    var o = ToString(t), g = ToInteger(r), a = i.length, p = Math.min(Math.max(g, 0), a);
                    return -1 !== String.prototype.indexOf.call(i, o, p)
                });
            })('object' === typeof window && window || 'object' === typeof self && self || 'object' === typeof global && global || {});
        }

        // RUN
        $(document).ready(function () {
            "use strict";

            /*-----------------------------------------------------------------
            /* Mobile detection
            /*-----------------------------------------------------------------*/
            if (isIOS()) {
                $('html').addClass('dgwt-wcas-is-ios');
            }

            /*-----------------------------------------------------------------
            /* Set some global variables
            /*-----------------------------------------------------------------*/
            window.dgwt_wcas.resizeOnlyOnce = null;
            window.dgwt_wcas.scrollOnlyOnce = null;

            /*-----------------------------------------------------------------
            /* Fire autocomplete
            /*-----------------------------------------------------------------*/

            window.dgwt_wcas.config = {
                minChars: dgwt_wcas.min_chars,
                width: dgwt_wcas.sug_width,
                autoSelectFirst: false,
                triggerSelectOnValidInput: false,
                serviceUrl: dgwt_wcas.ajax_search_endpoint,
                paramName: 's',
                showDetailsPanel: dgwt_wcas.show_details_panel == 1 ? true : false,
                showImage: dgwt_wcas.show_images == 1 ? true : false,
                showPrice: dgwt_wcas.show_price == 1 ? true : false,
                showDescription: dgwt_wcas.show_desc == 1 ? true : false,
                showSKU: dgwt_wcas.show_sku == 1 ? true : false,
                showSaleBadge: dgwt_wcas.show_sale_badge == 1 ? true : false,
                showFeaturedBadge: dgwt_wcas.show_featured_badge == 1 ? true : false,
                dynamicPrices: typeof dgwt_wcas.dynamic_prices != 'undefined' && dgwt_wcas.dynamic_prices ? true : false,
                saleBadgeText: dgwt_wcas.labels.sale_badge,
                featuredBadgeText: dgwt_wcas.labels.featured_badge,
                isRtl: dgwt_wcas.is_rtl == 1 ? true : false,
                showHeadings: dgwt_wcas.show_headings == 1 ? true : false,
                isPremium: dgwt_wcas.is_premium == 1 ? true : false,
                taxonomyBrands: dgwt_wcas.taxonomy_brands,
                layoutBreakpoint: dgwt_wcas.layout_breakpoint,
                mobileOverlayBreakpoint: dgwt_wcas.mobile_overlay_breakpoint,
                mobileOverlayWrapper: dgwt_wcas.mobile_overlay_wrapper,
                mobileOverlayDelay: dgwt_wcas.mobile_overlay_delay,
                debounceWaitMs: dgwt_wcas.debounce_wait_ms,
                sendGAEvents: dgwt_wcas.send_ga_events,
                convertHtml: dgwt_wcas.convert_html,
                enableGASiteSearchModule: dgwt_wcas.enable_ga_site_search_module,
                appendTo: typeof dgwt_wcas.suggestions_wrapper != 'undefined' ? dgwt_wcas.suggestions_wrapper : 'body',
                showProductVendor: typeof dgwt_wcas.show_product_vendor != 'undefined' && dgwt_wcas.show_product_vendor ? true : false,
                disableHits: typeof dgwt_wcas.disable_hits != 'undefined' && dgwt_wcas.disable_hits ? true : false,
                disableSubmit: typeof dgwt_wcas.disable_submit != 'undefined' && dgwt_wcas.disable_submit ? true : false,
                voiceSearchEnabled: typeof dgwt_wcas.voice_search_enabled != 'undefined' && dgwt_wcas.voice_search_enabled ? true : false,
                voiceSearchLang: typeof dgwt_wcas.voice_search_lang != 'undefined' ? dgwt_wcas.voice_search_lang  : '',
            };

            $('.dgwt-wcas-search-input').dgwtWcasAutocomplete(window.dgwt_wcas.config);

        });


        /**
         * UI Fixer. Fixes several known UX issues related to search bars
         */
        var UI_FIXER = {
            brokenSearchUi: typeof dgwt_wcas.fixer.broken_search_ui != 'undefined' && dgwt_wcas.fixer.broken_search_ui ? true : false,
            brokenSearchUiAjax: typeof dgwt_wcas.fixer.broken_search_ui_ajax != 'undefined' && dgwt_wcas.fixer.broken_search_ui_ajax ? true : false,
            brokenSearchUiHard: typeof dgwt_wcas.fixer.broken_search_ui_hard != 'undefined' && dgwt_wcas.fixer.broken_search_ui_hard ? true : false,
            brokenSearchElementorPopups: typeof dgwt_wcas.fixer.broken_search_elementor_popups != 'undefined' && dgwt_wcas.fixer.broken_search_elementor_popups ? true : false,
            brokenSearchBrowserBackArrow: typeof dgwt_wcas.fixer.broken_search_browsers_back_arrow != 'undefined' && dgwt_wcas.fixer.broken_search_browsers_back_arrow ? true : false,
            forceRefreshCheckout: typeof dgwt_wcas.fixer.force_refresh_checkout != 'undefined' && dgwt_wcas.fixer.force_refresh_checkout ? true : false,
            searchBars: [],
            init: function () {
                var that = this;

                /**
                 * Fix broken search bars UI - first approach, after loading the page
                 * Some page builders can copy instances of search bar without events eg. mobile usage.
                 */
                if (that.brokenSearchUi) {
                    $(document).ready(function () {
                        that.fixBrokenSearchUi();
                    });
                }

                /**
                 *
                 */
                if (that.brokenSearchUiAjax) {
                    that.fixBrokenSearchUiAjax();
                }

                /**
                 * Repair search bars continuously
                 * May overload the browser. Disabled by default.
                 */
                if (that.brokenSearchUiHard) {
                    that.fixBrokenSearchUiHard();
                }

                /**
                 * Fix Elementor popups
                 * Reinit the search bars after loading Elementor popup
                 */
                if (that.brokenSearchElementorPopups) {
                    $(document).ready(function () {
                        that.fixBrokenSearchOnElementorPopupsV1();
                        that.fixBrokenSearchOnElementorPopupsV2();
                    });
                }

                /**
                 * Fix broken search bars after click browser's back arrow.
                 * Not worked for some browsers especially Safari and FF
                 * Add dgwt-wcas-active class if wasn't added for some reason
                 */
                if (that.brokenSearchBrowserBackArrow) {
                    that.fixbrokenSearchBrowserBackArrow();
                }

                /**
                 * Refreshing the content on the checkout page when a product is added to the cart from the search Details Panel
                 */
                if (that.forceRefreshCheckout) {
                    that.fixforceRefreshCheckout();
                }
            },
            fixBrokenSearchUi: function () {
                var that = this;

                $(document).ready(function () {
                    setTimeout(function () {
                        that.pullAndReconditionSearchBars();
                    }, 50);
                });

                $(window).on('load', function () {
                    setTimeout(function () {
                        that.pullAndReconditionSearchBars();
                    }, 500);
                });
            },
            fixBrokenSearchUiAjax: function () {
                var that = this;
                $(document).ajaxSuccess(function (event, request, settings) {

                    // Exclude FiboSearch and WooCommerce AJAX requests
                    if (typeof settings.url == 'string' && new RegExp('search\.php|wc-ajax').test(settings.url)) {
                        return;
                    }

                    if (typeof request.responseText == 'string' && request.responseText.includes('dgwt-wcas-search-input')) {
                        setTimeout(function () {
                            that.pullAndReconditionSearchBars();
                        }, 500);
                    }
                });
            },
            fixBrokenSearchUiHard: function () {
                var that = this;
                $(document).ready(function () {

                    if (that.searchBars.length === 0) {
                        that.pullAndReconditionSearchBars();
                    }

                    setInterval(function () {
                        that.pullAndReconditionSearchBars();
                    }, 1000);
                });
            },
            fixBrokenSearchOnElementorPopupsV1: function () {
                var that = this;
                $(document).on('elementor/popup/show', () => {
                    setTimeout(function () {
                        that.pullAndReconditionSearchBars();
                    }, 500);
                });
            },
            fixBrokenSearchOnElementorPopupsV2: function () {
                var that = this;
                $(document).ready(function () {
                    if (
                        typeof window.elementorFrontend != 'undefined'
                        && typeof window.elementorFrontend.documentsManager != 'undefined'
                        && typeof window.elementorFrontend.documentsManager.documents != 'undefined'
                    ) {

                        $.each(elementorFrontend.documentsManager.documents, function (id, document) {

                            if (typeof document.getModal != 'undefined' && document.getModal) {

                                document.getModal().on('show', function () {
                                    setTimeout(function () {
                                        that.pullAndReconditionSearchBars();
                                    }, 500);

                                });
                            }
                        });
                    }
                });
            },
            fixforceRefreshCheckout: function () {
                $(document.body).on('added_to_cart', function () {
                    if (
                        $(document.body).hasClass('woocommerce-checkout') &&
                        $('.dgwt-wcas-search-input').length > 0
                    ) {
                        $(document.body).trigger('update_checkout');
                    }
                });
            },
            fixbrokenSearchBrowserBackArrow: function () {
                $(window).on('load', function () {
                    var i = 0;
                    var interval = setInterval(function () {

                        var activeEl = document.activeElement;
                        if (
                            typeof activeEl == 'object'
                            && $(activeEl).length
                            && $(activeEl).hasClass('dgwt-wcas-search-input')
                        ) {

                            var $search = $(activeEl).closest('.dgwt-wcas-search-wrapp');

                            if ($search.length && !$search.hasClass('dgwt-wcas-active')) {
                                $search.addClass('dgwt-wcas-active');
                                clearInterval(interval);
                            }
                        }

                        // Stop after 5 seconds
                        if (i > 10) {
                            clearInterval(interval);
                        }

                        i++;

                    }, 500);
                });
            },
            pullAndReconditionSearchBars: function () {
                var that = this,
                    $inputs = $('.dgwt-wcas-search-input'),
                    firstPull = that.searchBars.length == 0;

                if ($inputs.length > 0) {
                    $inputs.each(function () {
                        var $searchBar = $(this),
                            isNew = true,
                            i;

                        // Check if this search bar is new one or not
                        if (that.searchBars.length > 0) {
                            for (i = 0; i < that.searchBars.length; i++) {
                                if ($searchBar[0] === that.searchBars[i][0]) {
                                    isNew = false;
                                    break;
                                }
                            }
                        }

                        if (isNew) {
                            var changedId = false;
                            if (!that.hasUniqueId($searchBar)) {
                                that.makeUniqueID($searchBar);
                                changedId = true;
                            }

                            if (!firstPull || !that.isInitialized($searchBar) || changedId) {
                                that.reinitSearchBar($searchBar)
                            }

                            that.searchBars.push($searchBar);
                        }
                    });
                }
            },
            isInitialized: function ($searchBarInput) {
                return typeof $searchBarInput.data('autocomplete') == 'object';
            },
            hasUniqueId: function ($searchBarInput) {
                var that = this,
                    unique = true;
                if (that.searchBars.length > 0) {
                    for (var i = 0; i < that.searchBars.length; i++) {
                        if ($searchBarInput.attr('id') === that.searchBars[i].attr('id')) {
                            unique = false;
                        }
                    }
                }
                return unique;
            },
            reinitSearchBar: function ($searchBarInput) {
                if (typeof $searchBarInput.data('autocomplete') == 'object') {
                    $searchBarInput.data('autocomplete').dispose();
                }
                $searchBarInput.dgwtWcasAutocomplete(window.dgwt_wcas.config);
            },
            makeUniqueID: function ($searchBarInput) {
                var newID = Math.random().toString(36).substring(2, 6);
                newID = 'dgwt-wcas-search-input-' + newID;

                $searchBarInput.attr('id', newID);
                $searchBarInput.closest('form').find('label').attr('for', newID);
            },
        }

        UI_FIXER.init();
    }());

}));
