(function ($) {

    var RADIO_SETTINGS_TOGGLE = {
        inputSel: 'dgwt-wcas-options-toggle input[type=radio]',
        groupSel: 'dgwt_wcas_settings-group',
        reloadChoices: function (name) {
            var _this = this,
                $group = $('[name="' + name + '"]').closest('.' + _this.groupSel),
                value = $('[name="' + name + '"]:checked').val(),
                currentClass = '';

            _this.hideAll($group);

            value = value.replace('_', '-');

            if (value.length > 0) {
                currentClass = 'wcas-opt-' + value;
            }

            if ($('.' + currentClass).length > 0) {
                $('.' + currentClass).fadeIn();
            }


        },
        hideAll: function ($group) {
            $group.find('tr[class*="wcas-opt-"]').hide();
        },
        registerListeners: function () {
            var _this = this;

            $('.' + _this.inputSel).on('change', function () {
                _this.reloadChoices($(this).attr('name'));
            });

        },
        init: function () {
            var _this = this,
                $sel = $('.' + _this.inputSel + ':checked');

            if ($sel.length > 0) {
                _this.registerListeners();

                $sel.each(function () {
                    _this.reloadChoices($(this).attr('name'));
                });

            }


        }

    };


    var CHECKBOX_SETTINGS_TOGGLE = {
        inputSel: 'dgwt-wcas-options-cb-toggle input[type=checkbox]',
        groupSel: 'dgwt_wcas_settings-group',
        reloadChoices: function ($el) {
            var _this = this,
                checked = $el.is(':checked'),
                groupClass = _this.getGroupSelector($el);

            $('.' + groupClass + ':not(.dgwt-wcas-options-cb-toggle)').hide();

            if (checked) {
                $('.' + groupClass).each(function () {
                    if (!(
                        $(this).hasClass('js-dgwt-wcas-adv-settings')
                        && $('.js-dgwt-wcas-adv-settings-toggle').hasClass('woocommerce-input-toggle--disabled')
                    )
                    ) {
                        $(this).fadeIn();
                    }
                });
            }
        },
        getGroupSelector($el) {
            var $row = $el.closest('.dgwt-wcas-options-cb-toggle'),
                className = '',
                classList = $row.attr('class').split(/\s+/);

            $.each(classList, function (index, item) {
                if (item.indexOf('js-dgwt-wcas-cbtgroup-') !== -1) {
                    className = item;
                }
            });

            return className;
        },
        registerListeners: function () {
            var _this = this;

            $(document).on('change', '.' + _this.inputSel, function () {
                _this.reloadChoices($(this));
            });

        },
        refresh: function () {
            var _this = this,
                $sel = $('.' + _this.inputSel);
            if ($sel.length > 0) {
                $sel.each(function () {
                    var checked = $(this).is(':checked'),
                        groupClass = _this.getGroupSelector($(this));

                    if (checked) {
                        $('.' + groupClass).fadeIn();
                    } else {
                        $('.' + groupClass + ':not(.dgwt-wcas-options-cb-toggle)').hide();
                    }
                });
            }
        },
        init: function () {
            var _this = this,
                $sel = $('.' + _this.inputSel);

            if ($sel.length > 0) {
                _this.registerListeners();

                $sel.each(function () {
                    _this.reloadChoices($(this));
                });

            }


        }

    };

    var CHECKBOX_SETTINGS_TOGGLE_SIBLING = {
        inputSel: 'js-dgwt-wcas-options-toggle-sibling input[type=checkbox]',

        toogleSibling: function ($el) {
            var _this = this;
            var checked = $el.is(':checked');

            if (checked) {
                $el.closest('label').next().fadeIn();
            } else {
                $el.closest('label').next().hide();
            }
        },

        registerListeners: function () {
            var _this = this;

            $(document).on('change', '.' + _this.inputSel, function () {
                _this.toogleSibling($(this));
            });
        },

        init: function () {
            var _this = this;
            var $sel = $('.' + _this.inputSel);

            if ($sel.length > 0) {
                _this.registerListeners();

                $sel.each(function () {
                    _this.toogleSibling($(this));
                });
            }
        }
    };

    var CONDITIONAL_LAYOUT_SETTINGS = {
        layoutSelect: "select[id*='search_layout']",
        overlayMobile: "input[id*='enable_mobile_overlay']",
        switchLayoutBreakpoint: "input[id*='mobile_breakpoint']",
        mobileOverlayBreakpoint: "input[id*='mobile_overlay_breakpoint']",
        searchIconColor: "input[id*='search_icon_color']",
        $layoutSelectEl: null,
        $overlayMobileEl: null,
        $switchLayoutBreakpointEl: null,
        $mobileOverlayBreakpointEl: null,
        $searchIconColorEl: null,
        setConditions: function () {
            var _this = this,
                layoutVal = _this.$layoutSelectEl.find('option:selected').val(),
                overlayOnMobileVal = _this.$overlayMobileEl.is(':checked'),
                hasAdvSettings = $('.js-dgwt-wcas-adv-settings-toggle').hasClass('woocommerce-input-toggle--enabled');

            _this.hideOption(_this.$switchLayoutBreakpointEl);
            _this.hideOption(_this.$mobileOverlayBreakpointEl);
            _this.hideOption(_this.$searchIconColorEl);

            $("input[id*='bg_search_icon_color']").closest('tr').show();

            switch (layoutVal) {
                case 'icon':

                    if (hasAdvSettings) {
                        _this.showOption(_this.$searchIconColorEl);
                    }

                    break;
                case 'icon-flexible':
                case 'icon-flexible-inv':

                    if (hasAdvSettings) {
                        _this.showOption(_this.$switchLayoutBreakpointEl);
                        _this.showOption(_this.$searchIconColorEl);
                    }

                    break;
                default:

                    if (hasAdvSettings) {
                        $("input[id*='bg_search_icon_color']").closest('tr').hide();
                    }
                    break;
            }

            if (overlayOnMobileVal) {
                _this.showOption(_this.$mobileOverlayBreakpointEl);
            }

        },
        hideOption: function ($el) {
            $el.closest('tr').hide();
        },
        showOption: function ($el) {
            $el.closest('tr').show();
        },
        registerListeners: function () {
            var _this = this;

            _this.$layoutSelectEl.on('change', function () {
                _this.setConditions();
            });

            _this.$overlayMobileEl.on('change', function () {
                _this.setConditions();
            });

        },
        init: function () {
            var _this = this,
                $layoutSelectEl = $(_this.layoutSelect);

            if ($layoutSelectEl.length > 0) {
                _this.$layoutSelectEl = $layoutSelectEl;
                _this.$overlayMobileEl = $(_this.overlayMobile);
                _this.$switchLayoutBreakpointEl = $(_this.switchLayoutBreakpoint);
                _this.$mobileOverlayBreakpointEl = $(_this.mobileOverlayBreakpoint);
                _this.$searchIconColorEl = $(_this.searchIconColor);
                _this.registerListeners();

                setTimeout(function () {
                    _this.setConditions();
                }, 400);
            }

        }

    };

    var AJAX_BUILD_INDEX = {
        actionTriggerClass: 'js-ajax-build-index',
        actionStopTriggerClass: 'js-ajax-stop-build-index',
        indexingWrappoerClass: 'js-dgwt-wcas-indexing-wrapper',
        indexerTabProgressClass: 'js-dgwt-wcas-indexer-tab-progress',
        indexerTabErrorClass: 'js-dgwt-wcas-indexer-tab-error',
        indexerMenuErrorClass: 'dgwt-wcas-menu-warning-icon',
        getWrapper: function () {
            var _this = this;

            return $('.' + _this.indexingWrappoerClass).closest('.dgwt-wcas-settings-info');
        },
        registerListeners: function () {
            var _this = this;

            $(document).on('click', '.' + _this.actionTriggerClass, function (e) {
                e.preventDefault();

                var $btn = $(this);

                $btn.attr('disabled', 'disabled');

                $('.dgwt-wcas-settings-info').addClass('wcas-ajax-build-index-wait');
                $('.' + _this.indexerTabErrorClass).removeClass('active');
                $('.' + _this.indexerTabProgressClass).addClass('active');

                var emergency = $btn.hasClass('js-ajax-build-index-emergency') ? true : false;

                if (emergency) {

                    $('.dgwt-wcas-indexing-header__title').text('[Emergency mode] Wait... Indexing in progress');
                    $('.dgwt-wcas-indexing-header__troubleshooting, .dgwt-wcas-indexing-header__actions, .js-dgwt-wcas-indexer-details').hide();
                }

                $.ajax({
                    url: ajaxurl,
                    type: 'post',
                    data: {
                        action: 'dgwt_wcas_build_index',
                        emergency: emergency,
                        _wpnonce: dgwt_wcas.nonces.build_index,
                    },
                    success: function (response) {
                        if (typeof response != 'undefined' && response.success) {
                            _this.getWrapper().html(response.data.html);
                            _this.heartbeat();
                        }
                    },
                    complete: function () {
                        $btn.removeAttr('disabled');
                        $('.dgwt-wcas-settings-info').removeClass('wcas-ajax-build-index-wait');
                        if (emergency) {
                            window.location.reload();
                        }
                    }
                });
            })

            $(document).on('click', '.' + _this.actionStopTriggerClass, function (e) {
                e.preventDefault();

                var $btn = $(this);

                $btn.attr('disabled', 'disabled');
                _this.getWrapper().attr('data-stopping', '1');

                $.ajax({
                    url: ajaxurl,
                    type: 'post',
                    data: {
                        action: 'dgwt_wcas_stop_build_index',
                        _wpnonce: dgwt_wcas.nonces.stop_build_index,
                    },
                    success: function (response) {
                        if (typeof response != 'undefined' && response.success) {
                            _this.getWrapper().html(response.data.html);
                            _this.heartbeat();
                        }
                    },
                    complete: function () {
                        _this.getWrapper().attr('data-stopping', '0');
                    }
                });
            })
        },
        heartbeat: function () {
            var _this = this;

            setTimeout(function () {

                $.ajax({
                    url: ajaxurl,
                    type: 'post',
                    data: {
                        action: 'dgwt_wcas_build_index_heartbeat',
                        _wpnonce: dgwt_wcas.nonces.build_index_heartbeat,
                    },
                    success: function (response) {
                        if (typeof response != 'undefined' && response.success) {
                            if (_this.getWrapper().attr('data-stopping') === '1') {
                                return;
                            }
                            _this.getWrapper().html(response.data.html);

                            if (response.data.loop) {
                                _this.heartbeat();
                            } else {
                                $('.' + _this.indexerTabProgressClass).removeClass('active');
                                if (response.data.status === 'error') {
                                    $('.' + _this.indexerTabErrorClass).addClass('active');
                                } else if (response.data.status === 'completed') {
                                    $('.' + _this.indexerMenuErrorClass).remove();
                                }
                            }

                            if (!response.data.loop && response.data.refresh_once.length > 0) {
                                // If refresh cookie non exist and Troubleshooting tab is hidden then reload
                                if (
                                    !document.cookie.split(';').some(function (item) {
                                        return item.trim().indexOf('dgwt_wcas_refresh_once=' + response.data.refresh_once) === 0;
                                    }) &&
                                    $('#dgwt_wcas_troubleshooting-tab').css('display') === 'none'
                                ) {
                                    document.cookie = 'dgwt_wcas_refresh_once=' + response.data.refresh_once;
                                    location.reload();
                                }
                            }

                        }
                    }
                });

            }, 1000);
        },
        detailsToggle: function () {
            var _this = this,
                display;


            $(document).on('click', '.js-dgwt-wcas-indexing-details-trigger', function (e) {
                e.preventDefault();

                var $details = $('.js-dgwt-wcas-indexer-details');

                if ($details.hasClass('show')) {
                    $details.removeClass('show');
                    $details.addClass('hide');
                    $('.js-dgwt-wcas-indexing__showd').addClass('show').removeClass('hide');
                    $('.js-dgwt-wcas-indexing__hided').addClass('hide').removeClass('show');
                    display = false;
                } else {
                    $details.addClass('show');
                    $details.removeClass('hide');
                    $('.js-dgwt-wcas-indexing__showd').addClass('hide').removeClass('show');
                    $('.js-dgwt-wcas-indexing__hided').addClass('show').removeClass('hide');
                    display = true;
                }

                $.ajax({
                    url: ajaxurl,
                    type: 'post',
                    data: {
                        action: 'dgwt_wcas_index_details_toggle',
                        display: display
                    }
                });
            });


        },
        init: function () {
            var _this = this;
            _this.registerListeners();

            if ($('.' + _this.indexingWrappoerClass).length > 0) {
                _this.heartbeat();
            }
            _this.detailsToggle();
        }
    };

    var SELECTIZE = {
        init: function () {
            var _this = this;

            if ($('.dgwt-wcas-selectize').length > 0) {
                $.ajax({
                    url: ajaxurl,
                    data: {
                        action: 'dgwt_wcas_settings_list_custom_fields',
                        _wpnonce: $('.dgwt-wcas-selectize').data('security')

                    },
                    success: function (res) {

                        if (typeof res != 'undefined' && typeof res.data != 'undefined') {
                            _this.initSelectize(res.data);
                        }
                    }
                });
            }

        },
        initSelectize: function (loadedOptions) {

            var $inputs = $('.dgwt-wcas-selectize');


            if ($inputs.length > 0) {
                $inputs.each(function () {

                    var $input = $(this);
                    var optionsRaw = $input.data('options');
                    var options = loadedOptions;

                    if (optionsRaw.length > 0) {
                        optionsRaw = JSON.parse('["' + decodeURI(optionsRaw.replace(/&/g, "\",\"").replace(/=/g, "\",\"")) + '"]');

                        var lastKey = '';

                        optionsRaw.forEach(function (el, i) {

                            if ((i + 1) % 2 === 0) {
                                var obj = {value: el, label: lastKey};
                                options.push(obj);
                                lastKey = '';
                            }
                            lastKey = el;
                        });

                    }

                    $(this).selectize({
                        persist: false,
                        maxItems: null,
                        valueField: 'key',
                        labelField: 'label',
                        searchField: ['value', 'label'],
                        options: options,
                        create: function (input) {
                            return {
                                value: input.key,
                                label: input.label
                            }
                        },
                        load: function (query, callback) {
                            if (!query.length) return callback();

                            $.ajax({
                                url: ajaxurl,
                                data: {
                                    action: 'dgwt_wcas_settings_list_custom_fields',
                                    _wpnonce: $input.data('security')

                                },
                                error: function () {
                                    callback();
                                },
                                success: function (res) {
                                    callback(res.data);
                                }
                            });
                        }
                    });

                });
            }

        }
    };

    var TOOLTIP = {
        init: function () {
            var _this = this;
            var $tooltips = $('.js-dgwt-wcas-tooltip');

            if ($tooltips.length > 0) {
                $tooltips.each(function () {
                    var element = $(this)[0];
                    var contentEl = $(this).data('tooltip-html-el');
                    var placement = $(this).data('tooltip-placement');

                    if (contentEl) {
                        const instance = new DgwtWcasTooltip(element, {
                            title: $('.' + contentEl + ' > .dgwt-wcas-tooltip-wrapper')[0],
                            placement: placement,
                            trigger: "hover",
                            html: true
                        });
                    }

                });
            }

        }
    };

    var ADVANCED_SETTINGS = {
        advClass: 'js-dgwt-wcas-adv-settings',
        highlightClass: 'dgwt-wcas-opt-highlight',
        transClass: 'dgwt-wcas-opt-transition',
        init: function () {
            var _this = this;

            _this.clickListener();
            _this.setStartingState();
        },
        clickListener: function () {
            var _this = this;
            $(document).on('click', '.js-dgwt-wcas-settings__advanced', function () {
                var $toggleEl = $('.js-dgwt-wcas-adv-settings-toggle'),
                    choice;

                if ($toggleEl.hasClass('woocommerce-input-toggle--disabled')) {
                    choice = 'show';
                } else {
                    choice = 'hide';
                }

                _this.saveChoice(choice);

            });
        },
        setStartingState: function () {
            var _this = this,
                $options = $('.' + _this.advClass);

            if ($options.length > 0) {
                var showAdvanced = $('.js-dgwt-wcas-adv-settings-toggle').hasClass('woocommerce-input-toggle--enabled');

                if (!showAdvanced) {
                    $options.hide();
                } else {
                    $options.show();
                    CHECKBOX_SETTINGS_TOGGLE.refresh();
                }
            }
        },
        saveChoice: function (choice) {
            var _this = this;

            $('.js-dgwt-wcas-settings__advanced').append('<span class="dgwt-wcas-adv-settings-saving">saving...</span>');

            $.ajax({
                url: ajaxurl,
                data: {
                    _wpnonce: dgwt_wcas.nonces.advanced_options_switch,
                    action: 'dgwt_wcas_adv_settings',
                    adv_settings_value: choice
                }
            }).done(function (data) {
                $('.dgwt-wcas-adv-settings-saving').remove();
            });

            var $el = $('.js-dgwt-wcas-adv-settings-toggle');

            if (choice === 'show') {
                $el.removeClass('woocommerce-input-toggle--disabled');
                $el.addClass('woocommerce-input-toggle--enabled');
            }

            if (choice === 'hide') {
                $el.removeClass('woocommerce-input-toggle--enabled');
                $el.addClass('woocommerce-input-toggle--disabled');
            }

            _this.toggleAdvancedOpt(choice);

        },
        toggleAdvancedOpt: function (action) {
            var _this = this,
                $options = $('.' + _this.advClass);

            if ($options.length > 0) {
                $options.addClass(_this.highlightClass);
                $options.addClass(_this.transClass);

                if (action === 'show') {

                    $options.fadeIn(500, function () {
                        setTimeout(function () {
                            $options.removeClass(_this.highlightClass);

                            setTimeout(function () {
                                $options.removeClass(_this.transClass);
                                CHECKBOX_SETTINGS_TOGGLE.refresh();
                                CONDITIONAL_LAYOUT_SETTINGS.setConditions();
                            }, 500)

                        }, 500);

                    });

                }

                if (action === 'hide') {
                    setTimeout(function () {
                        $options.removeClass(_this.transClass);
                        $options.fadeOut(500, function () {
                            $options.removeClass(_this.highlightClass);
                        });
                    }, 500);
                }
            }

        },
    };

    var STATS_INTERFACE = {
        placeholderClass: 'js-dgwt-wcas-stats-placeholder',
        placeholderClassLoaded: 'js-dgwt-wcas-stats-placeholder-loaded',
        preloaderClass: 'dgwt-wcas-stats-preloader',
        settingsGroupSel: '#dgwt_wcas_analytics',
        criticalSearchesLoadMoreClass: 'js-dgwt-wcas-critical-searches-load-more',
        autocompleteWithResultsLoadMoreClass: 'js-dgwt-wcas-autocomplete-with-results-load-more',
        searchPageWithResultsLoadMoreClass: 'js-dgwt-wcas-search-page-with-results-load-more',
        checkPhraseStatusClass: 'js-dgwt-wcas-stats-critical-check',
        checkPhraseStatusInitClass: 'js-dgwt-wcas-stats-critical-check-init',
        rowLoadingClass: 'dgwt-wcas-analytics-row-loading',
        languageSwitcherClass: 'js-dgwt-wcas-analytics-lang',
        excludePhraseClass: 'js-dgwt-wcas-analytics-exclude-phrase',
        checkIndexerAction: 'js-dgwt-wcas-analytics-check-indexer',
        init: function () {
            var _this = this;

            // Do nothing if the analytics module is disabled
            if (typeof dgwt_wcas.analytics == 'undefined' || !dgwt_wcas.analytics.enabled) {
                return;
            }

            _this.interfaceLoaderListener();
        },
        interfaceLoaderListener: function () {
            var _this = this,
                $languageSelectorEl = $('.' + _this.languageSwitcherClass);

            $(document).on('dgwt_wcas_settings_group_active', function (event, el) {
                if ($(el).length > 0 && el.id === 'dgwt_wcas_analytics') {
                    if (!_this.isLoaded()) {
                        _this.loadInterface();
                    }
                }
            });

            if ($languageSelectorEl.length > 0) {
                $languageSelectorEl.on('change', function () {
                    var $canvas = $('.' + _this.placeholderClass);
                    if ($canvas.length > 0) {
                        $canvas.html('');
                        _this.loadInterface();
                    }
                });
            }

        },
        isLoaded: function () {
            var _this = this;
            return $('.' + _this.placeholderClassLoaded).length > 0;
        },
        showPreloader: function () {
            var _this = this,
                $placeholder = $('.' + _this.placeholderClass),
                html = '<img class="' + _this.preloaderClass + '" src="' + dgwt_wcas.analytics.images.placeholder + '" />';

            if ($placeholder.length) {
                $placeholder.append(html);
            }
        },
        loadInterface: function () {
            var _this = this,
                $lang = $('.' + _this.languageSwitcherClass + ' option:selected');

            _this.showPreloader();

            var data = {
                'action': 'dgwt_wcas_load_stats_interface',
                '_wpnonce': dgwt_wcas.analytics.nonce.analytics_load_interface
            };

            if ($lang.length > 0) {
                data.lang = $lang.val();
            }

            $.post(
                ajaxurl,
                data,
                function (response) {
                    var $el = $('.' + _this.placeholderClass);
                    if (typeof response == 'object' && response.success && $el.length > 0) {
                        $el.addClass(_this.placeholderClassLoaded);
                        $el.html(response.data.html);
                        _this.loadCheckCriticalSearchesListeners();
                        _this.loadMoreListeners();
                    }
                }
            );
        },
        loadCheckCriticalSearchesListeners: function () {
            var _this = this,
                $elements = $('.' + _this.checkPhraseStatusClass + ':not(.' + _this.checkPhraseStatusInitClass + ')');

            // Critical searches - check status
            $elements.on('click', function (e) {
                e.preventDefault();
                $(e.target).after('<img src="' + dgwt_wcas.images.admin_preloader_url + '" />');
                _this.checkPhraseStatus($(e.target));
            })

            $elements.each(function () {
                $(this).addClass(_this.checkPhraseStatusInitClass);
            });

        },
        loadMoreListeners: function () {
            var _this = this;

            // Critical searches - load more
            $('.' + _this.criticalSearchesLoadMoreClass).on('click', function (e) {
                e.preventDefault();
                $(this).before('<img src="' + dgwt_wcas.images.admin_preloader_url + '" />');
                $(this).closest('tr').addClass(_this.rowLoadingClass);
                _this.loadMoreCriticalSearches();
            })

            // Autocomplete with results - load more
            $('.' + _this.autocompleteWithResultsLoadMoreClass).on('click', function (e) {
                e.preventDefault();
                _this.loadMorePhrases('autocomplete', $(e.target));
            })

            // Search page with results - load more
            $('.' + _this.searchPageWithResultsLoadMoreClass).on('click', function (e) {
                e.preventDefault();
                _this.loadMorePhrases('search-page', $(e.target));
            })


        },
        checkPhraseStatus: function ($el) {
            var _this = this,
                $lang = $('.' + _this.languageSwitcherClass + ' option:selected');

            var data = {
                'action': 'dgwt_wcas_check_critical_phrase',
                'phrase': $el.closest('tr').find('td:nth-child(2)').text(),
                '_wpnonce': dgwt_wcas.analytics.nonce.check_critical_phrase
            };

            if ($lang.length > 0) {
                data.lang = $lang.val();
            }

            $.post(
                ajaxurl,
                data,
                function (response) {
                    if (typeof response == 'object' && response.success) {
                        var $row = $el.closest('tr');
                        $el.closest('td').html(response.data.html);

                        var $excludeEl = $row.find('.' + _this.excludePhraseClass);
                        var $checkIndexerEl = $row.find('.' + _this.checkIndexerAction);

                        // Critical searches - exclude phrase
                        if ($excludeEl.length > 0) {
                            $excludeEl.on('click', function (e) {
                                e.preventDefault();
                                var html = '<p>Processing...</p>';
                                $(e.target).closest('td').html(html);
                                _this.excludePhrase($row);
                            })
                        }

                        // Check the indexer status
                        if ($checkIndexerEl.length > 0) {
                            $('.' + _this.checkIndexerAction).on('click', function (e) {
                                e.preventDefault();
                                var $tab = $('#dgwt_wcas_performance-tab');
                                if ($tab.length > 0) {
                                    $tab[0].click();
                                    $([document.documentElement, document.body]).animate({
                                        scrollTop: 0
                                    }, 1000);
                                }
                            })
                        }
                    }
                }
            );
        },
        excludePhrase: function ($el) {
            var _this = this,
                $lang = $('.' + _this.languageSwitcherClass + ' option:selected');

            var data = {
                'action': 'dgwt_wcas_exclude_critical_phrase',
                'phrase': $el.find('td:nth-child(2)').text(),
                '_wpnonce': dgwt_wcas.analytics.nonce.exclude_critical_phrase
            };

            if ($lang.length > 0) {
                data.lang = $lang.val();
            }

            $.post(
                ajaxurl,
                data,
                function (response) {
                    if (typeof response == 'object' && response.success) {
                        $el.addClass('dgwt-wcas-analytics-disable-row');
                        $el.find('td:last-child').html(response.data);
                    }
                }
            );
        },
        loadMoreCriticalSearches: function () {
            var _this = this,
                $lang = $('.' + _this.languageSwitcherClass + ' option:selected');

            var data = {
                'action': 'dgwt_wcas_laod_more_critical_searches',
                'loaded': $('.js-dgwt-wcas-critical-searches-row').length,
                '_wpnonce': dgwt_wcas.analytics.nonce.load_more_critical_searches
            };

            if ($lang.length > 0) {
                data.lang = $lang.val();
            }

            $.post(
                ajaxurl,
                data,
                function (response) {
                    if (typeof response == 'object' && response.success) {
                        var $loadMoreRow = $('.' + _this.criticalSearchesLoadMoreClass).closest('tr');

                        if (response.data.html.length > 0) {
                            $loadMoreRow.before(response.data.html);
                        }

                        if (response.data.more > 0) {
                            $loadMoreRow.removeClass(_this.rowLoadingClass);
                            $loadMoreRow.find('img').remove();
                            $('.' + _this.criticalSearchesLoadMoreClass + ' span:first-child').text(response.data.more_label);
                            _this.loadCheckCriticalSearchesListeners();
                        } else {
                            $loadMoreRow.remove();
                        }
                    }
                }
            );
        },
        loadMorePhrases: function (context, $el) {
            var _this = this,
                $lang = $('.' + _this.languageSwitcherClass + ' option:selected');

            $el.before('<img src="' + dgwt_wcas.images.admin_preloader_url + '" />');
            $el.closest('tr').addClass(_this.rowLoadingClass);

            if (context === 'autocomplete') {
                var data = {
                    'action': 'dgwt_wcas_laod_more_autocomplete',
                    'loaded': $('.js-dgwt-wcas-autocomplete-row').length,
                    '_wpnonce': dgwt_wcas.analytics.nonce.load_more_autocomplete
                };
            } else {
                var data = {
                    'action': 'dgwt_wcas_laod_more_search_page',
                    'loaded': $('.js-dgwt-wcas-search-page-row').length,
                    '_wpnonce': dgwt_wcas.analytics.nonce.load_more_search_page
                };
            }

            if ($lang.length > 0) {
                data.lang = $lang.val();
            }

            $.post(
                ajaxurl,
                data,
                function (response) {
                    if (typeof response == 'object' && response.success) {
                        var $tBody = $el.closest('tbody');
                        $tBody.html(response.data.html);
                    }
                }
            );
        },
    };

    window.DGWT_WCAS_SEARCH_PREVIEW = {
        previewWrapper: {},
        searchWrapp: {},
        suggestionWrapp: {},
        searchInput: {},
        animateTypingInterval: null,
        init: function () {
            var _this = this;

            _this.previewWrapper = $('.js-dgwt-wcas-preview');
            _this.searchWrapp = $('.js-dgwt-wcas-search-wrapp');
            _this.suggestionWrapp = $('.js-dgwt-wcas-suggestions-wrapp');
            _this.detailsWrapp = $('.js-dgwt-wcas-details-wrapp');
            _this.searchInput = $('.js-dgwt-wcas-search-input');

            _this.onChangeHandler();
            _this.onColorHandler();
            _this.onTypeHandler();

            _this.disableSubmit();

            _this.noResultsHandler();
            _this.fixSizesInit();

            _this.keepPreviewVisible();
            _this.animationController();
        },
        isChecked: function ($el) {
            return $el.length > 0 && $el.is(':checked') ? true : false;
        },
        isColor: function (color) {
            return (typeof color === 'string' && color.length === 7 && color.charAt(0) === '#');
        },
        camelCase: function (string) {
            var pieces = string.split('_');
            var camelCase = '';
            for (var i = 0; i < pieces.length; i++) {
                camelCase += pieces[i].charAt(0).toUpperCase() + pieces[i].slice(1);
            }

            return camelCase;
        },
        disableSubmit: function () {
            var timeout,
                $tooltip;

            $('.js-dgwt-wcas-preview-source').on('click', function (e) {
                e.preventDefault();
                var relativeX = e.pageX - 100,
                    relativeY = e.pageY + 10,
                    tooltipHTML = '<div class="dgwt-wcas-click-alert">No interaction! This is only a preview.</div>';

                if (typeof timeout != 'undefined') {
                    clearTimeout(timeout);
                    if ($tooltip) {
                        $tooltip.remove();
                    }
                }

                $('body').append(tooltipHTML);
                $tooltip = $('.dgwt-wcas-click-alert');
                $tooltip.css({left: relativeX, top: relativeY});
                $('.dgwt-wcas-preview-source').addClass('dgwt-wcas-preview-source-no-click');

                timeout = setTimeout(function () {
                    $tooltip.fadeOut(500, function () {
                        $(this).remove();
                        $('.dgwt-wcas-preview-source').removeClass('dgwt-wcas-preview-source-no-click');
                    });
                }, 2000);

            });
        },
        noResultsHandler: function () {
            var _this = this,
                suggestionsTarget = '.js-dgwt-wcas-preview .dgwt-wcas-suggestion:not(.js-dgwt-wcas-suggestion-nores)',
                noresTarget = '.js-dgwt-wcas-suggestion-nores',
                target = "input[id*='search_no_results_text']";

            $(document).on('focus', target, function () {
                $(suggestionsTarget).addClass('dgwt-wcas-hide');
                $(noresTarget).removeClass('dgwt-wcas-hide');
                _this.detailsWrapp.addClass('dgwt-wcas-hide');
                _this.suggestionWrapp.addClass('dgwt-wcas-preview-nores');
            });

            $(document).on('blur', target, function () {
                $(suggestionsTarget).removeClass('dgwt-wcas-hide');
                $(noresTarget).addClass('dgwt-wcas-hide');
                _this.detailsWrapp.removeClass('dgwt-wcas-hide');
                _this.suggestionWrapp.removeClass('dgwt-wcas-preview-nores');
            });

        },
        onChangeHandler: function () {
            var _this = this,
                options = [
                    'show_submit_button',
                    'max_form_width',
                    'search_style',
                    'search_layout',
                    'show_product_image',
                    'show_product_sku',
                    'show_product_desc',
                    'show_product_price',
                    'show_matching_categories',
                    'show_categories_images',
                    'show_matching_tags',
                    'show_matching_brands',
                    'show_brands_images',
                    'show_matching_posts',
                    'show_matching_pages',
                    'show_grouped_results',
                    'suggestions_limit',
                    'show_details_box'
                ];
            for (var i = 0; i < options.length; i++) {
                var tag = ['search_style', 'search_layout'].includes(options[i]) ? 'select' : 'input',
                    selector = tag + "[id='dgwt_wcas_settings\\[" + options[i] + "\\]']",
                    altSelector = tag + "[id^='dgwt_wcas_settings'][data-option-trigger='" + options[i] + "']",
                    $el = $(selector),
                    $altEl = $(altSelector),
                    methodToCall = 'onChange' + _this.camelCase(options[i]);

                if (typeof _this[methodToCall] == 'function' && $el.length > 0) {
                    _this[methodToCall]($el, $el.val());

                    $(document).on('change', selector, function () {
                        methodToCall = $(this).attr('id').replace(']', '').replace('dgwt_wcas_settings[', '');
                        methodToCall = 'onChange' + _this.camelCase(methodToCall);
                        if (typeof (_this[methodToCall]) === 'function') {
                            _this[methodToCall]($(this), this.value);
                        }
                    });
                } else if (typeof _this[methodToCall] == 'function' && $altEl.length > 0) {
                    _this[methodToCall]($altEl, $altEl.val());

                    $(document).on('change', altSelector, function () {
                        methodToCall = $(this).data('option-trigger');
                        methodToCall = 'onChange' + _this.camelCase(methodToCall);
                        if (typeof (_this[methodToCall]) === 'function') {
                            _this[methodToCall]($(this), this.value);
                        }
                    });
                } else {
                    // Fallback for methods related to non-existing elements (eg. brands)
                    _this[methodToCall]('', '');
                }
            }
        },
        onColorHandler: function () {
            var _this = this,
                options = [
                    'search_icon_color',
                    'bg_input_color',
                    'text_input_color',
                    'border_input_color',
                    'bg_submit_color',
                    'text_submit_color',
                    'sug_bg_color',
                    'sug_hover_color',
                    'sug_text_color',
                    'sug_highlight_color',
                    'sug_border_color'
                ];
            for (var i = 0; i < options.length; i++) {
                var selector = "input[id*='" + options[i] + "']";
                var $el = $(selector),
                    methodToCall = 'onColor' + _this.camelCase(options[i]);

                _this[methodToCall]($el, $el.val());

                $(document).on("change", selector, function () {
                    methodToCall = $(this).attr('id').replace(']', '').replace('dgwt_wcas_settings[', '');
                    methodToCall = 'onColor' + _this.camelCase(methodToCall);
                    _this[methodToCall]($(this), this.value);
                });
            }
        },
        onColorChangeHandler: function ($el, value) {
            var _this = this,
                methodToCall = $el.attr('id').replace(']', '').replace('dgwt_wcas_settings[', '');
            methodToCall = 'onColor' + _this.camelCase(methodToCall);
            _this[methodToCall]($el, value);
        },
        onTypeHandler: function () {
            var _this = this,
                options = [
                    'search_submit_text',
                    'search_placeholder',
                    'search_no_results_text',
                    'search_see_all_results_text'
                ];
            for (var i = 0; i < options.length; i++) {
                var selector = "input[id*='" + options[i] + "']";
                var $el = $(selector),
                    methodToCall = 'onType' + _this.camelCase(options[i],);

                _this[methodToCall]($el, $el.val());

                $(document).on("input", selector, function (a) {
                    methodToCall = $(a.target).attr('id').replace(']', '').replace('dgwt_wcas_settings[', '');
                    methodToCall = 'onType' + _this.camelCase(methodToCall);
                    _this[methodToCall]($(a.target), this.value);
                });
            }
        },
        onChangeMaxFormWidth: function ($el, value) {
            var _this = this;

            if (value.length > 0 && value != '0') {
                _this.searchWrapp.css('max-width', value + 'px');
                _this.suggestionWrapp.css('max-width', value + 'px');
            } else {
                _this.searchWrapp.css('max-width', '100%');
                _this.suggestionWrapp.css('max-width', '100%');
            }

            _this.onChangeShowDetailsBox($("input[id*='show_details_box']"));
        },
        onChangeShowSubmitButton: function ($el, value) {
            var _this = this,
                $submit = $('.js-dgwt-wcas-search-submit');

            if (_this.isChecked($el)) {
                _this.searchWrapp.addClass('dgwt-wcas-has-submit');
                _this.searchWrapp.removeClass('dgwt-wcas-no-submit');
                $submit.show();
                $('.dgwt-wcas-sf-wrapp > .dgwt-wcas-ico-magnifier').hide();

                var $textSubmitBgEl = $("input[id*='bg_submit_color']");
                var $textSubmitTextEl = $("input[id*='text_submit_color']");
                _this.onColorBgSubmitColor($textSubmitBgEl, $textSubmitBgEl.val());
                _this.onColorTextSubmitColor($textSubmitTextEl, $textSubmitTextEl.val());

                setTimeout(function () {
                    _this.positionPreloader();
                }, 10);

            } else {
                _this.searchWrapp.addClass('dgwt-wcas-no-submit');
                _this.searchWrapp.removeClass('dgwt-wcas-has-submit');
                $submit.hide();
                $('.dgwt-wcas-sf-wrapp > .dgwt-wcas-ico-magnifier').show();
                _this.positionPreloader();
            }


        },
        onChangeShowProductImage: function ($el, value) {
            var _this = this,
                $imageWrapp = $('.js-dgwt-wcas-si'),
                $contentWrapp = $('.js-dgwt-wcas-content-wrapp');

            if (_this.isChecked($el)) {
                _this.suggestionWrapp.addClass('dgwt-wcas-has-img');

                $('.dgwt-wcas-suggestion-product > .dgwt-wcas-st').remove();
                $('.dgwt-wcas-suggestion-product > .dgwt-wcas-sp').remove();

                $contentWrapp.show();
                $imageWrapp.show();

            } else {
                _this.suggestionWrapp.removeClass('dgwt-wcas-has-img');

                $contentWrapp.each(function () {
                    $(this).closest('.dgwt-wcas-suggestion-product').append($(this).html());
                });

                $contentWrapp.hide();
                $imageWrapp.hide();
            }

        },
        onChangeShowProductSku: function ($el, value) {
            var _this = this,
                $skuWrapp = $('.js-dgwt-wcas-sku');

            if (_this.isChecked($el)) {
                _this.suggestionWrapp.addClass('dgwt-wcas-has-sku');
                $skuWrapp.show();

            } else {
                _this.suggestionWrapp.removeClass('dgwt-wcas-has-sku');
                $skuWrapp.hide();
            }

        },
        onChangeShowProductDesc: function ($el, value) {
            var _this = this,
                $descWrapp = $('.js-dgwt-wcas-sd');

            if (_this.isChecked($el)) {
                _this.suggestionWrapp.addClass('dgwt-wcas-has-desc');
                $descWrapp.show();

            } else {
                _this.suggestionWrapp.removeClass('dgwt-wcas-has-desc');
                $descWrapp.hide();
            }

        },
        onChangeShowProductPrice: function ($el, value) {
            var _this = this,
                $priceWrapp = $('.js-dgwt-wcas-sp');

            if (_this.isChecked($el)) {
                _this.suggestionWrapp.addClass('dgwt-wcas-has-price');
                $priceWrapp.show();

            } else {
                _this.suggestionWrapp.removeClass('dgwt-wcas-has-price');
                $priceWrapp.hide();
            }

        },
        onChangeShowMatchingCategories: function ($el, value) {
            var _this = this,
                $headline = $('.dgwt-wcas-suggestion-headline-cat'),
                $items = $('.dgwt-wcas-suggestion-cat');

            if (_this.isChecked($el)) {
                $headline.show();
                $items.show();
                $items.removeClass('js-dgwt-wcas-suggestion-hidden');

                _this.onChangeShowGroupedResults($("input[id*='show_grouped_results']"));
            } else {
                $headline.hide();
                $items.hide();
                $items.addClass('js-dgwt-wcas-suggestion-hidden');
            }

            var $limitInput = $("input[id*='suggestions_limit']");
            _this.onChangeSuggestionsLimit($limitInput, $limitInput.val());

        },
        onChangeShowCategoriesImages: function ($el, value) {
            var _this = this,
                $contentWrapp = $('.js-dgwt-wcas-suggestion-cat');

            if (_this.isChecked($el)) {
                $contentWrapp.addClass('dgwt-wcas-has-img');
            } else {
                $contentWrapp.removeClass('dgwt-wcas-has-img');
            }
        },
        onChangeShowMatchingTags: function ($el, value) {
            var _this = this,
                $headline = $('.dgwt-wcas-suggestion-headline-tag'),
                $items = $('.dgwt-wcas-suggestion-tag');

            if (_this.isChecked($el)) {
                $headline.show();
                $items.show();
                $items.removeClass('js-dgwt-wcas-suggestion-hidden');

                _this.onChangeShowGroupedResults($("input[id*='show_grouped_results']"));
            } else {
                $headline.hide();
                $items.hide();
                $items.addClass('js-dgwt-wcas-suggestion-hidden');
            }

            var $limitInput = $("input[id*='suggestions_limit']");
            _this.onChangeSuggestionsLimit($limitInput, $limitInput.val());

        },
        onChangeShowMatchingBrands: function ($el, value) {
            var _this = this,
                $headline = $('.dgwt-wcas-suggestion-headline-brand'),
                $items = $('.dgwt-wcas-suggestion-brand');

            if (_this.isChecked($el)) {
                $headline.show();
                $items.show();
                $items.removeClass('js-dgwt-wcas-suggestion-hidden');

                _this.onChangeShowGroupedResults($("input[id*='show_grouped_results']"));
            } else {
                $headline.hide();
                $items.hide();
                $items.addClass('js-dgwt-wcas-suggestion-hidden');
            }

            var $limitInput = $("input[id*='suggestions_limit']");
            _this.onChangeSuggestionsLimit($limitInput, $limitInput.val());

        },
        onChangeShowBrandsImages: function ($el, value) {
            var _this = this,
                $contentWrapp = $('.js-dgwt-wcas-suggestion-brand');

            if (_this.isChecked($el)) {
                $contentWrapp.addClass('dgwt-wcas-has-img');
            } else {
                $contentWrapp.removeClass('dgwt-wcas-has-img');
            }
        },
        onChangeShowMatchingPosts: function ($el, value) {
            var _this = this,
                $headline = $('.dgwt-wcas-suggestion-headline-post'),
                $items = $('.dgwt-wcas-suggestion-post');

            if (_this.isChecked($el)) {
                $headline.show();
                $items.show();
                $items.removeClass('js-dgwt-wcas-suggestion-hidden');

                _this.onChangeShowGroupedResults($("input[id*='show_grouped_results']"));
            } else {
                $headline.hide();
                $items.hide();
                $items.addClass('js-dgwt-wcas-suggestion-hidden');
            }

            var $limitInput = $("input[id*='suggestions_limit']");
            _this.onChangeSuggestionsLimit($limitInput, $limitInput.val());

        },
        onChangeShowMatchingPages: function ($el, value) {
            var _this = this,
                $headline = $('.dgwt-wcas-suggestion-headline-page'),
                $items = $('.dgwt-wcas-suggestion-page');

            if (_this.isChecked($el)) {
                $headline.show();
                $items.show();
                $items.removeClass('js-dgwt-wcas-suggestion-hidden');

                _this.onChangeShowGroupedResults($("input[id*='show_grouped_results']"));
            } else {
                $headline.hide();
                $items.hide();
                $items.addClass('js-dgwt-wcas-suggestion-hidden');
            }

            var $limitInput = $("input[id*='suggestions_limit']");
            _this.onChangeSuggestionsLimit($limitInput, $limitInput.val());

        },
        onChangeShowGroupedResults: function ($el, value) {
            var _this = this,
                $directHeadlines = $('.dgwt-wcas-st--direct-headline'),
                $headlines = $('.dgwt-wcas-suggestion-headline');

            if (_this.isChecked($el)) {
                $directHeadlines.addClass('dgwt-wcas-hidden');

                _this.suggestionWrapp.addClass('dgwt-wcas-has-headings');

                $('.dgwt-wcas-suggestion-headline').show();

                if (!_this.isChecked($("input[data-option-trigger='show_matching_categories']"))) {
                    $('.dgwt-wcas-suggestion-headline-cat').hide();
                }
                if (!_this.isChecked($("input[data-option-trigger='show_matching_tags']"))) {
                    $('.dgwt-wcas-suggestion-headline-tag').hide();
                }
                if (!_this.isChecked($("input[data-option-trigger='show_matching_brands']"))) {
                    $('.dgwt-wcas-suggestion-headline-brand').hide();
                }
                if (!_this.isChecked($("input[id*='show_matching_posts']"))) {
                    $('.dgwt-wcas-suggestion-headline-post').hide();
                }
                if (!_this.isChecked($("input[id*='show_matching_pages']"))) {
                    $('.dgwt-wcas-suggestion-headline-page').hide();
                }

            } else {
                $directHeadlines.removeClass('dgwt-wcas-hidden');
                $headlines.hide();
                _this.suggestionWrapp.removeClass('dgwt-wcas-has-headings');
            }

        },
        onChangeSuggestionsLimit: function ($el, value) {
            setTimeout(function () {
                var _this = this,
                    i = 0,
                    limit = 7,
                    $duplicated = $('.dgwt-wcas-suggestion-duplicated'),
                    types = ['brand', 'cat', 'tag', 'post', 'page', 'product'];

                if (value.length > 0 && value != '0') {
                    limit = Math.abs(value);
                }

                if ($duplicated.length > 0) {
                    $duplicated.remove();
                }

                var prototypes = [];

                for (i = 0; i < types.length; i++) {
                    var prototype = $('.dgwt-wcas-suggestion-' + types[i] + ':not(.js-dgwt-wcas-suggestion-hidden)');
                    if (prototype.length > 0) {
                        prototypes.push(prototype);
                    }
                }

                var total = prototypes.length;

                if (prototypes.length > 0) {

                    var slots = limit - prototypes.length;
                    var lastProtoypeIndex = prototypes.length - 1;

                    while (slots > 0) {

                        var $cloned = prototypes[lastProtoypeIndex].clone();
                        $cloned.addClass('dgwt-wcas-suggestion-duplicated');
                        $cloned.removeClass('dgwt-wcas-suggestion-selected');
                        prototypes[lastProtoypeIndex].after($cloned);
                        total++

                        lastProtoypeIndex--;
                        if (lastProtoypeIndex < 0) {
                            lastProtoypeIndex = prototypes.length - 1;
                        }
                        slots--;
                    }


                }

                if (total > limit) {
                    $el.val(total);
                }
            }, 10);

        },
        onChangeShowDetailsBox: function ($el, value) {
            var _this = this;

            if (_this.isChecked($el)) {
                _this.detailsWrapp.show();
                _this.searchWrapp.addClass('dgwt-wcas-is-detail-box');
                _this.previewWrapper.addClass('dgwt-wcas-is-details');
                _this.previewWrapper.addClass('dgwt-wcas-details-right');


                setTimeout(function () {

                    $('.dgwt-wcas-suggestion-product:not(.dgwt-wcas-suggestion-duplicated)').addClass('dgwt-wcas-suggestion-selected');

                    var $visibleSearchWrapp = $('.js-dgwt-wcas-preview-bar-example');
                    var searchWidth = $visibleSearchWrapp.width();

                    if (searchWidth >= 550) {
                        _this.previewWrapper.addClass('dgwt-wcas-full-width');

                        var realWidth = getComputedStyle($visibleSearchWrapp[0]).width;
                        realWidth = Math.round(parseFloat(realWidth.replace('px', '')));

                        if (realWidth % 2 == 0) {
                            _this.suggestionWrapp.css('width', Math.round(realWidth / 2));
                            _this.detailsWrapp.css('width', Math.round(realWidth / 2));
                        } else {
                            _this.suggestionWrapp.css('width', Math.floor(realWidth / 2));
                            _this.detailsWrapp.css('width', Math.ceil(realWidth / 2));
                        }

                    } else {
                        _this.suggestionWrapp.width(_this.searchWrapp.width());
                    }

                }, 10);


            } else {
                _this.detailsWrapp.hide();
                _this.searchWrapp.removeClass('dgwt-wcas-is-detail-box');
                _this.previewWrapper.removeClass('dgwt-wcas-is-details');
                _this.previewWrapper.removeClass('dgwt-wcas-details-right');
                _this.previewWrapper.removeClass('dgwt-wcas-full-width');
                $('.dgwt-wcas-suggestion-product').removeClass('dgwt-wcas-suggestion-selected');
                _this.suggestionWrapp.css('width', '');
                _this.detailsWrapp.css('width', '');
            }
        },
        onChangeSearchStyle: function ($el, value) {
            var _this = this,
                themes = ['solaris', 'pirx'],
                $inputSubmitButton = $('input[id*="show_submit_button"]'),
                $inputSubmitBgColor = $('label[for*="bg_submit_color"]'),
                i;

            $('.dgwt-wcas-ico-magnifier').addClass('dgwt-wcas-hidden');

            for (i = 0; i < themes.length; i++) {
                $('.js-dgwt-wcas-preview').removeClass('dgwt-wcas-open-' + themes[i]);
                $('.js-dgwt-wcas-search-wrapp').removeClass('dgwt-wcas-style-' + themes[i]);
            }

            $('.js-dgwt-wcas-ico-magnifier-' + value).removeClass('dgwt-wcas-hidden');

            $('.js-dgwt-wcas-preview').addClass('dgwt-wcas-open-' + value);
            $('.js-dgwt-wcas-search-wrapp').addClass('dgwt-wcas-style-' + value);

            if (value === 'solaris') {
                $('label[for*="show_submit_button"] .js-dgwt-wcas-tooltip').addClass('dgwt-wcas-hidden');
                $('label[for*="search_submit_text"]').closest('tr').removeClass('dgwt-wcas-hidden');
                $('input[id*="search_submit_text"]').prop("disabled", false);
                $inputSubmitBgColor.closest('tr').removeClass('dgwt-wcas-hidden');
                $('label[for*="text_submit_color"] > span:nth-child(1)').removeClass('dgwt-wcas-hidden');
                $('label[for*="text_submit_color"] > span:nth-child(2)').addClass('dgwt-wcas-hidden');
                $inputSubmitButton.prop("disabled", false);
                $('.js-dgwt-wcas-submit-button-pirx-tooltip').removeClass('dgwt-wcas-hidden');

                setTimeout(function () {
                    _this.positionPreloader();
                }, 300);
            }

            if (value === 'pirx') {
                $('label[for*="show_submit_button"] .js-dgwt-wcas-tooltip').removeClass('dgwt-wcas-hidden');
                $('label[for*="search_submit_text"]').closest('tr').addClass('dgwt-wcas-hidden');
                $('input[id*="search_submit_text"]').prop("disabled", true);
                $('label[for*="bg_submit_color"]').closest('tr').addClass('dgwt-wcas-hidden');
                $('label[for*="text_submit_color"] > span:nth-child(2)').removeClass('dgwt-wcas-hidden');
                $('label[for*="text_submit_color"] > span:nth-child(1)').addClass('dgwt-wcas-hidden');
                $('input[id*="search_submit_text"]').val('');

                var $cPicker = $inputSubmitBgColor.closest('tr').find('.wp-picker-clear');
                if($cPicker.length > 0){
                    $cPicker[0].click();
                }
                _this.onColorBgSubmitColor();

                if (!$inputSubmitButton.is(':checked')) {
                    $inputSubmitButton[0].click();
                }

                $inputSubmitButton.prop("disabled", true);

                _this.onTypeSearchSubmitText($('input[id*="search_submit_text"]'), '');

                setTimeout(function () {
                    _this.positionPreloader();
                }, 10);

            }
        },
        onChangeSearchLayout: function ($el, value) {
            var _this = this,
                $labels = $('.js-dgwt-wcas-preview-device-info'),
                $barExample = $('.js-dgwt-wcas-preview-bar-example'),
                $iconExample = $('.js-dgwt-wcas-preview-icon-example'),
                i;

            $('.js-dgwt-wcas-preview-device-info > span').addClass('dgwt-wcas-hidden');
            $iconExample.removeClass('dgwt-wcas-preview-icon-only');

            switch (value) {
                case 'classic':
                    $labels.addClass('dgwt-wcas-hidden');
                    $barExample.removeClass('dgwt-wcas-hidden');
                    $iconExample.addClass('dgwt-wcas-hidden');
                    break;
                case 'icon':
                    $labels.addClass('dgwt-wcas-hidden');
                    $barExample.addClass('dgwt-wcas-hidden');
                    $iconExample.removeClass('dgwt-wcas-hidden');
                    $('.dgwt-wcas-search-form').removeClass('dgwt-wcas-hidden');
                    $('.dgwt-wcas-search-icon-arrow').removeClass('dgwt-wcas-hidden');
                    $iconExample.addClass('dgwt-wcas-preview-icon-only');
                    break;
                case 'icon-flexible':
                    $labels.removeClass('dgwt-wcas-hidden');
                    $barExample.removeClass('dgwt-wcas-hidden');
                    $iconExample.removeClass('dgwt-wcas-hidden');
                    $iconExample.find('.dgwt-wcas-search-form').addClass('dgwt-wcas-hidden');
                    $iconExample.find('.dgwt-wcas-search-icon-arrow').addClass('dgwt-wcas-hidden');
                    $('.js-dgwt-wcas-preview-device-info[data-device="desktop"] span:nth-child(2)').removeClass('dgwt-wcas-hidden');
                    $('.js-dgwt-wcas-preview-device-info[data-device="mobile"] span:nth-child(1)').removeClass('dgwt-wcas-hidden');
                    break;
                case 'icon-flexible-inv':
                    $labels.removeClass('dgwt-wcas-hidden');
                    $barExample.removeClass('dgwt-wcas-hidden');
                    $iconExample.removeClass('dgwt-wcas-hidden');
                    $iconExample.find('.dgwt-wcas-search-form').addClass('dgwt-wcas-hidden');
                    $iconExample.find('.dgwt-wcas-search-icon-arrow').addClass('dgwt-wcas-hidden');
                    $('.js-dgwt-wcas-preview-device-info[data-device="desktop"] span:nth-child(1)').removeClass('dgwt-wcas-hidden');
                    $('.js-dgwt-wcas-preview-device-info[data-device="mobile"] span:nth-child(2)').removeClass('dgwt-wcas-hidden');
                    break;
            }
        },
        onColorSearchIconColor: function ($el, value) {
            var _this = this;
            if (_this.isColor(value)) {
                _this.searchWrapp.find('.dgwt-wcas-ico-magnifier-handler path').css('fill', value);
            } else {
                _this.searchWrapp.find('.dgwt-wcas-ico-magnifier-handler path').css('fill', '');
            }
        },
        onColorBgInputColor: function ($el, value) {
            var _this = this;
            if (_this.isColor(value)) {
                _this.searchInput.css('background-color', value);
            } else {
                _this.searchInput.css('background-color', '');
            }
        },
        onColorTextInputColor: function ($el, value) {
            var _this = this,
                styleClass = 'dgwt-wcas-preview-placeholder-style';

            if (_this.isColor(value)) {

                var style = '<style class="' + styleClass + '">';
                style += '.dgwt-wcas-search-input {color:' + value + '!important;}';
                style += '.dgwt-wcas-ico-magnifier path {fill:' + value + '}';
                style += '</style>';

                $('head').append(style);

                _this.searchInput.css('color', value);

            } else {
                _this.searchInput.css('color', '');
                var $styleEl = $('.' + styleClass);
                if ($styleEl.length > 0) {
                    $styleEl.remove();
                }
            }
        },
        onColorBorderInputColor: function ($el, value) {
            var _this = this;
            if (_this.isColor(value)) {
                _this.searchInput.css('border-color', value);
            } else {
                _this.searchInput.css('border-color', '');
            }
        },
        onColorBgSubmitColor: function ($el, value) {
            var _this = this,
                styleClass = 'dgwt-wcas-preview-submit-style',
                submitEnabled = this.isChecked($("input[id*='show_submit_button']"));

            if (submitEnabled && _this.isColor(value)) {

                var style = '<style class="' + styleClass + '">';
                style += '.dgwt-wcas-search-submit::before{border-color: transparent ' + value + '!important;}';
                style += '.dgwt-wcas-search-submit:hover::before{border-right-color: ' + value + '!important;}';
                style += '.dgwt-wcas-search-submit:focus::before{border-right-color: ' + value + '!important;}';
                style += '.dgwt-wcas-search-submit{background-color: ' + value + '!important;}';
                style += '.dgwt-wcas-om-bar .dgwt-wcas-om-return{background-color: ' + value + '!important;}';
                style += '</style>';

                $('head').append(style);

            } else {
                var $styleEl = $('.' + styleClass);
                if ($styleEl.length > 0) {
                    $styleEl.remove();
                }
            }
        },
        onColorTextSubmitColor: function ($el, value) {
            var _this = this,
                submitEnabled = this.isChecked($("input[id*='show_submit_button']"));

            if (submitEnabled && _this.isColor(value)) {

                $('.js-dgwt-wcas-search-submit').css('color', value);
                $('.dgwt-wcas-search-submit .dgwt-wcas-ico-magnifier path').css('fill', value);

            } else {
                _this.searchInput.css('background-color', '');

                $('.js-dgwt-wcas-search-submit').css('color', '');
                $('.dgwt-wcas-search-submit .dgwt-wcas-ico-magnifier path').css('fill', '');
            }
        },
        onColorSugBgColor: function ($el, value) {
            var _this = this,
                styleClass = 'dgwt-wcas-preview-sugbgcol-style';

            if (_this.isColor(value)) {

                var style = '<style class="' + styleClass + '">';
                style += '.dgwt-wcas-suggestions-wrapp,';
                style += '.dgwt-wcas-details-wrapp';
                style += '{background-color: ' + value + '!important;}';
                style += '</style>';

                $('head').append(style);

            } else {
                var $styleEl = $('.' + styleClass);
                if ($styleEl.length > 0) {
                    $styleEl.remove();
                }
            }
        },
        onColorSugHoverColor: function ($el, value) {
            var _this = this;
            if (_this.isColor(value)) {
                setTimeout(function () {
                    $('.dgwt-wcas-suggestion-selected').css('background-color', value);
                }, 50);
            } else {
                $('.dgwt-wcas-suggestion-selected').css('background-color', '');
            }
        },
        onColorSugTextColor: function ($el, value) {
            var _this = this,
                styleClass = 'dgwt-wcas-preview-sugtextcol-style';

            if (_this.isColor(value)) {

                var style = '<style class="' + styleClass + '">';
                style += '.dgwt-wcas-suggestions-wrapp *,';
                style += '.dgwt-wcas-details-wrapp *,';
                style += '.dgwt-wcas-sd,';
                style += '.dgwt-wcas-suggestion *';
                style += '{color: ' + value + '!important;}';
                style += '</style>';

                $('head').append(style);

            } else {
                var $styleEl = $('.' + styleClass);
                if ($styleEl.length > 0) {
                    $styleEl.remove();
                }
            }
        },
        onColorSugHighlightColor: function ($el, value) {
            var _this = this,
                styleClass = 'dgwt-wcas-preview-sughighlight-style';

            if (_this.isColor(value)) {

                var style = '<style class="' + styleClass + '">';
                style += '.dgwt-wcas-st strong,';
                style += '.dgwt-wcas-sd strong';
                style += '{color: ' + value + '!important;}';
                style += '</style>';

                $('head').append(style);

            } else {
                var $styleEl = $('.' + styleClass);
                if ($styleEl.length > 0) {
                    $styleEl.remove();
                }
            }
        },
        onColorSugBorderColor: function ($el, value) {
            var _this = this,
                styleClass = 'dgwt-wcas-preview-sugborder-style';

            if (_this.isColor(value)) {

                var style = '<style class="' + styleClass + '">';
                style += '.dgwt-wcas-suggestions-wrapp,';
                style += '.dgwt-wcas-details-wrapp,';
                style += '.dgwt-wcas-suggestion,';
                style += '.dgwt-wcas-datails-title,';
                style += '.dgwt-wcas-details-more-products';
                style += '{border-color: ' + value + '!important;}';
                style += '</style>';

                $('head').append(style);

            } else {
                var $styleEl = $('.' + styleClass);
                if ($styleEl.length > 0) {
                    $styleEl.remove();
                }
            }
        },
        onTypeSearchSubmitText: function ($el, value) {
            var _this = this,
                $label = $('.js-dgwt-wcas-search-submit-l'),
                $icon = $('.js-dgwt-wcas-search-submit-m');

            if (value.length > 0) {
                $label.text(value);
                $label.show();
                $icon.hide();
            } else {
                $label.text('');
                $label.hide();
                $icon.show();
            }

            _this.positionPreloader();
        },
        onTypeSearchPlaceholder: function ($el, value) {
            var _this = this;
            if (value.length == 0) {
                value = dgwt_wcas.labels.search_placeholder;
            }
            _this.searchInput.attr('placeholder', value);
        },
        onTypeSearchNoResultsText: function ($el, value) {
            if (value.length == 0) {
                value = dgwt_wcas.labels.no_results;
            }
            $('.js-dgwt-wcas-suggestion-nores span').text(value);
        },
        onTypeSearchSeeAllResultsText: function ($el, value) {
            if (value.length == 0) {
                value = dgwt_wcas.labels.show_more;
            }
            $('.js-dgwt-wcas-st-more-label').text(value);
        },
        positionPreloader: function () {
            var $submit = $('.js-dgwt-wcas-search-wrapp:not(.dgwt-wcas-hidden) .js-dgwt-wcas-search-submit'),
                visible = $submit.is(":visible"),
                style = $("select[id*='search_style'] option:selected").val(),
                right = ($submit.width() + 35);

            if (style == 'pirx') {
                right = 38;
            }

            if (!visible && style == 'solaris') {
                right = 7;
            }

            $('.dgwt-wcas-preloader').css('right', right + 'px');
        },
        fixSizesInit: function () {
            var _this = this;

            $(document).on('click', '#dgwt_wcas_autocomplete-tab', function () {
                _this.onChangeShowDetailsBox($("input[id*='show_details_box']"));
            });

        },
        keepPreviewVisible: function () {
            var $movingEl = $('.js-dgwt-wcas-preview-inner'),
                $startEl = $('.js-dgwt-wcas-preview'),
                $endEl = $('.js-dgwt-wcas-preview-source'),
                $boundaryEl = $('.dgwt-eq-settings-form');

            $(window).on('scroll.autocomplete', function () {
                var currentTop = $(document).scrollTop(),
                    topEdge = $startEl[0].getBoundingClientRect().top,
                    breakPoint = topEdge - 40;
                if (breakPoint < 0) {
                    var offset = (-1 * breakPoint) < 1 ? 0 : (-1 * breakPoint),
                        bottomLimit = $endEl.offset().top + $endEl.outerHeight(false),
                        boundaryBottom = Math.floor($boundaryEl.offset().top + $boundaryEl.outerHeight(false)) - 90;

                    if (bottomLimit <= boundaryBottom) {
                        $movingEl.css('top', offset + 'px');
                    } else {
                        if ((currentTop + 40) < $movingEl.offset().top) {
                            var tmp = $movingEl.css('top').replace(/px/i, '') - 10;
                            $movingEl.css('top', tmp + 'px');
                        }
                    }
                } else {
                    $movingEl.css('top', 0);
                }
            });
        },
        animationController: function () {
            var that = this;

            $(window).on('load', function () {
                var $searchBarSettings = $('#dgwt_wcas_form_body-tab');
                if ($searchBarSettings.length && $searchBarSettings.hasClass('nav-tab-active')) {
                    that.startAnimateTyping();
                }
            });

            $('.dgwt_wcas_settings-nav-tab-wrapper > a').on('click', function () {
                if ($(this).attr('id') === 'dgwt_wcas_form_body-tab') {
                    that.stopAnimateTyping();
                    that.startAnimateTyping();
                } else {
                    that.stopAnimateTyping();
                }
            });

            $('.dgwt_wcas_settings-nav-tab-wrapper > a').on('click', function () {
                if ($(this).attr('id') === 'dgwt_wcas_form_body-tab') {
                    that.stopAnimateTyping();
                    that.startAnimateTyping();
                } else {
                    that.stopAnimateTyping();
                }
            });

            $('input[id*="search_placeholder"]').on('focus', function () {
                that.stopAnimateTyping();
                _this.searchInput.val('');
            });

            $('input[id*="search_placeholder"]').on('blur', function () {
                that.startAnimateTyping();
            });

        },
        startAnimateTyping: function () {
            var that = this,
                frame = 0,
                $wrapp = $('.js-dgwt-wcas-search-wrapp'),
                $searchBar = $('.js-dgwt-wcas-search-input'),
                $closeEl = $('.dgwt-wcas-preloader'),
                closeSvg = $('.js-dgwt-wcas-preview-elements-close').html();

            // TODO [refactor] shorten it using a recursive function
            that.animateTypingInterval = setInterval(function () {
                frame++;

                if (frame === 10) {
                    $searchBar.val('f');
                    $wrapp.addClass('dgwt-wcas-search-filled');
                    $wrapp.addClass('dgwt-wcas-search-focused');
                    $closeEl.addClass('dgwt-wcas-close');
                }

                if (frame === 11) {
                    $searchBar.val('fi');
                }
                if (frame === 12) {
                    $searchBar.val('fib');
                    $closeEl.append(closeSvg);
                    that.positionPreloader();
                }
                if (frame === 13) {
                    $searchBar.val('fibo');
                }
                if (frame === 14) {
                    $searchBar.val('fibo ');
                }
                if (frame === 15) {
                    $searchBar.val('fibo s');
                }
                if (frame === 16) {
                    $searchBar.val('fibo se');
                }
                if (frame === 17) {
                    $searchBar.val('fibo sea');
                }
                if (frame === 18) {
                    $searchBar.val('fibo sear');
                }
                if (frame === 19) {
                    $searchBar.val('fibo searc');
                }
                if (frame === 20) {
                    $searchBar.val('fibo search');
                }
                if (frame === 30) {
                    $searchBar.val('fibo searc');
                }
                if (frame === 31) {
                    $searchBar.val('fibo sear');
                }
                if (frame === 32) {
                    $searchBar.val('fibo sea');
                }
                if (frame === 33) {
                    $searchBar.val('fibo se');
                }
                if (frame === 34) {
                    $searchBar.val('fibo s');
                }
                if (frame === 35) {
                    $searchBar.val('fibo ');
                }
                if (frame === 36) {
                    $searchBar.val('fibo');
                }
                if (frame === 37) {
                    $searchBar.val('fib');
                }
                if (frame === 38) {
                    $searchBar.val('fi');
                    $closeEl.removeClass('dgwt-wcas-close');
                }
                if (frame === 39) {
                    $searchBar.val('f');
                }
                if (frame === 40) {
                    $searchBar.val('');
                    $closeEl.html('');
                    $wrapp.removeClass('dgwt-wcas-search-filled');
                }
                if (frame === 45) {
                    frame = 0;
                }
            }, 200);
        },
        stopAnimateTyping() {
            var that = this,
                $wrapp = $('.js-dgwt-wcas-search-wrapp'),
                $searchBar = $('.js-dgwt-wcas-search-input'),
                $closeEl = $('.dgwt-wcas-preloader');

            $closeEl.removeClass('dgwt-wcas-close');
            $searchBar.val('');
            $closeEl.html('');
            $wrapp.removeClass('dgwt-wcas-search-filled');

            clearInterval(that.animateTypingInterval);
        }
    };

    var TROUBLESHOOTING = {
        settingsTab: '#dgwt_wcas_troubleshooting-tab',
        noIssuesClass: '.js-dgwt-wcas-troubleshooting-no-issues',
        counterClass: '.js-dgwt-wcas-troubleshooting-count',
        issuesListClass: '.js-dgwt-wcas-troubleshooting-issues',
        progressBar: '.dgwt-wcas-troubleshooting-wrapper .progress_bar',
        progressBarInner: '.dgwt-wcas-troubleshooting-wrapper .progress-bar-inner',
        resetButtonName: 'dgwt-wcas-reset-async-tests',
        fixOutofstockButtonName: 'dgwt-wcas-fix-out-of-stock-relationships',
        maintenanceAnalyticsButtonName: 'dgwt-wcas-maintenance-analytics',
        switchAlternativeEndpoint: 'dgwt-wcas-switch-alternative-endpoint',
        dismissElementorTemplateButtonName: 'dgwt-wcas-dismiss-elementor-template',
        init: function () {
            var _this = this;
            if (typeof dgwt_wcas['troubleshooting'] === 'undefined') {
                return;
            }

            const count = dgwt_wcas['troubleshooting']['tests']['issues']['critical'] + dgwt_wcas['troubleshooting']['tests']['issues']['recommended'];
            if (count > 0) {
                $(_this.counterClass).text(count).addClass('active');
                $(_this.settingsTab).addClass('enabled');
            }

            if (dgwt_wcas.troubleshooting.tests.results_async.length > 0) {
                $.each(dgwt_wcas.troubleshooting.tests.results_async, function () {
                    _this.appendIssue(this, false);
                });
            }

            if (dgwt_wcas.troubleshooting.tests.direct.length > 0) {
                $.each(dgwt_wcas.troubleshooting.tests.direct, function () {
                    _this.appendIssue(this, false);
                });
            }

            if (dgwt_wcas.troubleshooting.tests.async.length > 0) {
                _this.maybeRunNextAsyncTest();
            }

            $(document).on('click', 'input[name="' + _this.resetButtonName + '"]', function (e) {
                $('input[name="' + _this.resetButtonName + '"]').attr('disabled', 'disabled');
                var data = {
                    'action': 'dgwt_wcas_troubleshooting_reset_async_tests',
                    '_wpnonce': dgwt_wcas.troubleshooting.nonce.troubleshooting_reset_async_tests
                };
                $.post(
                    ajaxurl,
                    data,
                    function () {
                        location.reload();
                    }
                );
                return false;
            });

            $(document).on('click', 'input[name="' + _this.fixOutofstockButtonName + '"]', function (e) {
                $('input[name="' + _this.fixOutofstockButtonName + '"]').attr('disabled', 'disabled').next().addClass('loading');
                var data = {
                    'action': 'dgwt_wcas_troubleshooting_fix_outofstock',
                    '_wpnonce': dgwt_wcas.troubleshooting.nonce.troubleshooting_fix_outofstock
                };
                $.post(
                    ajaxurl,
                    data,
                    function () {
                        location.reload();
                    }
                );
                return false;
            });

            $(document).on('click', 'input[name="' + _this.maintenanceAnalyticsButtonName + '"]', function (e) {
                $('input[name="' + _this.maintenanceAnalyticsButtonName + '"]').attr('disabled', 'disabled').next().addClass('loading');
                var data = {
                    'action': 'dgwt_wcas_troubleshooting_maintenance_analytics',
                    '_wpnonce': dgwt_wcas.troubleshooting.nonce.troubleshooting_maintenance_analytics
                };
                $.post(
                    ajaxurl,
                    data,
                    function () {
                        location.reload();
                    }
                );
                return false;
            });

            $(document).on('click', 'input[name="' + _this.switchAlternativeEndpoint + '"]', function (e) {
                var action = parseInt($(this).data('switch')) === 1 ? 'enable' : 'disable';
                $('input[name="' + _this.switchAlternativeEndpoint + '"]').attr('disabled', 'disabled').next().addClass('loading');
                var data = {
                    'action': 'dgwt_wcas_troubleshooting_switch_alternative_endpoint',
                    '_wpnonce': dgwt_wcas.troubleshooting.nonce.troubleshooting_switch_alternative_endpoint,
                    'switch': action,
                };
                $.post(
                    ajaxurl,
                    data,
                    function () {
                        location.reload();
                    }
                );
                return false;
            });

            $(document).on('click', 'input[name="' + _this.dismissElementorTemplateButtonName + '"]', function (e) {
                $('input[name="' + _this.dismissElementorTemplateButtonName + '"]').attr('disabled', 'disabled');
                var data = {
                    'action': 'dgwt_wcas_troubleshooting_dismiss_elementor_template',
                    '_wpnonce': dgwt_wcas.troubleshooting.nonce.troubleshooting_dismiss_elementor_template
                };
                $.post(
                    ajaxurl,
                    data,
                    function () {
                        location.reload();
                    }
                );
                return false;
            });
        },
        appendIssue: function (issue, incrementCounter) {
            var _this = this;
            var template = wp.template('dgwt-wcas-troubleshooting-issue'),
                issueWrapper = $(_this.issuesListClass + '-' + issue.status),
                count;

            if (issue.status === 'good') {
                return;
            }

            $(_this.noIssuesClass).hide();

            if (incrementCounter) {
                dgwt_wcas.troubleshooting.tests.issues[issue.status]++;
            }

            count = dgwt_wcas.troubleshooting.tests.issues['critical'] + dgwt_wcas.troubleshooting.tests.issues['recommended'];

            if (count > 0) {
                $(_this.counterClass).text(count).addClass('active');
                $(_this.settingsTab).addClass('enabled');
            }

            $(issueWrapper).append(template(issue));
        },
        maybeRunNextAsyncTest: function () {
            var _this = this;

            if (dgwt_wcas.troubleshooting.tests.async.length > 0) {
                $.each(dgwt_wcas.troubleshooting.tests.async, function () {
                    var data = {
                        'action': 'dgwt_wcas_troubleshooting_test',
                        'test': this.test,
                        '_wpnonce': dgwt_wcas.troubleshooting.nonce.troubleshooting_async_test
                    };

                    if (this.completed) {
                        return true;
                    }

                    this.completed = true;

                    $(_this.progressBar).show();

                    $.post(
                        ajaxurl,
                        data,
                        function (response) {
                            if (response.success) {
                                _this.appendIssue(response.data, true)
                            }
                            _this.maybeRunNextAsyncTest();
                        }
                    );

                    return false;
                });
            }

            _this.recalculateProgression();
        },
        recalculateProgression: function () {
            var _this = this;
            var total = dgwt_wcas.troubleshooting.tests.async.length;
            var completed = 0;

            $.each(dgwt_wcas.troubleshooting.tests.async, function () {
                if (this.completed) {
                    completed++;
                }
            });
            var progress = Math.ceil((completed / total) * 100);
            $(_this.progressBarInner).css('width', progress + '%');
            if (progress === 100) {
                setTimeout(function () {
                    $(_this.progressBar).slideUp();
                }, 2000);
            }
        },
    }

    var MOVE_OPTIONS = {
        moveOptionClass: '.js-dgwt-wcas-move-option',
        init: function () {
            var optionsToMove = $(this.moveOptionClass);
            if (optionsToMove.length > 0) {
                $.each(optionsToMove, function (index, el) {
                    var moveDest = $('#' + $(el).data('move-dest').replace(/(:|\.|\[|\])/g, '\\$1'));
                    if (moveDest.length > 0) {
                        if ($(el).closest('tr').hasClass('dgwt-wcas-premium-only')) {
                            $(el).addClass('dgwt-wcas-premium-only');
                        }
                        $(el).clone().appendTo(moveDest.closest('td fieldset'));
                    }
                    $(el).closest('tr').remove();
                });
            }
        }
    }

    var SETTINGS_FILTERS_RULES = {
        init: function () {

            if (typeof Vue == 'undefined') {
                return;
            }

            var productSearchSettings = function ({nonce, options, type}) {
                return {
                    persist: false,
                    maxItems: null,
                    valueField: 'key',
                    labelField: 'label',
                    searchField: ['label'],
                    options: options,
                    preload: true,
                    create: function (input) {
                        return {
                            value: input.key,
                            label: input.label
                        }
                    },
                    load: function (query, callback) {
                        $.ajax({
                            url: ajaxurl,
                            method: 'POST',
                            data: {
                                action: 'dgwt_wcas_settings_search_terms',
                                query: query,
                                type: type,
                                _wpnonce: nonce
                            },
                            error: function () {
                                callback();
                            },
                            success: function (res) {
                                callback(res.data);
                            }
                        });
                    }
                };
            };

            Vue.component('dgwt-wcas-rule', {
                template: '#dgwt-wcas-settings-filters-rules-rule',
                components: {
                    Selectize
                },
                props: ['nonce', 'rule', 'rules', 'index'],
                data() {
                    return {
                        isSelectActive: true,
                    }
                },
                computed: {
                    ruleValue(value) {
                        return this.rule.group;
                    },
                },
                watch: {
                    rule: {
                        handler: function () {
                            this.$emit('update:rule', this.index);
                        },
                        deep: true,
                    },
                    ruleValue() {
                        // Reset values on group change
                        var vm = this;
                        this.$emit('change:group', this.index);
                        this.isSelectActive = false;
                        setTimeout(function () {
                            vm.isSelectActive = true;
                        }, 0);
                    },
                },
                methods: {
                    deleteRule() {
                        this.$emit('delete:rule', this.index)
                    },
                    getSelectizeSettings(type) {
                        var options = (typeof dgwt_wcas_filters_rules_selected_options[type] === 'undefined') ? [] : dgwt_wcas_filters_rules_selected_options[type];
                        return productSearchSettings({nonce: this.nonce, type: type, options: options});
                    },
                },
            });

            var FiltersRules = new Vue({
                el: '#dgwt-wcas-settings-filters-rules',
                components: {
                    Selectize
                },
                data() {
                    return {
                        rules: []
                    }
                },
                mounted() {
                    try {
                        const rules = JSON.parse(this.$refs['dgwt-wcas-settings-filters-rules-ref'].value);
                        $.each(rules, function (index, rule) {
                            rules[index].key = Math.random();
                        });
                        this.rules = rules;
                    } catch (e) {
                    }
                    this.updateInput();
                },
                methods: {
                    addRule() {
                        this.rules.push({group: '', values: [], key: Math.random()});
                        this.updateInput();
                    },
                    changeGroup(index) {
                        this.rules[index].values = [];
                        this.updateInput();
                    },
                    deleteRule(index) {
                        this.rules = this.rules.filter(function (item, itemIndex) {
                            return itemIndex !== index;
                        });
                        this.updateInput();
                    },
                    updateInput() {
                        const rules = JSON.parse(JSON.stringify(this.rules));
                        this.$refs['dgwt-wcas-settings-filters-rules-ref'].value = JSON.stringify(rules.map(function (rule) {
                            if (typeof (rule['key'] !== 'undefined')) {
                                delete (rule['key']);
                            }
                            return rule;
                        }));
                    }
                },
            });
        }
    };

    function automateSettingsColspan() {
        var $el = $('.js-dgwt-wcas-sgs-autocolspan');
        if ($el.length > 0) {
            $el.find('td').attr('colspan', 2);
        }
    }

    function moveOuterBorderOption() {
        var $elToMove = $('.js-dgwt-wcas-settings-margin-nob');

        if ($elToMove.length > 0) {

            $elToMove.each(function () {

                var $wrapp = $(this).find('td .dgwt-wcas-fieldset');

                if ($wrapp.length > 0) {
                    var $parent = $(this).prev('.js-dgwt-wcas-settings-margin');
                    if ($parent.length > 0) {

                        var classList = $(this).attr('class').split(/\s+/);
                        var className = '';

                        $.each(classList, function (index, item) {
                            if (item.indexOf('js-dgwt-wcas-cbtgroup-') !== -1) {
                                className = item;
                            }
                        });
                        var $clone = $wrapp.clone(true, true);
                        $clone.addClass('dgwt-wcas-settings-margin-nob');
                        if (className) {
                            $clone.addClass(className);
                        }
                        $clone.appendTo($parent.find('td'));
                        $(this).remove();
                    }
                }
            });

        }
    }


    $(document).ready(function () {

        moveOuterBorderOption();

        RADIO_SETTINGS_TOGGLE.init();
        CHECKBOX_SETTINGS_TOGGLE.init();
        CONDITIONAL_LAYOUT_SETTINGS.init();

        automateSettingsColspan();

        AJAX_BUILD_INDEX.init();
        SELECTIZE.init();
        TOOLTIP.init();
        ADVANCED_SETTINGS.init();
        TROUBLESHOOTING.init();
        MOVE_OPTIONS.init();
        CHECKBOX_SETTINGS_TOGGLE_SIBLING.init();
        STATS_INTERFACE.init();

        SETTINGS_FILTERS_RULES.init();
        window.DGWT_WCAS_SEARCH_PREVIEW.init();

    });


})(jQuery);

