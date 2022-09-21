<?php

namespace DgoraWcas\Analytics;

use  DgoraWcas\Multilingual ;
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
class UserInterface
{
    const  SECTION_ID = 'dgwt_wcas_analytics' ;
    const  LOAD_INTERFACE_NONCE = 'analytics-load-interface' ;
    const  LOAD_MORE_CRITICAL_SEARCHES_NONCE = 'analytics-load-more-critical-searches' ;
    const  LOAD_MORE_AUTOCOMPLETE_NONCE = 'analytics-load-more-autocomplete' ;
    const  LOAD_MORE_SEARCH_PAGE_NONCE = 'analytics-load-more-search-page' ;
    const  CRITICAL_CHECK_NONCE = 'analytics-critical-check' ;
    const  EXCLUDE_CRITICAL_PHRASE_NONCE = 'analytics-exclude-critical-phrase' ;
    const  CSS_CLASS_PLACEHOLDER = 'js-dgwt-wcas-stats-placeholder' ;
    const  CRITICAL_SEARCHES_LOAD_LIMIT = 10 ;
    const  TABLE_ROW_LIMIT_LIMIT = 10 ;
    /**
     * @var Analytics
     */
    private  $analytics ;
    /**
     * Constructor
     *
     * @param Analytics $analytics
     */
    public function __construct( Analytics $analytics )
    {
        $this->analytics = $analytics;
    }
    
    /**
     * Init the class
     *
     * @return void
     */
    public function init()
    {
        // Draw settings page
        add_filter( 'dgwt/wcas/settings/sections', array( $this, 'addSettingsSection' ) );
        add_filter( 'dgwt/wcas/settings', array( $this, 'addSettingsTab' ) );
        add_filter( 'dgwt/wcas/scripts/admin/localize', array( $this, 'localizeSettings' ) );
        // AJAX callbacks
        add_action( 'wp_ajax_dgwt_wcas_load_stats_interface', array( $this, 'loadInterface' ) );
        add_action( 'wp_ajax_dgwt_wcas_laod_more_critical_searches', array( $this, 'loadMoreCriticalSearches' ) );
        add_action( 'wp_ajax_dgwt_wcas_laod_more_autocomplete', array( $this, 'loadMoreAutocomplete' ) );
        add_action( 'wp_ajax_dgwt_wcas_laod_more_search_page', array( $this, 'loadMoreSearchPage' ) );
        add_action( 'wp_ajax_dgwt_wcas_check_critical_phrase', array( $this, 'checkCriticalPhrase' ) );
        add_action( 'wp_ajax_dgwt_wcas_exclude_critical_phrase', array( $this, 'excludeCriticalPhrase' ) );
        if ( $this->analytics->isModuleEnabled() ) {
            add_action( DGWT_WCAS_SETTINGS_KEY . '-form_end_' . self::SECTION_ID, array( $this, 'tabContent' ) );
        }
    }
    
    /**
     * Content of "Analytics" tab on Settings page
     *
     * @param array $sections
     *
     * @return array
     */
    public function addSettingsSection( $sections )
    {
        $sections[28] = array(
            'id'    => self::SECTION_ID,
            'title' => __( 'Analytics (beta)', 'ajax-search-for-woocommerce' ),
        );
        return $sections;
    }
    
    /**
     * Add "Analytics" tab on Settings page
     *
     * @param array $settings
     *
     * @return array
     */
    public function addSettingsTab( $settings )
    {
        $settings[self::SECTION_ID] = apply_filters( 'dgwt/wcas/settings/section=analytics', array(
            100 => array(
            'name'  => 'analytics_head',
            'label' => __( 'Search Analytics', 'ajax-search-for-woocommerce' ),
            'type'  => 'head',
            'class' => 'dgwt-wcas-sgs-header',
        ),
            110 => array(
            'name'    => 'analytics_enabled',
            'label'   => __( 'Enable search analytics', 'ajax-search-for-woocommerce' ),
            'type'    => 'checkbox',
            'size'    => 'small',
            'default' => 'off',
        ),
        ) );
        return $settings;
    }
    
    /**
     * Pass data to JavaScript on the settings page
     *
     * @param array $localize
     *
     * @return array
     */
    public function localizeSettings( $localize )
    {
        $localize['analytics'] = array(
            'nonce'   => array(
            'analytics_load_interface'    => wp_create_nonce( self::LOAD_INTERFACE_NONCE ),
            'load_more_critical_searches' => wp_create_nonce( self::LOAD_MORE_CRITICAL_SEARCHES_NONCE ),
            'load_more_autocomplete'      => wp_create_nonce( self::LOAD_MORE_AUTOCOMPLETE_NONCE ),
            'load_more_search_page'       => wp_create_nonce( self::LOAD_MORE_SEARCH_PAGE_NONCE ),
            'check_critical_phrase'       => wp_create_nonce( self::CRITICAL_CHECK_NONCE ),
            'exclude_critical_phrase'     => wp_create_nonce( self::EXCLUDE_CRITICAL_PHRASE_NONCE ),
        ),
            'enabled' => $this->analytics->isModuleEnabled(),
            'images'  => array(
            'placeholder' => DGWT_WCAS_URL . 'assets/img/admin-stats-placeholder.png',
        ),
        );
        return $localize;
    }
    
    /**
     * Load content for "Analytics" tab on Settings page
     *
     * @return void
     */
    public function tabContent()
    {
        if ( Multilingual::isMultilingual() ) {
            echo  $this->getLanguageSwitcher() ;
        }
        echo  '<div class="dgwt-wcas-analytics-body ' . self::CSS_CLASS_PLACEHOLDER . '"></div>' ;
    }
    
    /**
     * Get HTML of language switcher
     *
     * @return string
     */
    private function getLanguageSwitcher()
    {
        $vars = array(
            'multilingual' => array(
            'is-multilingual' => true,
            'current-lang'    => Multilingual::getCurrentLanguage(),
            'langs'           => array(),
        ),
        );
        foreach ( Multilingual::getLanguages() as $lang ) {
            $vars['multilingual']['langs'][$lang] = Multilingual::getLanguageField( $lang, 'name' );
        }
        ob_start();
        require DGWT_WCAS_DIR . 'partials/admin/stats/langs.php';
        return ob_get_clean();
    }
    
    /**
     * Load an interface (AJAX callback)
     *
     * @return void
     */
    public function loadInterface()
    {
        if ( !current_user_can( 'administrator' ) ) {
            wp_die( -1, 403 );
        }
        check_ajax_referer( self::LOAD_INTERFACE_NONCE );
        $lang = ( !empty($_REQUEST['lang']) && Multilingual::isLangCode( $_REQUEST['lang'] ) ? sanitize_key( $_REQUEST['lang'] ) : '' );
        $data = array(
            'html' => '',
        );
        ob_start();
        $vars = $this->getVars( $lang );
        require DGWT_WCAS_DIR . 'partials/admin/stats/stats.php';
        $data['html'] = ob_get_clean();
        wp_send_json_success( $data );
    }
    
    /**
     * Load more critical searches
     *
     * @return void
     */
    public function loadMoreCriticalSearches()
    {
        if ( !current_user_can( 'administrator' ) ) {
            wp_die( -1, 403 );
        }
        check_ajax_referer( self::LOAD_MORE_CRITICAL_SEARCHES_NONCE );
        $lang = ( !empty($_REQUEST['lang']) && Multilingual::isLangCode( $_REQUEST['lang'] ) ? sanitize_key( $_REQUEST['lang'] ) : '' );
        $offset = ( !empty($_REQUEST['loaded']) ? absint( $_REQUEST['loaded'] ) : 0 );
        $html = '';
        $data = new Data();
        if ( !empty($lang) ) {
            $data->setLang( $lang );
        }
        $total = $data->getTotalCriticalSearches();
        $critical = $data->getCriticalSearches( self::CRITICAL_SEARCHES_LOAD_LIMIT, $offset );
        
        if ( !empty($critical) ) {
            ob_start();
            $i = $offset + 1;
            foreach ( $critical as $row ) {
                require DGWT_WCAS_DIR . 'partials/admin/stats/critical-searches-row.php';
                $i++;
            }
            $html = ob_get_clean();
        }
        
        $toLoad = $total - $offset - count( $critical );
        $more = min( self::CRITICAL_SEARCHES_LOAD_LIMIT, $toLoad );
        $data = array(
            'html'       => $html,
            'more'       => $more,
            'more_label' => '',
        );
        if ( $more > 0 ) {
            $data['more_label'] = sprintf( _n(
                'load another %d phrase',
                'load another %d phrases',
                $more,
                'ajax-search-for-woocommerce'
            ), $more );
        }
        wp_send_json_success( $data );
    }
    
    /**
     * Load more autocomplete searches with results
     *
     * @return void
     */
    public function loadMoreAutocomplete()
    {
        if ( !current_user_can( 'administrator' ) ) {
            wp_die( -1, 403 );
        }
        check_ajax_referer( self::LOAD_MORE_AUTOCOMPLETE_NONCE );
        $lang = ( !empty($_REQUEST['lang']) && Multilingual::isLangCode( $_REQUEST['lang'] ) ? sanitize_key( $_REQUEST['lang'] ) : '' );
        // Autocomplete
        $data = new Data();
        if ( !empty($lang) ) {
            $data->setLang( $lang );
        }
        $data->setContext( 'autocomplete' );
        $phrases = $data->getPhrasesWithResults( 100 );
        ob_start();
        $i = 1;
        foreach ( $phrases as $row ) {
            require DGWT_WCAS_DIR . 'partials/admin/stats/ac-searches-row.php';
            $i++;
        }
        $html = ob_get_clean();
        $data = array(
            'html' => $html,
        );
        wp_send_json_success( $data );
    }
    
    /**
     * Load more search page searches with results
     *
     * @return void
     */
    public function loadMoreSearchPage()
    {
        if ( !current_user_can( 'administrator' ) ) {
            wp_die( -1, 403 );
        }
        check_ajax_referer( self::LOAD_MORE_SEARCH_PAGE_NONCE );
        $lang = ( !empty($_REQUEST['lang']) && Multilingual::isLangCode( $_REQUEST['lang'] ) ? sanitize_key( $_REQUEST['lang'] ) : '' );
        // Search page
        $data = new Data();
        if ( !empty($lang) ) {
            $data->setLang( $lang );
        }
        $data->setContext( 'search-results-page' );
        $phrases = $data->getPhrasesWithResults( 100 );
        ob_start();
        $i = 1;
        foreach ( $phrases as $row ) {
            require DGWT_WCAS_DIR . 'partials/admin/stats/sp-searches-row.php';
            $i++;
        }
        $html = ob_get_clean();
        $data = array(
            'html' => $html,
        );
        wp_send_json_success( $data );
    }
    
    /**
     * Check if the phrase returns results
     *
     * @return void
     */
    public function checkCriticalPhrase()
    {
        if ( !current_user_can( 'administrator' ) ) {
            wp_die( -1, 403 );
        }
        check_ajax_referer( self::CRITICAL_CHECK_NONCE );
        $data = array(
            'html'   => '',
            'status' => '',
        );
        $phrase = ( !empty($_REQUEST['phrase']) ? $_REQUEST['phrase'] : '' );
        if ( empty($phrase) ) {
            wp_send_json_error( 'empty phrase' );
        }
        
        if ( !dgoraAsfwFs()->is_premium() ) {
            $res = DGWT_WCAS()->nativeSearch->getSearchResults( $phrase, true, 'autocomplete' );
            
            if ( is_array( $res ) && isset( $res['total'] ) ) {
                $total = absint( $res['total'] );
                
                if ( $total > 0 ) {
                    $data['status'] = 'with-results';
                    $data['html'] = $this->getCriticalPhraseMessage( $data['status'], $total );
                } else {
                    $data['status'] = 'without-results';
                    $data['html'] = $this->getCriticalPhraseMessage( $data['status'] );
                }
            
            } else {
                $data['status'] = 'error';
                $data['html'] = $this->getCriticalPhraseMessage( $data['status'] );
            }
        
        } else {
        }
        
        wp_send_json_success( $data );
    }
    
    /**
     * Check critical phrase - messages on response
     *
     * @param string $context
     * @param int $total
     *
     * @return string
     */
    public function getCriticalPhraseMessage( $context = '', $total = 0 )
    {
        $html = '';
        //This phrase returns X products.
        switch ( $context ) {
            case 'with-results':
                $html = '<p>';
                $html .= '<b class="dgwt-wcas-analytics-text-good">' . __( "Perfect!", 'ajax-search-for-woocommerce' ) . '</b>';
                $html .= ' ' . __( "It's sorted.", 'ajax-search-for-woocommerce' );
                $html .= ' ' . sprintf( _n(
                    "This phrase returns 1 product.",
                    "This phrase returns %d products.",
                    $total,
                    'ajax-search-for-woocommerce'
                ), $total );
                $html .= ' ' . __( 'Click the button below to remove this phrase from the list.', 'ajax-search-for-woocommerce' );
                $html .= '<button class="button button-small dgwt-wcas-analytics-btn-mark js-dgwt-wcas-analytics-exclude-phrase"><span class="dashicons dashicons-yes"></span> ' . __( 'Mark this phrase as resolved', 'ajax-search-for-woocommerce' ) . '</button>';
                $html .= '</p>';
                break;
            case 'without-results':
                $html = '<p>';
                $html .= '<b class="dgwt-wcas-analytics-text-poorly">' . __( "Poor!", 'ajax-search-for-woocommerce' ) . '</b>';
                $html .= ' ' . __( "Still this phrase doesn't return any results. Learn how to fix it.", 'ajax-search-for-woocommerce' );
                $html .= '</p>';
                break;
            case 'wrong-index':
                $html = '<p>';
                $html .= __( "Can't check the status. The search index hasn't been completed. Go to the Indexer tab and wait until the search index is completed.", 'ajax-search-for-woocommerce' );
                $html .= '<button class="button button-small dgwt-wcas-analytics-btn-mark js-dgwt-wcas-analytics-check-indexer">' . __( 'Check the indexer status', 'ajax-search-for-woocommerce' ) . '</button>';
                $html .= '</p>';
                break;
            case 'error':
                $html = '<p>';
                $html .= __( 'Something went wrong', 'ajax-search-for-woocommerce' );
                $html .= '</p>';
                break;
        }
        return $html;
    }
    
    /**
     * Unmark a phrase as critical. AJAX callback
     *
     * @return void
     */
    public function excludeCriticalPhrase()
    {
        if ( !current_user_can( 'administrator' ) ) {
            wp_die( -1, 403 );
        }
        check_ajax_referer( self::EXCLUDE_CRITICAL_PHRASE_NONCE );
        $phrase = ( !empty($_REQUEST['phrase']) ? $_REQUEST['phrase'] : '' );
        $lang = ( !empty($_REQUEST['lang']) && Multilingual::isLangCode( $_REQUEST['lang'] ) ? sanitize_key( $_REQUEST['lang'] ) : '' );
        
        if ( !empty($phrase) ) {
            $data = new Data();
            if ( Multilingual::isMultilingual() && !empty($lang) ) {
                $data->setLang( $lang );
            }
            if ( $data->markAsSolved( $phrase ) ) {
                wp_send_json_success( '<p>' . __( 'This phrase has been resolved! This row will disappear after refreshing the page.', 'ajax-search-for-woocommerce' ) . '</p>' );
            }
        }
        
        wp_send_json_error( 'empty phrase' );
    }
    
    /**
     * Prepare vars for the view
     *
     * @param string $lang
     *
     * @return array
     */
    private function getVars( $lang = '' )
    {
        $data = new Data();
        if ( Multilingual::isMultilingual() ) {
            $data->setLang( $lang );
        }
        $mainUrl = 'https://fibosearch.com/lack-of-queries-insight-really-hurts-your-sales/';
        $vars = array(
            'days'                             => $this->getExpirationInDays(),
            'autocomplete'                     => array(),
            'search-page'                      => array(),
            'critical-searches'                => array(),
            'critical-searches-total'          => 0,
            'critical-searches-more'           => 0,
            'returning-results-percent'        => 0,
            'returning-results-percent-poorly' => false,
            'links'                            => array(
            'synonyms' => $mainUrl . '#synonyms',
            'support'  => 'https://fibosearch.com/contact/',
        ),
        );
        // Autocomplete
        $data->setContext( 'autocomplete' );
        $vars['autocomplete'] = array(
            'with-results'               => $data->getPhrasesWithResults( self::TABLE_ROW_LIMIT_LIMIT ),
            'total-with-results-uniq'    => $data->getTotalSearches( true, true ),
            'total-without-results-uniq' => $data->getTotalSearches( false, true ),
            'total-with-results'         => $data->getTotalSearches( true ),
            'total-without-results'      => $data->getTotalSearches( false ),
            'total-results'              => 0,
        );
        $vars['autocomplete']['total-results-uniq'] = $vars['autocomplete']['total-with-results-uniq'] + $vars['autocomplete']['total-without-results-uniq'];
        $vars['autocomplete']['total-results'] = $vars['autocomplete']['total-with-results'] + $vars['autocomplete']['total-without-results'];
        // Search page
        $data->setContext( 'search-results-page' );
        $vars['search-page'] = array(
            'with-results'               => $data->getPhrasesWithResults( self::TABLE_ROW_LIMIT_LIMIT ),
            'total-with-results-uniq'    => $data->getTotalSearches( true, true ),
            'total-without-results-uniq' => $data->getTotalSearches( false, true ),
            'total-with-results'         => $data->getTotalSearches( true ),
            'total-without-results'      => $data->getTotalSearches( false ),
            'total-results'              => 0,
        );
        $vars['search-page']['total-results-uniq'] = $vars['search-page']['total-with-results-uniq'] + $vars['search-page']['total-without-results-uniq'];
        $vars['search-page']['total-results'] = $vars['search-page']['total-with-results'] + $vars['search-page']['total-without-results'];
        // Common
        $vars['total'] = $vars['autocomplete']['total-results'] + $vars['search-page']['total-results'];
        
        if ( $vars['total'] > 0 ) {
            $vars['returning-results-percent'] = round( ($vars['autocomplete']['total-with-results'] + $vars['search-page']['total-with-results']) * 100 / $vars['total'] );
            $vars['returning-results-percent-satisfying'] = $data->isSearchesReturningResutlsSatisfying( $vars['returning-results-percent'] );
        }
        
        // Critical searches
        $critical = $data->getCriticalSearches( self::CRITICAL_SEARCHES_LOAD_LIMIT );
        
        if ( !empty($critical) ) {
            $vars['critical-searches'] = $critical;
            $vars['critical-searches-total'] = $data->getTotalCriticalSearches();
            $toLoad = $vars['critical-searches-total'] - count( $critical );
            $vars['critical-searches-more'] = min( self::CRITICAL_SEARCHES_LOAD_LIMIT, $toLoad );
            if ( $vars['critical-searches-total'] < self::CRITICAL_SEARCHES_LOAD_LIMIT ) {
                $vars['critical-searches-more'] = 0;
            }
        }
        
        return $vars;
    }
    
    /**
     * The records will be removed from the database after passing X days
     *
     * @return int
     */
    public function getExpirationInDays()
    {
        $days = Maintenance::ANALYTICS_EXPIRATION_IN_DAYS;
        if ( defined( 'DGWT_WCAS_ANALYTICS_EXPIRATION_IN_DAYS' ) && intval( DGWT_WCAS_ANALYTICS_EXPIRATION_IN_DAYS ) > 0 ) {
            $days = intval( DGWT_WCAS_ANALYTICS_EXPIRATION_IN_DAYS );
        }
        return $days;
    }

}