
var updateProducts, berocket_set_slider, berocket_product_recount, global_ajax_data, br_reset_all_filters, berocket_filters_first_load;
(function ($){
    $('body').append('<div class="berocket_aapf_widget_loading_preload" style="position:relative; width: 1px; height: 1px;overflow: hidden;">'+the_ajax_script.load_image+'</div>');
    $(document).ready(function (){
        $('.berocket_aapf_widget_loading_preload').remove();
        $(document).on('change', '.br_date_filter', function() {
            var $berocket_aapf_widget = $(this).parents('.berocket_aapf_widget');
            var $date_info = $berocket_aapf_widget.find('.berocket_date_picker');
            var value = $(this).val();
            value = value.replace(/\//g, '');
            if( $(this).is('.br_start_date')) {
                $date_info.data('value_1', $(this).val());
                $date_info.data('value1', value);
            } else {
                $date_info.data('value_2', $(this).val());
                $date_info.data('value2', value);
            }
        });
        var berocket_aapf_widget_product_filters = [],
            berocket_aapf_widget_product_limits = [],
            berocket_aapf_widget_product_price_limit = [],
            berocket_aapf_attribute_data = {name: [], data: [], jquery: false, loaded: false},
            berocket_aapf_child_parent_loaded = true,
            woocommerce_pagination_page = 1,
            berocket_aapf_widget_wait_for_button = false,
            berocket_aapf_widget_selected_filters = [],
            berocket_aapf_reset_info = [],
            berocket_aapf_404_jump = false,
            berocket_aapf_404_jump_fail = false,
            berocket_aapf_widget_first_page_jump = true,
            $berocket_aapf_last_changed,
            berocket_last_ajax_request = null,
            berocket_last_ajax_request_id = 1,
            berocket_child_no_products = '',
            berocket_replace_only_html = false;
        if( $(the_ajax_script.products_holder_id).is('.wf-container') ) {
            berocket_replace_only_html = true;
        }
        var berocket_unselect_all = false;
        the_ajax_script.woocommerce_removes = JSON.parse(the_ajax_script.woocommerce_removes);
        function berocket_fire( func ){
            if ( typeof the_ajax_script.user_func != 'undefined'
                && the_ajax_script.user_func != null
                && typeof func != 'undefined'
                && func.length > 0
            ) {
                try{
                    eval( func );
                } catch(err){
                    alert('You have some incorrect JavaScript code (AJAX Products Filter)');
                }
            }
        }
        function init_styler(){
            setTimeout(function() {
               $('.themed').styler();
            }, 100);
        }
        function update_selected_area() {
            var selected_area_exist = true;
            if ( ! $('.berocket_aapf_widget_selected_area').hasClass('berocket_aapf_widget_selected_area') ) {
                selected_area_exist = false;
                if( ! $('.berocket_aapf_reset_button').hasClass('berocket_aapf_reset_button') ) {
                    return false;
                }
            }
            tmp_html = '';
            prev_label = false;
            el_type = '';
            selected = [];
            berocket_aapf_reset_info = [];
            var select_taxonomies = [];
            if ( typeof berocket_aapf_widget_selected_filters != 'undefined' && berocket_aapf_widget_selected_filters.length > 0 ) {
                $(berocket_aapf_widget_selected_filters).each(function (i, $el) {
                    var elements_data = [];
                    if ($el.is('select')) {
                        taxonomy = $el.data('taxonomy');
                        if( select_taxonomies.indexOf(taxonomy) == -1 ) {
                            select_taxonomies.push(taxonomy);
                            label = $el.parents('ul.berocket_aapf_widget').prev().find('.widget-title').first().text();
                            el_type = 'select';
                            $el.find("option:selected").each(function(i, o) {
                                if( $(o).val() ) {
                                    option = $(o).data('term_name');
                                    el_id = $(o).val();
                                    elements_data.push({el_id:el_id, el_type:el_type, option:option, label:label, taxonomy:taxonomy, el:$el});
                                }
                            });
                        }
                    } else if ($el.is('input')) {
                        taxonomy = $el.data('taxonomy');
                        label = $el.parents('ul.berocket_aapf_widget').prev().find('.widget-title').first().text();
                        option = $el.data('term_name');
                        el_type = $el.attr('type');
                        el_id = $el.parents('li').first().find('label').data('for');
                        if ( $el.data('taxonomy-type') == 'color' ) {
                            el_type = $el.data('taxonomy-type');
                            option = $el.parents('li').first().find('label').html();
                        }
                        elements_data.push({el_id:el_id, el_type:el_type, option:option, label:label, taxonomy:taxonomy, el:$el});
                    } else if ($el.hasClass('berocket_filter_slider') || $el.hasClass('berocket_date_picker')) {
                        taxonomy = $el.data('taxonomy');
                        val1 = $( '#'+$el.data('fields_1') ).val();
                        val2 = $( '#'+$el.data('fields_2') ).val();
                        el_type = 'slider';
                        label = $el.parents('ul.berocket_aapf_widget').prev().find('h3').first().text();
                            if ( $el.hasClass('berocket_date_picker') ) {
                                val1 = $el.data('value_1');
                                val2 = $el.data('value_2');
                                if( typeof(label) == 'undefined' || ! label ) {
                                    label = 'Date';
                                }
                                el_type = '_date';
                            } else if ($el.hasClass('berocket_filter_price_slider')) {
                                if( typeof(label) == 'undefined' || ! label ) {
                                    label = 'Price';
                                }
                            }
                        el_id = $el.data('fields_2');
                        option = val1 + ' - ' + val2;
                        elements_data.push({el_id:el_id, el_type:el_type, option:option, label:label, taxonomy:taxonomy, el:$el});
                    }
                    elements_data.forEach(function(element_data) {
                        var el_id = element_data.el_id;
                        var el_type = element_data.el_type;
                        var option = element_data.option;
                        var label = element_data.label;
                        var taxonomy = element_data.taxonomy;
                        var $el = element_data.el;
                        berocket_aapf_reset_info.push({type:el_type, id:el_id, taxonomy:taxonomy});
                        if( selected_area_exist ) {
                            option = '<li><a href="#Unselect_' + label + '" data-el_type="' + el_type + '" data-el_id="' + el_id + '" data-el_taxonomy="' + taxonomy + '"><i class="fa fa-times"></i> ' + option + '</a></li>';
                            if ( ! $el.hasClass('berocket_filter_slider') || selected.indexOf( taxonomy ) == -1 )
                            if (prev_label === false) {
                                tmp_html += '<div class="berocket_aapf_widget_selected_filter"><span>' + label + '</span><ul>' + option;
                            } else if (prev_label == taxonomy) {
                                tmp_html += option;
                            } else {
                                tmp_html += '</ul></div><div class="berocket_aapf_widget_selected_filter"><span>' + label + '</span><ul>' + option;
                            }
                            prev_label = taxonomy;
                            selected.push( taxonomy );
                        }
                    });
                });
            }
            if ( tmp_html == '' ) {
                $('.berocket_aapf_widget_selected_area').html(tmp_html);
                $('.berocket_aapf_widget_selected_area_hide').parent().hide();
                $('.berocket_aapf_widget_selected_area_text').html(the_ajax_script.translate.nothing_selected);
            } else {
                tmp_html += '</ul></div>';
                tmp_html += '<ul><li><a href="#Unselect_all" class="br_unselect_all"><i class="fa fa-times"></i> '+the_ajax_script.translate.unselect_all+'</a></li></ul>';
                $('.berocket_aapf_widget_selected_area').html(tmp_html);
                $('.berocket_aapf_widget_selected_area_hide').parent().show();
            }
        }
        $(document).on( 'click', '.berocket_aapf_product_count_desc', function( event ) {
            event.preventDefault();
            event.stopPropagation();
        });
        $(document).on( 'click', '.berocket_aapf_product_count_desc .berocket_aapf_close_pc', function( event ) {
            event.preventDefault();
            $(this).parents('.berocket_aapf_product_count_desc').remove();
        });
        function update_data_containers(search_box, search_block) {
            if ( typeof(search_box) == 'undefined' ) {
                search_box = false;
            }
            if ( typeof(search_block) == 'undefined' ) {
                search_block = $(document);
            }
            berocket_aapf_widget_product_filters = [];
            berocket_aapf_widget_selected_filters = [];
            search_block.find('.berocket_aapf_widget li:not(.slider) input, .berocket_aapf_widget li:not(slider) select').each(function (i,o) {
                $el = $(o);
                if ( $el.is("select") ) {
                    el_data = [];
                    $el.find("option:selected").each(function(i, o) {
                        if( $(o).val() ) {
                            el_data.push($(o).data());
                        }
                    });
                    el_data_child_parent_depth = $el.parents('.berocket_aapf_widget').data('child_parent_depth') || 0;
                    el_data.forEach(function(element) {
                        var el_show = true;
                        $(berocket_aapf_widget_product_filters).each(function (i, o) {
                            if (o[0] == element.taxonomy) {
                                if ( o[5] < el_data_child_parent_depth ) {
                                    if ( element.term_id != undefined && element.term_slug != undefined ) {
                                        berocket_aapf_widget_product_filters[i] = [element.taxonomy, element.term_id, element.operator, element.term_slug, element.filter_type, el_data_child_parent_depth];
                                        berocket_aapf_widget_selected_filters[i] = $el;
                                    }
                                    el_show = false;
                                }
                            }
                        });
                        if( el_show && $el.val() ){
                            berocket_aapf_widget_product_filters[berocket_aapf_widget_product_filters.length] = [element.taxonomy, element.term_id, element.operator, element.term_slug, element.filter_type, el_data_child_parent_depth];
                            berocket_aapf_widget_selected_filters[berocket_aapf_widget_selected_filters.length] = $el;
                        }
                    });
                } else if ( $el.is(':checked') || $el.is(':selected') ){
                    el_data = $el.data();
                    el_data_child_parent_depth = $el.parents('.berocket_aapf_widget').data('child_parent_depth') || 0;
                    var el_show = true;
                    remove = 0;
                    $(berocket_aapf_widget_product_filters).each(function (i, o) {
                        if ( o[0] == el_data.taxonomy ) {
                            if ( o[5] < el_data_child_parent_depth ) {
                                berocket_aapf_widget_product_filters.splice( i - remove, 1 );
                                berocket_aapf_widget_selected_filters.splice( i - remove, 1 );
                                remove++;
                            } else if (  o[1] == el_data.term_id ) {
                                el_show = false;
                            }
                        }
                    });
                    if (el_data.term_id != undefined && el_show) {
                        berocket_aapf_widget_product_filters[berocket_aapf_widget_product_filters.length] = [el_data.taxonomy, el_data.term_id, el_data.operator, el_data.term_slug, el_data.filter_type, el_data_child_parent_depth];
                        berocket_aapf_widget_selected_filters[berocket_aapf_widget_selected_filters.length] = $el;
                    }
                }
            });
            berocket_aapf_widget_product_limits = [];
            berocket_aapf_widget_product_price_limit = [];
            $t = search_block.find('.berocket_filter_slider, .berocket_date_picker');
            if( $t.hasClass('berocket_filter_slider') || $t.hasClass('berocket_date_picker') ){
                $t.each(function (i,o){
                    if ( $(o).parents( '.berocket_child_parent_sample' ).length == 0 ) { 
                        var all_terms_name = $(o).data('all_terms_name');
                        var string_slider_edited = true;
                        if( $(o).hasClass('berocket_date_picker') ) {
                            val1 = $(o).data('value1');
                            val2 = $(o).data('value2');
                        } else {
                            val1 = $(o).data('value_1');
                            val2 = $(o).data('value_2');
                        }
                        data_child_parent_depth = $(o).parents('.berocket_aapf_widget').data('child_parent_depth') || 0;
                        var min = $(o).data( 'min' );
                        var max = $(o).data( 'max' );
                        if( $(o).hasClass('berocket_date_picker') ) {
                            min = min.replace(/\//g, '');
                            max = max.replace(/\//g, '');
                        }
                        if( all_terms_name != null ){
                            if( ! ( val1 != all_terms_name[ min ] || val2 != all_terms_name[ max ] ) ) {
                                string_slider_edited = false;
                            }
                            min = all_terms_name[ min ];
                            max = all_terms_name[ max ];
                        }
                        var is_changed = false;
                        if( $(o).hasClass('berocket_date_picker') ) {
                            is_changed = ( val1 != min || val2 !=max );
                        } else {
                            is_changed = ( ( val1 != ( min / $(o).data( 'step' ) ) || val2 != ( max / $(o).data( 'step' ) ) ) && ( string_slider_edited || data_child_parent_depth > 0 ) );
                        }
                        if( is_changed ) {
                            berocket_aapf_widget_selected_filters[berocket_aapf_widget_selected_filters.length] = $(o);
                            if( $(o).hasClass('berocket_filter_price_slider') ){
                                berocket_aapf_widget_product_price_limit = [val1, val2];
                            } else {
                                var add_limit = true;
                                for( i = 0 ; i < berocket_aapf_widget_product_limits.length ; i++ ) {
                                    if( berocket_aapf_widget_product_limits[ i ][ 0 ] == $(o).data( 'taxonomy' ) ) {
                                        if ( berocket_aapf_widget_product_limits[ i ][ 3 ] < data_child_parent_depth ) {
                                            berocket_aapf_widget_product_limits[i] = [$(o).data('taxonomy'), val1, val2, data_child_parent_depth];
                                        }
                                        add_limit = false;
                                    }
                                }
                                if( add_limit ) {
                                    berocket_aapf_widget_product_limits[berocket_aapf_widget_product_limits.length] = [$(o).data('taxonomy'), val1, val2, data_child_parent_depth];
                                }
                            }
                        }
                    }
                });
            }
        }
        get_widget_selected_filters = function() {
            search_box = false;
            search_block = $(document);
            check_berocket_aapf_widget_product_filters = [];
            check_berocket_aapf_widget_selected_filters = [];
            search_block.find('.berocket_aapf_widget li:not(.slider) input, .berocket_aapf_widget li:not(slider) select').each(function (i,o) {
                $el = $(o);
                if ( $el.is("select") ) {
                    if( $el.find("option:selected").length ) {
                        el_data = $el.find("option:selected").data();
                        el_data_child_parent_depth = $el.parents('.berocket_aapf_widget').data('child_parent_depth') || 0;
                        var el_show = true;
                        if( el_show && $el.val() ){
                            check_berocket_aapf_widget_product_filters[check_berocket_aapf_widget_product_filters.length] = [el_data.taxonomy, el_data.term_id, el_data.operator, el_data.term_slug, el_data.filter_type, el_data_child_parent_depth];
                            check_berocket_aapf_widget_selected_filters[check_berocket_aapf_widget_selected_filters.length] = $el;
                        }
                    }
                } else if ( $el.is(':checked') || $el.is(':selected') ){
                    el_data = $el.data();
                    el_data_child_parent_depth = $el.parents('.berocket_aapf_widget').data('child_parent_depth') || 0;
                    var el_show = true;
                    remove = 0;
                    if (el_data.term_id != undefined && el_show) {
                        check_berocket_aapf_widget_product_filters[check_berocket_aapf_widget_product_filters.length] = [el_data.taxonomy, el_data.term_id, el_data.operator, el_data.term_slug, el_data.filter_type, el_data_child_parent_depth];
                        check_berocket_aapf_widget_selected_filters[check_berocket_aapf_widget_selected_filters.length] = $el;
                    }
                }
            });
            $t = search_block.find('.berocket_filter_slider, .berocket_date_picker');
            if( $t.hasClass('berocket_filter_slider') || $t.hasClass('berocket_date_picker') ){
                $t.each(function (i,o){
                    if ( $(o).parents( '.berocket_child_parent_sample' ).length == 0 ) { 
                        var all_terms_name = $(o).data('all_terms_name');
                        var string_slider_edited = true;
                        if( $(o).hasClass('berocket_date_picker') ) {
                            val1 = $(o).data('value1');
                            val2 = $(o).data('value2');
                        } else {
                            val1 = $(o).data('value_1');
                            val2 = $(o).data('value_2');
                        }
                        data_child_parent_depth = $(o).parents('.berocket_aapf_widget').data('child_parent_depth') || 0;
                        var min = $(o).data( 'min' );
                        var max = $(o).data( 'max' );
                        if( $(o).hasClass('berocket_date_picker') ) {
                            min = min.replace(/\//g, '');
                            max = max.replace(/\//g, '');
                        }
                        if( all_terms_name != null ){
                            if( ! ( val1 != all_terms_name[ min ] || val2 != all_terms_name[ max ] ) ) {
                                string_slider_edited = false;
                            }
                            min = all_terms_name[ min ];
                            max = all_terms_name[ max ];
                        }
                        var is_changed = false;
                        if( $(o).hasClass('berocket_date_picker') ) {
                            is_changed = ( val1 != min || val2 !=max );
                        } else {
                            is_changed = ( ( val1 != ( min / $(o).data( 'step' ) ) || val2 != ( max / $(o).data( 'step' ) ) ) && ( string_slider_edited || data_child_parent_depth > 0 ) );
                        }
                        if( is_changed ) {
                            check_berocket_aapf_widget_selected_filters[check_berocket_aapf_widget_selected_filters.length] = $(o);
                        }
                    }
                });
            }
            var reset_info = [];
            if ( typeof check_berocket_aapf_widget_selected_filters != 'undefined' && check_berocket_aapf_widget_selected_filters.length > 0 ) {
                $(check_berocket_aapf_widget_selected_filters).each(function (i, $el) {
                    if ($el.is('select')) {
                        el_type = 'select';
                        $el.find("option:selected").each(function(i, o) {
                            if( $(o).val() ) {
                                el_id = $(o).val();
                                taxonomy = $(o).data('taxonomy');
                                reset_info.push({type:el_type, id:el_id, taxonomy:taxonomy});
                            }
                        });
                    } else if ($el.is('input')) {
                        el_type = $el.attr('type');
                        el_id = $el.parents('li').first().find('label').data('for');
                        if ( $el.data('taxonomy-type') == 'color' ) {
                            el_type = $el.data('taxonomy-type');
                        }
                        reset_info.push({type:el_type, id:el_id});
                    } else if ($el.hasClass('berocket_filter_slider') || $el.hasClass('berocket_date_picker')) {
                        el_type = 'slider';
                        if ( $el.hasClass('berocket_date_picker') ) {
                            el_type = '_date';
                        }
                        el_id = $el.data('fields_2');
                        reset_info.push({type:el_type, id:el_id});
                    }
                });
            }
            return reset_info;
        }
        updateProducts = function ( $force , search_box ){
            $('.berocket_aapf_product_count_desc').remove();
            if ( berocket_unselect_all ) return false;
            if ( the_ajax_script.user_func != null )
                berocket_fire( the_ajax_script.user_func.before_update );
            if ( berocket_aapf_404_jump ) {
                first_page_404 = the_ajax_script.first_page;
                the_ajax_script.first_page = true;
                berocket_aapf_widget_first_page_jump_404 = berocket_aapf_widget_first_page_jump;
                berocket_aapf_widget_first_page_jump = true;
            }
            if ( typeof search_box == 'undefined' ) search_box = false;
            if( ! search_box ) {
                update_data_containers();
            }
            if ( typeof $force == 'undefined' ) $force = false;
            if ( ! $force && berocket_aapf_widget_wait_for_button && ! the_ajax_script.ub_product_count ) return false;
            args = {
                current_language: the_ajax_script.current_language,
                terms: berocket_aapf_widget_product_filters,
                price: berocket_aapf_widget_product_price_limit,
                limits: berocket_aapf_widget_product_limits,
                product_cat: the_ajax_script.product_cat,
                product_taxonomy: the_ajax_script.product_taxonomy,
                s: the_ajax_script.s,
                action: 'berocket_aapf_listener',
                orderby: $('.woocommerce-ordering select.orderby').val(),
                attributes: [],
                cat_limit: [],
            };
            var attribute_selected_count_start = get_widget_selected_filters().length;
            if ( ! $force && berocket_aapf_widget_wait_for_button && the_ajax_script.ub_product_count ) {
                t_args = args;
                t_args.action = 'berocket_aapf_listener_pc';
                t_args.location = the_ajax_script.current_page_url;
                $.post(the_ajax_script.ajaxurl, t_args, function (data) {
                    $('.berocket_aapf_product_count_desc').remove();
                    $widget = $berocket_aapf_last_changed.parents('.berocket_aapf_widget');
                    if( $berocket_aapf_last_changed.is('[type=checkbox], [type=radio]') ) {
                        var toppos = parseInt($berocket_aapf_last_changed.parents('li').first().position().top);
                    } else {
                        var toppos = parseInt($berocket_aapf_last_changed.position().top);
                    }
                    toppos = toppos+parseInt($berocket_aapf_last_changed.parents('.berocket_aapf_widget').first().position().top)-2;
                    if( $berocket_aapf_last_changed.parents('.mCSB_container').length && $berocket_aapf_last_changed.parents('.mCSB_container').first().parents('.berocket_aapf_widget').length ) {
                        toppos = toppos+parseInt($berocket_aapf_last_changed.parents('.mCSB_container').first().position().top);
                    }
                    if ( ( $widget.offset().left + $widget.width() ) < ( $(window).width() / 2 ) ) {
                        var leftpos = $berocket_aapf_last_changed.parents('.berocket_aapf_widget').outerWidth() - $berocket_aapf_last_changed.parents('.berocket_aapf_widget-wrapper').outerWidth();
                        $berocket_aapf_last_changed
                            .parents('.berocket_aapf_widget')
                            .after($('<div class="berocket_aapf_product_count_desc br_aapf_pcd_left"><span></span>'+data.product_count+' '+the_ajax_script.ub_product_text+(the_ajax_script.ub_product_button_text?' <a href="#update" class="berocket_aapf_widget_update_button" >'+the_ajax_script.ub_product_button_text+'</a>':'')+'<a class="berocket_aapf_close_pc" href="#close"><i class="fa fa-times"></i></a></div>').css('top', toppos).css('margin-left', leftpos));
                    } else {
                        var leftpos = -$berocket_aapf_last_changed.parents('.berocket_aapf_widget').position().left;
                        $berocket_aapf_last_changed
                            .parents('.berocket_aapf_widget')
                            .after($('<div class="berocket_aapf_product_count_desc br_aapf_pcd_right"><span></span>'+data.product_count+' '+the_ajax_script.translate.products+(the_ajax_script.ub_product_button_text?' <a href="#update" class="berocket_aapf_widget_update_button" >'+the_ajax_script.ub_product_button_text+'</a>':'')+'<a class="berocket_aapf_close_pc" href="#close"><i class="fa fa-times"></i></a></div>').css('top', toppos).css('margin-right', leftpos));
                    }
                }, 'json');
                return false;
            }
            var berocket_this_ajax_request_id = berocket_last_ajax_request_id = berocket_last_ajax_request_id + 1;
            if ( berocket_last_ajax_request == null ) {
                $(the_ajax_script.products_holder_id).addClass('hide_products').append(the_ajax_script.load_image);
            } else {
                berocket_last_ajax_request.abort();
                berocket_last_ajax_request = null;
            }
            $(document).trigger('berocket_ajax_filtering_start');
            if( the_ajax_script.pos_relative ) {
                $('.berocket_aapf_widget_loading').parent().css('position', 'relative');
            }
            berocket_aapf_widget_loading();
            if( the_ajax_script.seo_friendly_urls && 'history' in window && 'pushState' in history ) {
                updateLocation(args, true);
                args.location = location.href;
            }else{
                args.location = the_ajax_script.current_page_url;
                cur_page = $(the_ajax_script.pagination_class+' .current').first().text();
                var paginate_regex = new RegExp(".+\/"+the_ajax_script.pagination_base+"\/([0-9]+).+", "i");
                if( prev_page = location.href.replace(paginate_regex, "$1") ){
                    if( ! parseInt( cur_page ) ){
                        cur_page = prev_page;
                    }
                    if(berocket_aapf_widget_first_page_jump && the_ajax_script.first_page) {
                        cur_page = 1;
                    }
                    if( $(the_ajax_script.pagination_class+' a').last().length && $(the_ajax_script.pagination_class+' a').last().attr('href').search('product-page=') != -1 ) {
                        args.location = args.location.replace(/\/?/,"") + "/?product-page=" + cur_page + "";
                    } else {
                        args.location = args.location.replace(/\/?/,"") + "/"+the_ajax_script.pagination_base+"/" + cur_page + "/";
                    }
                }else if( prev_page = location.href.replace(/.+paged?=([0-9]+).+/, "$1") ){
                    if( ! parseInt( cur_page ) ){
                        cur_page = prev_page;
                    }
                    if(berocket_aapf_widget_first_page_jump && the_ajax_script.first_page)   {
                        cur_page = 1;
                    }
                    if( $(the_ajax_script.pagination_class+' a').last().length && $(the_ajax_script.pagination_class+' a').last().attr('href').search('product-page=') != -1 ) {
                        args.location = args.location.replace(/\/?/,"") + "/?product-page=" + cur_page + "";
                    } else {
                        args.location = args.location.replace(/\/?/,"") + "/?paged=" + cur_page + "";
                    }
                }
                if( the_ajax_script.seo_friendly_urls ) {
                    uri_request = updateLocation(args, false);
                    location.hash = '';
                    if( parseInt( cur_page ) )
                        location.hash = "paged="+parseInt( cur_page )+"&";
                    if( uri_request != "")
					    uri_request = 'filters=' + uri_request;
                    location.hash += 'filters=('+uri_request+')';
                }
            }
            if( jQuery('.berocket_product_table_compat').length ) {
                berocket_ajax_load_product_table_compat();
            }
            update_selected_area();
            if( the_ajax_script.scroll_shop_top ) {
                var top_scroll_offset = 0;
                if( $( the_ajax_script.products_holder_id ).length ) {
                    top_scroll_offset = $( the_ajax_script.products_holder_id ).offset().top + parseInt(the_ajax_script.scroll_shop_top_px);
                    if(top_scroll_offset < 0) top_scroll_offset = 0;
                }
                $("html, body").animate({ scrollTop: top_scroll_offset }, "slow");
            }
            args.location = updateLocation(args, true, true);
            if( ! $(the_ajax_script.products_holder_id).length ) {
                location.href = args.location;
                return;
            }
            if( the_ajax_script.recount_products ) {
                $('.berocket_aapf_widget').each(function (i,o){
                    args.attributes[i] = $(o).data('attribute');
                    args.cat_limit[i] = $(o).data('cat_limit');
                });
            }
            if(the_ajax_script.ajax_request_load) {
                if( the_ajax_script.ajax_request_load_style == 'jquery' ) {
                    url = args.location;
                    return_type = 'html';
                    t_args = args;
                    args = {};
                } else {
                    if( the_ajax_script.ajax_request_load_style == 'js' ) {
                        return_type = 'html';
                    } else {
                        return_type = 'json';
                    }
                    if( args.location.indexOf('?') > 0 ) {
                        url = args.location+'&explode=explode';
                    } else {
                        url = args.location+'/?explode=explode';
                    }
                    t_args = args;
                    args = {
                        location: t_args.location,
                        attributes: t_args.attributes,
                        cat_limit: t_args.cat_limit,
                    };
                }
            } else {
                return_type = 'json';
                url = the_ajax_script.ajaxurl;
            }
            berocket_aapf_attribute_data.loaded = false;
            var send_type = the_ajax_script.use_request_method;
            var berocket_this_ajax_request = berocket_last_ajax_request = $[send_type](url, args, function (data) {
                global_ajax_data = data;
                berocket_last_ajax_request = null;
                berocket_last_ajax_request_id = 1;
                $('.berocket_aapf_product_count_desc').remove();
                var is_product_table = false;
                if( jQuery('.berocket_product_table_compat').length ) {
                    var tableid = jQuery('.berocket_product_table_compat .wc-product-table').attr('id');
                    if( typeof(window['config_'+tableid]) != 'undefined' && window['config_'+tableid].serverSide ) {
                        is_product_table = true;
                    }
                }
                if( is_product_table ) {
                    $data = $(data);
                    berocket_aapf_attribute_data.jquery = $data;
                    $('.hide_products').removeClass('hide_products');
                } else {
                    if( the_ajax_script.ajax_request_load && the_ajax_script.ajax_request_load_style == 'jquery' ) {
                        $data = $(data);
                        args_ajax = {
                            pagination:$data.find(the_ajax_script.pagination_class).prop('outerHTML'),
                            catalog_ordering:$data.find(the_ajax_script.ordering_class).prop('outerHTML'),
                            result_count:$data.find(the_ajax_script.result_count_class).prop('outerHTML'),
                        }
                        if( $data.find('.woocommerce-info').hasClass('woocommerce-info') && $data.find(the_ajax_script.products_holder_id).length == 0 ) {
                            if( $data.find('.content .woocommerce-info').length > 0 ) {
                                args_ajax.no_products = $data.find('.content .woocommerce-info').prop('outerHTML');
                            } else {
                                args_ajax.no_products = the_ajax_script.no_products;
                            }
                        } else {
                            args_ajax.products = $data.find(the_ajax_script.products_holder_id).prop('outerHTML');
                        }
                        result = afterAjaxLoad(args_ajax);
                        berocket_aapf_attribute_data.jquery = $data;
                    } else {
                        if( the_ajax_script.ajax_request_load && the_ajax_script.ajax_request_load_style == 'js' ) {
                            if( data.indexOf('||EXPLODE||') > 0 ) {
                                products = data.split('||EXPLODE||');
                                products = products[1];
                            } else {
                                products = false;
                            }
                            json_data = JSON.parse( data.split('||JSON||')[1] );
                            data = json_data;
                            if( ! products ) {
                                data.no_products = the_ajax_script.no_products;
                            } else {
                                data.products = products;
                            }
                        }
                        if( ! the_ajax_script.ajax_request_load ) {
                            if( data.products ) {
                                if( jQuery('<div>'+data.products+'</div>').find(the_ajax_script.products_holder_id).length > 0 ) {
                                    data.products = jQuery('<div>'+data.products+'</div>').find(the_ajax_script.products_holder_id).prop('outerHTML');
                                }
                            }
                        }
                        result = afterAjaxLoad(data);
                        berocket_aapf_attribute_data.name = data.attributesname;
                        berocket_aapf_attribute_data.data = data.attributes;
                    }
                }
                berocket_aapf_attribute_data.loaded = true;
                berocket_product_recount();
                berocket_reset_button_hide_not();
                $('.berocket_aapf_widget_loading').remove();
                berocket_aapf_widget_first_page_jump = true;
                berocket_aapf_404_jump_fail = false;
                berocket_aapf_404_jump = false;
                if( attribute_selected_count_start != get_widget_selected_filters().length ) {
					updateProducts();
				} else {
					$(document).trigger('berocket_ajax_products_loaded');
					$(document).trigger('berocket_ajax_filtering_end');
					if( the_ajax_script.user_func != null )
						berocket_fire( the_ajax_script.user_func.after_update );
				}
            }, return_type).fail(function() {
                if ( berocket_last_ajax_request_id == berocket_this_ajax_request_id ) {
                    if( ! berocket_aapf_404_jump_fail ) {
                        berocket_aapf_404_jump = true;
                        berocket_aapf_404_jump_fail = true;
                        updateProducts($force);
                    } else {
                        berocket_aapf_404_jump_fail = false;
                        berocket_aapf_404_jump = false;
                        $(document).trigger('berocket_ajax_filtering_end');
                    }
                } else {
                    $(document).trigger('berocket_ajax_filtering_end');
                }
            });
            if ( berocket_aapf_404_jump ) {
                the_ajax_script.first_page = first_page_404;
                berocket_aapf_widget_first_page_jump = berocket_aapf_widget_first_page_jump_404;
                first_page_404 = false;
            }
        }
        function afterAjaxLoad( args ) {
            result = {
                reset_pagination_event:false,
            }
            if( ! the_ajax_script.woocommerce_removes.result_count ) {
                $(the_ajax_script.result_count_class).html( $(args.result_count).html() || '' );
            }
            if( ! the_ajax_script.woocommerce_removes.ordering ) {
                $(the_ajax_script.ordering_class).html( $(args.catalog_ordering).html() || '' );
            }
            if( ! the_ajax_script.woocommerce_removes.pagination ) {
                $(the_ajax_script.pagination_class).html( $(args.pagination).html() || '' );
            }
            if ( typeof args.products != 'undefined' ) {
                if( $(the_ajax_script.pagination_class).length == 0 && ! the_ajax_script.woocommerce_removes.pagination ) {
                    args.products = args.products+( args.pagination || '' );
                    result.reset_pagination_event = true;
                }
                if( $(the_ajax_script.ordering_class).length == 0 && ! the_ajax_script.woocommerce_removes.ordering ) {
                    args.products = ( args.catalog_ordering || '' )+args.products;
                }
                if( $(the_ajax_script.result_count_class).length == 0 && ! the_ajax_script.woocommerce_removes.result_count ) {
                    args.products = ( args.result_count || '' )+args.products;
                }
            }
            if( the_ajax_script.user_func != null )
                berocket_fire( the_ajax_script.user_func.on_update );
            if ( $('.woocommerce-info').hasClass('woocommerce-info') && ! $(the_ajax_script.products_holder_id).is(':visible') ) {
                if ( typeof args.products != 'undefined' ) {
                    if( berocket_replace_only_html ) {
                        location.reload();
                    } else {
                        $('.woocommerce-info').replaceWith(args.products);
                    }
                }
            } else {
                if ( typeof args.no_products != 'undefined' ) {
                    if( berocket_child_no_products ) {
                        args.no_products = berocket_child_no_products;
                    }
                    if ( $(the_ajax_script.products_holder_id).length > 0 ) {
                        $(the_ajax_script.products_holder_id).html(args.no_products).removeClass('hide_products');
                    } else if ( $('div.woocommerce').length > 0 ) {
                        $('div.woocommerce').html(args.no_products);
                    }
                } else {
                    var $products = $(args.products);
                    if ( $products.length > 0 ) {
                        if ( $(the_ajax_script.products_holder_id).length > 0 ) {
                            if( berocket_replace_only_html ) {
                                $(the_ajax_script.products_holder_id).removeClass('hide_products').html($products.html());
                                if( typeof($(the_ajax_script.products_holder_id).isotope) == 'function' ) {
                                    $(the_ajax_script.products_holder_id).isotope( 'reloadItems' );
                                    $(the_ajax_script.products_holder_id).isotope();
                                }
                                $(the_ajax_script.products_holder_id).find('*').filter(function() {return $(this).css('opacity') == '0';}).css('opacity', 1);
                            } else {
                                $(the_ajax_script.products_holder_id).replaceWith($products);
                            }
                        } else {
                            if ( $('div.woocommerce').length > 0 ) {
                                $('div.woocommerce').html($products.prop('outerHTML'));
                            }
                        }
                    } else {
                        if ( $products.find(the_ajax_script.products_holder_id).length > 0 ) {
                            if ( $(the_ajax_script.products_holder_id).length > 0 ) {
                                $(the_ajax_script.products_holder_id).html($products.find(the_ajax_script.products_holder_id).html()).removeClass('hide_products');
                            } else if ( $('div.woocommerce').length > 0 ) {
                                $('div.woocommerce').html($products.find(the_ajax_script.products_holder_id).prop('outerHTML'));
                            }
                        } else {
                            if ( $(the_ajax_script.products_holder_id).length > 0 ) {
                                $(the_ajax_script.products_holder_id).html('').removeClass('hide_products');
                            } else if ( $('div.woocommerce').length > 0 ) {
                                $('div.woocommerce').html('');
                            }
                        }
                    }
                }
            }
            return result;
        }
        berocket_new_product_recount = function ($data) {
            $is_berocket_wc_shortcode_fix = $data.is('.berocket_wc_shortcode_fix');
            $new_widgets_list = [];
            $data.find('.berocket_single_filter_widget').each(function (i,o) {
                var showbutton = $('.berocket_single_filter_widget.berocket_single_filter_widget_'+$(o).data('id')).data('showbutton');
                var $thistitle = $(o).find('.berocket_aapf_widget-title_div');
                var widget_block = $thistitle.next('.berocket_aapf_widget');
                if(showbutton == 'hide') {
                    widget_block.slideDown(0, function(){widget_block.find('.mCSB_container').css('width', '');});
                    $thistitle.find('span').removeClass('show_button').addClass('hide_button');
                } else if( showbutton == 'show' ) {
                    widget_block.slideUp(0);
                    $thistitle.find('span').removeClass('hide_button').addClass('show_button');
                }
                var show_child_button = $('.berocket_single_filter_widget.berocket_single_filter_widget_'+$(o).data('id')).find('.br_child_toggle .fa-minus');
                if( show_child_button.length ) {
                    show_child_button.each(function() {
                        hide_child_attributes($(o).find('.br_child_toggle[data-term_id='+$(this).parents('.br_child_toggle').data('term_id')+']'));
                    });
                }
                $html = $(o).html();
                if( $is_berocket_wc_shortcode_fix ) {
                    $(o).html('');
                    $('.berocket_single_filter_widget.berocket_single_filter_widget_'+$(o).data('id')).not('.berocket_wc_shortcode_fix .berocket_single_filter_widget').html($html);
                } else {
                    $('.berocket_single_filter_widget.berocket_single_filter_widget_'+$(o).data('id')).html($html);
                }
            });
            $('.berocket_single_filter_widget').each(function(i, o) {
                var showbutton = $(o).data('showbutton');
                var $thistitle = $(o).find('.berocket_aapf_widget-title_div');
                var widget_block = $thistitle.next('.berocket_aapf_widget');
                if(showbutton == 'hide')
                {
                    widget_block.parents('.berocket_single_filter_widget').first().removeClass('berocket_single_filter_hidden').addClass('berocket_single_filter_visible');
                    widget_block.slideDown(100, function(){widget_block.find('.mCSB_container').css('width', '');});
                    $thistitle.find('span').removeClass('show_button').addClass('hide_button');
                    $thistitle.parents('.berocket_single_filter_widget').data('showbutton', 'hide');
                }
                else if( showbutton == 'show' )
                {
                    widget_block.parents('.berocket_single_filter_widget').first().removeClass('berocket_single_filter_visible').addClass('berocket_single_filter_hidden');
                    widget_block.slideUp(100);
                    $thistitle.find('span').removeClass('hide_button').addClass('show_button');
                    $thistitle.parents('.berocket_single_filter_widget').data('showbutton', 'show');
                }
                berocket_widget_show_values_text($(this));
            });
            if( ! $('.berocket_single_filter_widget').length ) {
                $data.find('.berocket_aapf_widget').each(function (i,o) {
                    $widget_id = $(o).data('widget_id');
                    $new_widgets_list[ $new_widgets_list.length ] = $widget_id;
                    if ( $(o).parents('.berocket_search_box_block').hasClass('berocket_search_box_block') ) {
                        var $html = $(o).parents('.widget.widget_berocket_aapf, .widget.widget_berocket_aapf_single').html();
                        $(o).parents('.widget.widget_berocket_aapf, .widget.widget_berocket_aapf_single').html('');
                        $('#'+$widget_id).html($html).show(0);
                    } else {
                        if( $(o).has('.mCSB_container').length) {
                            var $html = $(o).find('.mCSB_container').html();
                            $(o).find('.mCSB_container').html('');
                        } else {
                            var $html = $(o).html();
                            $(o).html('');
                        }
                        if( $('[data-widget_id="'+$widget_id+'"]').has('.mCSB_container').length ) {
                            $('[data-widget_id="'+$widget_id+'"]').find('.mCSB_container').html($html).parents('.widget.widget_berocket_aapf, .widget.widget_berocket_aapf_single').show(0);
                        } else {
                            $('[data-widget_id="'+$widget_id+'"]').html($html).parents('.widget.widget_berocket_aapf, .widget.widget_berocket_aapf_single').show(0);
                        }
                    }
                });
            }
            $( ".berocket_filter_slider" ).each(function (i,o){
                berocket_set_slider ( i, o );
            });
            $(".berocket_aapf_widget_height_control").each(function (i,o){
                if( ! $(o).hasClass('br_height_controled') ) {
                    $(o).css('height', $(o).height()).mCustomScrollbar({
                        axis: "y",
                        theme: $(o).data('scroll_theme'),
                        scrollInertia: 300
                    }).addClass('br_height_controled');
                    berocket_custom_scroll_bar_init();
                }
            });
            $('body').find('.berocket_aapf_widget').each(function (i,o) {
                if( $(o).parents('.berocket_single_filter_widget').length == 0 ) {
                    $remove_it = true;
                    $($new_widgets_list).each(function (ii,oo){
                        if ( $(o).data('widget_id') == oo ) {
                            $remove_it = false;
                        }
                    });
                    if ( $remove_it ) {
                        $('#'+$(o).data('widget_id')).hide(0);
                    }
                }
            });
            if( the_ajax_script.use_select2 && $(".berocket_aapf_widget select").length && typeof $(".berocket_aapf_widget select").select2 == 'function' ) {
                $(".select2-container--open").remove();
                $(".berocket_aapf_widget select").select2({width:'100%'});
            }
        }
        berocket_product_recount = function () {
            if( berocket_aapf_attribute_data.loaded && berocket_aapf_child_parent_loaded ) {
                jQuery('.berocket_aapf_widget:not(.berocket_aapf_widget_selected_area)').each(function(i, o) {
                    jQuery(o).parents('.widget.widget_berocket_aapf, .widget.widget_berocket_aapf_single').show();
                });
                if ( the_ajax_script.ajax_request_load_style == 'jquery' && the_ajax_script.ajax_request_load ) {
                    var $data = berocket_aapf_attribute_data.jquery;
                    if( the_ajax_script.recount_products ) {
                        berocket_new_product_recount($data);
                    }
                    berocket_aapf_attribute_data.jquery = false;
                    berocket_aapf_attribute_data.loaded = false;
                } else {
                    var data = {attributesname: berocket_aapf_attribute_data.name, attributes: berocket_aapf_attribute_data.data};
                    if( the_ajax_script.recount_products ) {
                        $('.berocket_aapf_widget').each(function (i,o){
                            if( $(o).data('type') == 'color' || $(o).data('type') == 'checkbox' || $(o).data('type') == 'radio'  || $(o).data('type') == 'select' || $(o).data('type') == 'image' || $(o).data('type') == 'ranges' ) {
                                attribute_pos = data.attributesname.indexOf($(o).data('attribute'));
                                if( attribute_pos >= 0 && typeof(data.attributes[attribute_pos]) != 'undefinded' && data.attributes[attribute_pos] != null ) {
                                    if( $(o).data('type') == 'color' || $(o).data('type') == 'checkbox' || $(o).data('type') == 'image' ) {
                                        data.attributes[attribute_pos].forEach( function(element, index, array) {
                                            $(o).find('.checkbox_'+element.term_id+element.taxonomy).each(function( i2, o2 ) {
                                                $(o2).data('term_count', element.count).parents('li').first().find('.berocket_aapf_count').text(element.count);
                                                hide_show_o_value($(o2), element.count);
                                            });
                                        });
                                    } else if( $(o).data('type') == 'ranges' ) {
                                        data.attributes[attribute_pos].forEach( function(element, index, array) {
                                            $(o).find('.checkbox_'+element.term_ranges).each(function( i2, o2 ) {
                                                $(o2).parents('li').first().find('.berocket_aapf_count').text(element.count);
                                                hide_show_o_value($(o2), element.count);
                                            });
                                        });
                                    } else if( $(o).data('type') == 'radio' ) {
                                        data.attributes[attribute_pos].forEach( function(element, index, array) {
                                            $(o).find('.radio_'+element.term_id+'_'+element.taxonomy).each(function( i2, o2 ) {
                                                $(o2).parents('li').first().find('.berocket_aapf_count').text(element.count);
                                                hide_show_o_value($(o2), element.count);
                                            });
                                        });
                                    } else if( $(o).data('type') == 'select' ) {
                                        data.attributes[attribute_pos].forEach( function(element, index, array) {
                                            $(o).find('.select_'+element.term_id).text($(o).find('.select_'+element.term_id).data('term_name')+' ('+element.count+')');
                                            hide_show_o_value_select( $('.select_'+$(o).data('term_id')), element.count );
                                        });
                                    }
                                }
                            }
                        });
                    }
                    berocket_aapf_attribute_data.loaded = false;
                    berocket_aapf_attribute_data.name = [];
                    berocket_aapf_attribute_data.data = [];
                }
            }
            if( the_ajax_script.hide_empty_value ) {
                jQuery('.berocket_aapf_widget:not(.berocket_aapf_widget_selected_area)').each(function(i, o) {
                    if( jQuery(o).parents('.berocket_search_box_block').parents('.berocket_search_box_block').length == 0 && jQuery(o).parents('.br_child_parent_wrapper').length == 0 ) {
                        if( jQuery(o).find('li').not('.berocket_widget_show_values').length == jQuery(o).find('li.berocket_hide_o_value').length ) {
                            jQuery(o).parents('.berocket_aapf_widget-wrapper').addClass('berocket_hiden_without_visible_values').hide().parents('.widget.widget_berocket_aapf, .widget.widget_berocket_aapf_single').hide();
                        }
                    }
                });
            }
            berocket_reset_button_hide_not();
            update_data_containers();
            update_selected_area();
        }
        function hide_show_o_value($block, count) {
            if( the_ajax_script.hide_o_value ) {
                if( count == 0 ) {
                    $block.each(function(i, o) {
                        $(o).parents('li').first().addClass('berocket_hide_o_value');
                    });
                } else {
                    $block.each(function(i, o) {
                        $(o).parents('li').first().removeClass('berocket_hide_o_value');
                    });
                }
                var $parent = $block.parents('ul.berocket_aapf_widget');
                $parent.each( function ( i, o ) {
                    if( the_ajax_script.hide_o_value && $(o).find('.berocket_hide_o_value').length > 0 || 
                    the_ajax_script.hide_sel_value && $(o).find('.berocket_hide_sel_value').length > 0 ) {
                        $(o).find('.berocket_widget_show_values').show();
                    } else {
                        $(o).find('.berocket_widget_show_values').hide();
                    }
                });
            }
        }
        function hide_show_o_value_select($block, count) {
            if( the_ajax_script.hide_o_value ) {
                if( count == 0 ) {
                    $block.each(function(i, o) {
                        $(o).addClass('berocket_hide_o_value').prop('hidden', true).prop('disabled', true);
                    });
                } else {
                    $block.each(function(i, o) {
                        $(o).removeClass('berocket_hide_o_value').prop('hidden', false).prop('disabled', false);
                    });
                }
            }
        }
        function updateLocation( args, pushstate, return_request ){
            if ( typeof return_request == 'undefined' ) return_request = false;
            uri_request_array = [];
            var uri_request = '';
            temp_terms = [];
            var taxonomy_sparator = "|", start_terms = "[", end_terms = "]", variable = 'filters';
            if (typeof the_ajax_script.nn_url_variable != "undefined" && the_ajax_script.nn_url_variable.length > 0) {
                variable = the_ajax_script.nn_url_variable;
            }
            if (typeof the_ajax_script.nn_url_value_1 != "undefined" && the_ajax_script.nn_url_value_1.length > 0) {
                start_terms = the_ajax_script.nn_url_value_1;
                end_terms = the_ajax_script.nn_url_value_2;
            }
            if (typeof the_ajax_script.nn_url_split != "undefined" && the_ajax_script.nn_url_split.length > 0) {
                taxonomy_sparator = the_ajax_script.nn_url_split;
            }
            if( the_ajax_script.nice_urls ) {
                taxonomy_sparator = the_ajax_script.nice_url_split;
                start_terms = the_ajax_script.nice_url_value_1;
                end_terms = the_ajax_script.nice_url_value_2;
                variable = the_ajax_script.nice_url_variable;
            }
            if( args.price ){
                $price_obj = $('.berocket_filter_price_slider');
                if( ( args.price[0] || args.price[0] === 0 ) && ( args.price[1] || args.price[1] === 0 ) && ( args.price[0] != $price_obj.data('min') || args.price[1] != $price_obj.data('max') ) ){
                    if( uri_request ) uri_request += taxonomy_sparator;
                    uri_request += 'price'+start_terms+args.price[0]+'_'+args.price[1]+end_terms;
                }
            }
            if( args.limits ){
                $(args.limits).each(function (i,o){
                    if( o[0].substring(0, 3) == 'pa_' ) {
                        if( !in_array( o[0].substring(3), temp_terms ) ){
                            temp_terms[temp_terms.length] = o[0].substring(3);
                        }
                        if( typeof uri_request_array[in_array( o[0].substring(3), temp_terms )] == 'undefined' ) {
                            uri_request_array[in_array(o[0].substring(3), temp_terms)] = [];
                        }
                        uri_request_array[in_array( o[0].substring(3), temp_terms )]
                            [uri_request_array[in_array( o[0].substring(3), temp_terms )].length] = [o[1],o[2]];
                    } else {
                        if( !in_array( o[0], temp_terms ) ){
                            temp_terms[temp_terms.length] = o[0];
                        }
                        if( typeof uri_request_array[in_array( o[0], temp_terms )] == 'undefined' ) {
                            uri_request_array[in_array(o[0], temp_terms)] = [];
                        }
                        uri_request_array[in_array( o[0], temp_terms )]
                            [uri_request_array[in_array( o[0], temp_terms )].length] = [o[1],o[2]];
                    }
                });
            }
            if( args.terms ){
                $(args.terms).each(function (i,o){
                    if ( the_ajax_script.slug_urls ) {
                        o[1] = o[3];
                    }
                    if( o[0].substring(0, 3) == 'pa_' ) {
                        if( !in_array( o[0].substring(3), temp_terms ) ){
                            temp_terms[temp_terms.length] = o[0].substring(3);
                        }
                        if( typeof uri_request_array[in_array( o[0].substring(3), temp_terms )] == 'undefined' ) {
                            uri_request_array[in_array(o[0].substring(3), temp_terms)] = [];
                        }
                        uri_request_array[in_array( o[0].substring(3), temp_terms )]
                            [uri_request_array[in_array( o[0].substring(3), temp_terms )].length] = [o[1],o[2]];
                    } else {
                        if( !in_array( o[0], temp_terms ) ){
                            temp_terms[temp_terms.length] = o[0];
                        }
                        if( typeof uri_request_array[in_array( o[0], temp_terms )] == 'undefined' ) {
                            uri_request_array[in_array(o[0], temp_terms)] = [];
                        }
                        uri_request_array[in_array( o[0], temp_terms )]
                            [uri_request_array[in_array( o[0], temp_terms )].length] = [o[1],o[2]];
                    }
                });
            }
            if( uri_request_array.length ) {
                $(uri_request_array).each(function (i,o){
                    if( uri_request ) uri_request += taxonomy_sparator;
                    if( typeof o != 'object' ){
                        if( the_ajax_script.seo_uri_decode ) {
                            uri_request += encodeURIComponent( o );
                        } else {
                            uri_request += o;
                        }
                    }else{
                        cnt_oo = false;
                        if( the_ajax_script.seo_uri_decode ) {
                            uri_request += encodeURIComponent( temp_terms[i] )+start_terms;
                        } else {
                            uri_request += temp_terms[i]+start_terms;
                        }
                        $(o).each(function (ii,oo){
                            if( ( oo[1] == 'AND' || oo[1] == 'OR' ) ){
                                if( cnt_oo ){
                                    if(oo[1] == 'AND'){
                                        if( the_ajax_script.seo_uri_decode ) {
                                            uri_request += encodeURIComponent('+');
                                        } else {
                                            uri_request += '+';
                                        }
                                    }else{
                                        if( the_ajax_script.seo_uri_decode ) {
                                            uri_request += encodeURIComponent('-');
                                        } else {
                                            uri_request += '-';
                                        }
                                    }
                                }
                            }else{
                                oo[0] += '_'+oo[1];
                            }
                            if( the_ajax_script.seo_uri_decode ) {
                                uri_request += encodeURIComponent(oo[0]);
                            } else {
                                uri_request += oo[0];
                            }
                            cnt_oo = true;
                        });
                        uri_request += end_terms;
                    }
                });
            }
            uri_request = uri_request;
            if( !pushstate ) {
                return uri_request;
            }
            if ( the_ajax_script.trailing_slash ) {
                if( the_ajax_script.nice_urls && uri_request && uri_request.slice(-1) != '/' ) {
                    uri_request += '/';
                }
            }
            var uri = the_ajax_script.current_page_url;
            if ( /\?/.test(uri) ) {
                passed_vars1 = uri.split('?');
                uri = passed_vars1[0];
            }
            if( uri && uri.slice(-1) != '/' && ( the_ajax_script.trailing_slash ) ) {
                uri += '/';
            }
            var cur_page = $(the_ajax_script.pagination_class).find('.current').first().text();
            var paginate_regex = new RegExp(".+\/"+the_ajax_script.pagination_base+"\/([0-9]+).+", "i");
            if( prev_page = parseInt( location.href.replace(paginate_regex, "$1") ) ) {
                if( ! parseInt( cur_page ) ){
                    cur_page = prev_page;
                }
            }
            if(berocket_aapf_widget_first_page_jump && the_ajax_script.first_page)   {
                cur_page = 1;
            }
            cur_page = parseInt( cur_page );
            var additional_datas = '';
            something_added = false;
            if( /\?/.test(location.href) ){
                passed_vars1 = location.href;
                if ( /\#/.test(passed_vars1) ) {
                    passed_vars1 = passed_vars1.split('#');
                    passed_vars1 = passed_vars1[0];
                }
                passed_vars1 = passed_vars1.split('?');
                if( passed_vars1[1] ){
                    passed_vars2 = [];
                    if( /&/.test(passed_vars1[1]) ) {
                        passed_vars2 = passed_vars1[1].split('&');
                    } else {
                        passed_vars2[0] = passed_vars1[1];
                    }
                    passed_vars2_length = passed_vars2.length;
                    for ( k = 0; k < passed_vars2.length; k++ ) {
                        temp = passed_vars2[k].split('=');
                        passed_vars2[k] = [];
                        passed_vars2[k][0] = temp.shift();
                        passed_vars2[k][1] = temp.join("=");
                        if( passed_vars2[k][0] == variable || passed_vars2[k][0] == 'page'  || passed_vars2[k][0] == 'paged' || passed_vars2[k][0] == 'product-page' ) continue;
                        if( the_ajax_script.control_sorting && passed_vars2[k][0] == 'orderby' ) continue;
                        if( something_added ) {
                            additional_datas += '&';
                        } else {
                            additional_datas += '?';
                        }
                        additional_datas += passed_vars2[k][0]+'='+passed_vars2[k][1];
                        something_added = true;
                    }
                }
            }
            var next_symbol_sep = '?';
            if( the_ajax_script.nice_urls ) {
                if( uri_request ) {
                    if( uri.slice(-1) != '/' ) {
                        uri += '/';
                    }
                    uri = uri + variable + "/" + uri_request;
                }
                if( cur_page > 1 && $(the_ajax_script.pagination_class+' a').last().length && $(the_ajax_script.pagination_class+' a').last().attr('href').search('product-page=') == -1 ) {
                    if( uri.slice(-1) != '/' ) {
                        uri += '/';
                    }
                    uri = uri + the_ajax_script.pagination_base+"/" + cur_page;
                    if ( the_ajax_script.trailing_slash ) {
                        uri += '/';
                    }
                }
                if( something_added ) {
                    uri = uri + additional_datas;
                    next_symbol_sep = '&'
                }
            } else {
                if( something_added ) {
                    uri = uri + additional_datas;
                    next_symbol_sep = '&';
                }
                if( cur_page > 1 && $(the_ajax_script.pagination_class+' a').last().length && $(the_ajax_script.pagination_class+' a').last().attr('href').search('product-page=') == -1 ) {
                    uri = uri + next_symbol_sep + "paged=" + cur_page;
                    next_symbol_sep = '&';
                }
                if( uri_request ) {
                    uri = uri + next_symbol_sep + variable + "=" + uri_request;
                    next_symbol_sep = '&';
                }
            }
            if( the_ajax_script.control_sorting && args.orderby && the_ajax_script.default_sorting != args.orderby ){
                uri = uri + next_symbol_sep + 'orderby=' + args.orderby;
                next_symbol_sep = '&';
            }
            if( cur_page > 1 && $(the_ajax_script.pagination_class+' a').last().length && $(the_ajax_script.pagination_class+' a').last().attr('href').search('product-page=') != -1 ) {
                uri = uri + next_symbol_sep + "product-page=" + cur_page;
            }
            if ( /\#/.test(location.href) ) {
                passed_vars1 = location.href.split('#');
                passed_vars1 = passed_vars1[1];
                uri += '#'+passed_vars1;
            }
            if( return_request ) {
                return uri;
            } else {
                var stateParameters = { BeRocket: "Rules" };
                history.replaceState(stateParameters, "BeRocket Rules");
                history.pushState(stateParameters, "BeRocket Rules", uri);
                history.pathname = uri;
            }
        }
        $(document).on('click', the_ajax_script.pagination_class+' a', function (event) {
            var permalink_page = /\/page\/(\d+)/;
            var non_permalink_page = /paged=(\d+)/;
            var href = $(this).attr('href');
            if( permalink_page.test(href) ) {
                _next_page = href.match(permalink_page);
                _next_page = _next_page[1];
            } else if( non_permalink_page.test(href) ) {
                _next_page = href.match(non_permalink_page);
                _next_page = _next_page[1];
            } else if ( $(this).hasClass('next') || $(this).parent().hasClass('next') ) {
                _next_page = $(the_ajax_script.pagination_class+' .current').first().text();
                _next_page = _next_page.replace(/\D/g,'');
                _next_page = parseInt( _next_page ) + 1;
            } else if ( $(this).hasClass('prev') || $(this).parent().hasClass('prev') ) {
                _next_page = $(the_ajax_script.pagination_class+' .current').first().text();
                _next_page = _next_page.replace(/\D/g,'');
                _next_page = parseInt( _next_page ) - 1;
            } else {
                _next_page = $(this).text();
                _next_page = _next_page.replace(/\D/g,'');
                _next_page = parseInt(_next_page);
            }
            if( typeof(_next_page) == 'undefined' || _next_page <= 0 ) {
                _next_page = 1;
            }
            event.preventDefault();
            $(the_ajax_script.pagination_class+' .current').removeClass('current');
            $(this).after("<span class='page-numbers current' style='display:none;'>"+_next_page+"</span>");
            if( the_ajax_script.first_page ) {
                berocket_aapf_widget_first_page_jump = false;
            }
            updateProducts(true);
        });
        function in_array(needle, haystack, strict) {
            var found = false, key, strict = !!strict;
            for (key in haystack) {
                if ((strict && haystack[key] === needle) || (!strict && haystack[key] == needle)) {
                    found = key;
                    break;
                }
            }
            return found;
        }
        $(document).on("mousedown", ".berocket_aapf_widget input[type=radio]", function(event){
            if ( $(this).prop('checked') ) {
                $(this).addClass('radio_is_checked');
            } else {
                $(this).removeClass('radio_is_checked');
            }
        });
        $(document).on("click", ".berocket_aapf_widget input[type=radio]", function(event){
            if ( $(this).is('.radio_is_checked') ) {
                $label = $(this).parents('li').first().find('.berocket_label_widgets');
                setTimeout(function() { 
                    $label.click();
                }, 5); 
            }
        });
        $(document).on("select2:close", ".berocket_aapf_widget select", function(){
            if( $(this).is('.select2changed') ) {
                $(this).removeClass('select2changed');
                berocket_on_change_inputs_selects($(this));
            }
        });
        $(document).on("change", ".berocket_aapf_widget input, .berocket_aapf_widget select", function(){
            if( $(this).is('.select2-hidden-accessible') ) {
                $(this).addClass('select2changed');
                return 0;
            }
            berocket_on_change_inputs_selects($(this));
        });
        function berocket_on_change_inputs_selects(element) {
            var $this = $(element);
            if( $this.parents('.berocket_disabled_filter_element').length ) {
                return;
            }
            var $parent_for = $(document);
            if( $this.parents('.berocket_search_box_block').length > 0 ) {
                $parent_for = $this.parents('.berocket_search_box_block');
                search_url = $parent_for.find('.berocket_search_box_button').data('url');
                if( the_ajax_script.current_page_url == search_url 
                || the_ajax_script.current_page_url.slice(-1) == '/' 
                && the_ajax_script.current_page_url.slice(0, -1) == search_url
                || search_url.slice(-1) == '/' 
                && the_ajax_script.current_page_url == search_url.slice(0, -1) ) {
                    $parent_for = $(document);
                }
            }
            current_value = [];
            $berocket_aapf_last_changed = $this;
            berocket_child_no_products = '';
            if( $this.parents('.berocket_aapf_widget-wrapper').find('.berocket_child_no_products').length > 0 && 
                $this.parents('.berocket_aapf_widget-wrapper').find('.berocket_child_no_products').text() ) {
                    berocket_child_no_products = $this.parents('.berocket_aapf_widget-wrapper').find('.berocket_child_no_products').text();
            }
            if($this.attr('type') == 'checkbox' || $this.attr('type') == 'radio')
            {
                $label = $this.parents('li').first().find('.berocket_label_widgets');
                if( $label.hasClass('berocket_checked')) {
                    $parent_for.find('.'+$label.data('for')).prop('checked', false).removeAttr('checked').trigger('refresh');
                    $parent_for.find('.'+$label.data('for')).each(function( i2, o2 ) {
                        $(o2).parents('li').first().removeClass('berocket_hide_sel_value').find('.berocket_label_widgets').removeClass('berocket_checked');
                    });
                    $label.removeClass('berocket_checked');
                } else {
                    $parent_for.find('.'+$label.data('for')).prop('checked', true).trigger('refresh');
                    if( $label.parents('li').first().find('input').attr('type') == 'radio' ) {
                        $parent_for.find('.'+$label.data('for')).parents('.berocket_aapf_widget').find('li').removeClass('berocket_hide_sel_value').find('.berocket_label_widgets').removeClass('berocket_checked');
                    }
                    $parent_for.find('.'+$label.data('for')).each(function( i2, o2 ) {
                        $(o2).parents('li').first().find('.berocket_label_widgets').addClass('berocket_checked');
                        if( the_ajax_script.hide_sel_value ) {
                            $(o2).parents('li').first().addClass('berocket_hide_sel_value');
                        }
                    });
                    $label.addClass('berocket_checked');
                }
                $parent_for.find('.'+$label.data('for')).parents('ul').find('input').trigger('refresh');
                if( the_ajax_script.hide_sel_value ) {
                    $for_objects = $parent_for.find('.'+$label.data('for')).parents('.berocket_aapf_widget');
                    $for_objects.each(function (i,o){
                        $hiden_objects = $(o).find('.berocket_hide_o_value, .berocket_hide_sel_value');
                        if( $hiden_objects.length == 0 ) {
                            $(o).find('.berocket_widget_show_values').hide();
                        } else {
                            $(o).find('.berocket_widget_show_values').show();
                        }
                    });
                }
                if($this.prop('checked'))
                {
                    $parent_for.find('.'+$label.data('for')).prop('checked', true).trigger('refresh');
                    if( the_ajax_script.hide_sel_value ) {
                        $('.'+$label.data('for')).each(function( i2, o2 ) {
                            $(o2).parents('li').first().addClass('berocket_hide_sel_value');
                        });
                    }
                }
                else
                {
                    $parent_for.find('.'+$label.data('for')).prop('checked', false).removeAttr('checked').trigger('refresh').each(function( i2, o2 ) {
                        $(o2).parents('li').removeClass('berocket_hide_sel_value');
                    });
                }
                $this.parents('.berocket_aapf_widget').find('input').each( function ( i, o ) {
                    if ( $(o).prop('checked') ) {
                        current_value.push( parseInt( $(o).data('term_id') ) );
                    }
                });
            } else if($this.is('select')) {
                var selected_val = [];
                $(element).find('option:selected').each(function(i, o) {
                    selected_val.push($(o).val());
                });
                if( $this.parents('.berocket_aapf_widget').data('child_parent') != 'child' ) {
                    $parent_for.find('select.'+$this.data('taxonomy')).val(selected_val).trigger('refresh');
                }
                current_value = selected_val;
                if( ! selected_val.length ) {
                    current_value = false;
                }
            }
            if( $this.parents('.berocket_search_box_block').length == 0 ) {
                var $widget_child = '';
                if ( $this.parents('.berocket_aapf_widget').data('child_parent') == 'parent' || $this.parents('.berocket_aapf_widget').data('child_parent') == 'child' ) {
                    berocket_aapf_child_parent_loaded = false;
                    $widget_child = '.'+$this.parents('.berocket_aapf_widget').data('attribute')+'_child_';
                    if ( $this.parents('.berocket_aapf_widget').data('child_parent') == 'parent' ) {
                        $widget_child += '1';
                    } else {
                        $widget_child += ( parseInt( $this.parents('.berocket_aapf_widget').data('child_parent_depth') ) + 1 );
                    }
                    $widget_child = $($widget_child);
                    berocket_remove_child ( $widget_child );
                }
                updateProducts();
                if ( $this.parents('.berocket_aapf_widget').data('child_parent') == 'parent' || $this.parents('.berocket_aapf_widget').data('child_parent') == 'child' ) {
                    berocket_child_load ( $widget_child, $this.parents('.berocket_aapf_widget').data('attribute'), current_value, 'default' );
                }
                if( !berocket_aapf_child_parent_loaded ) {
                    berocket_aapf_child_parent_loaded = true;
                    berocket_product_recount();
                }
                berocket_reset_button_hide_not();
            }
        }
        berocket_set_slider = function ( i, o ) {
            if( ! $(o).hasClass('berocket_slidered') ) {
                var all_terms_name;
                var all_terms_slug;
                var slider_disabled = $(o).data('disabled');
                if( slider_disabled ) {
                    slider_disabled = true;
                } else {
                    slider_disabled = false;
                }
                $(o).addClass('berocket_slidered').slider({
                    range: true,
                    min: parseFloat($(o).data('min')),
                    max: parseFloat($(o).data('max')),
                    values: [$(o).data('value1'),$(o).data('value2')],
                    disabled: slider_disabled,
                    create: function( event, ui ) {
                        all_terms_name = $(o).data('all_terms_name');
                        all_terms_slug = $(o).data('all_terms_slug');
                        $o = $(o);
                        var number_style = $(o).data('number_style');
                        if( ! number_style ) {
                            number_style = the_ajax_script.number_style;
                        }
                        if( $(o).data('all_terms_name') == null )  {
                            $( '#'+$o.data('fields_1') ).val( berocket_format_number ($(o).data('value1') / $(o).data( 'step' ), number_style ) );
                            $( '#'+$o.data('fields_2') ).val( berocket_format_number ($(o).data('value2') / $(o).data( 'step' ), number_style ) );
                            $(o).data('value_1', $(o).data('value1') / $(o).data( 'step' ) );
                            $(o).data('value_2', $(o).data('value2') / $(o).data( 'step' ) );
                        } else {
                            $( '#'+$o.data('fields_1') ).val( all_terms_slug[ $(o).data('value1')>>0 ] );
                            $( '#'+$o.data('fields_2') ).val( all_terms_slug[ $(o).data('value2')>>0 ] );
                            $(o).data('value_1', all_terms_name[ $(o).data('value1')>>0 ] );
                            $(o).data('value_2', all_terms_name[ $(o).data('value2')>>0 ] );
                        }
                    }
                }).on('slidestop', function( event ){
                    var $widget_child = '', current_value = false;
                    if ( $(o).data('min') != $(o).data('value1') || $(o).data('max') != $(o).data('value2') ) {
                        current_value = [$(o).data('value1'), $(o).data('value2')];
                    }
                    if( $(this).parents('.berocket_search_box_block').length == 0 ) {
                        if ( $(o).parents('.berocket_aapf_widget').data('child_parent') == 'parent' || $(o).parents('.berocket_aapf_widget').data('child_parent') == 'child' ) {
                            $widget_child = '.'+$(o).parents('.berocket_aapf_widget').data('attribute')+'_child_';
                            if ( $(o).parents('.berocket_aapf_widget').data('child_parent') == 'parent' ) {
                                $widget_child += '1';
                            } else {
                                $widget_child += ( parseInt( $(o).parents('.berocket_aapf_widget').data('child_parent_depth') ) + 1 );
                            }
                            $widget_child = $($widget_child);
                            berocket_remove_child ( $widget_child );
                        }
                        $berocket_aapf_last_changed = $(o);
                        updateProducts();
                        if ( $(o).parents('.berocket_aapf_widget').data('child_parent') == 'parent' || $(o).parents('.berocket_aapf_widget').data('child_parent') == 'child' ) {
                            berocket_child_load ( $widget_child, $(o).parents('.berocket_aapf_widget').data('attribute'), current_value, 'slider' );
                        }
                        if( !berocket_aapf_child_parent_loaded ) {
                            berocket_aapf_child_parent_loaded = true;
                            berocket_product_recount();
                        }
                        berocket_reset_button_hide_not();
                    }
                }).on('slide', function( event, ui ) {
                    $o = $(ui.handle).parents('div.berocket_filter_slider');
                    vals = ui.values;
                    var number_style = $(o).data('number_style');
                    if( ! number_style ) {
                        number_style = the_ajax_script.number_style;
                    }
                    if( $(o).data('all_terms_name') == null ) {
                        $( '#'+$o.data('fields_1') ).val( berocket_format_number (vals[0]/$(o).data('step'), number_style) );
                        $( '#'+$o.data('fields_2') ).val( berocket_format_number (vals[1]/$(o).data('step'), number_style) );
                        $o.data('value_1', (vals[0]/$(o).data('step')));
                        $o.data('value_2', (vals[1]/$(o).data('step')));
                    } else {
                        $( '#'+$o.data('fields_1') ).val( all_terms_slug[vals[0]] );
                        $( '#'+$o.data('fields_2') ).val( all_terms_slug[vals[1]] );
                        $o.data('value_1', all_terms_name[vals[0]]);
                        $o.data('value_2', all_terms_name[vals[1]]);
                    }
                    if ( $o.data('child_parent') != 'child' && $o.data('child_parent') != 'parent' ) {
                        $('.slide div').each(function( i, obj ) {
                            if( $(obj).data('taxonomy') == $(o).data('taxonomy') ) {
                                $(obj).slider("values", vals);
                                var number_style = $(obj).data('number_style');
                                if( ! number_style ) {
                                    number_style = the_ajax_script.number_style;
                                }
                                if( $(o).data('all_terms_name') == null ) {
                                    $( '#'+$(obj).data('fields_1') ).val( berocket_format_number (vals[0] / $(obj).data( 'step' ), number_style ) );
                                    $( '#'+$(obj).data('fields_2') ).val( berocket_format_number (vals[1] / $(obj).data( 'step' ), number_style ) );
                                    $(obj).data('value_1', (vals[0]/$(o).data('step')));
                                    $(obj).data('value_2', (vals[1]/$(o).data('step')));
                                } else {
                                    $( '#'+$(obj).data('fields_1') ).val( all_terms_slug[ vals[0] ] );
                                    $( '#'+$(obj).data('fields_2') ).val( all_terms_slug[ vals[1] ] );
                                    $(obj).data('value_1', all_terms_name[vals[0]]);
                                    $(obj).data('value_2', all_terms_name[vals[1]]);
                                }
                            }
                        });
                    }
                });
            }
        }
        berocket_slider_input_val_started = false;
        $(document).on('focus', '.berocket_slider_start_val, .berocket_slider_end_val', function() {
            if ( ! $(this).is(':disabled') ) {
                var slider = $(this).parents('.berocket_aapf_widget').find('.berocket_filter_slider');
                var values = slider.slider( "option", "values" );
                if( $(this).is('.berocket_slider_start_val') ) {
                    $(this).val( values[0] ).data('save_val', values[0]);
                } else {
                    $(this).val( values[1] ).data('save_val', values[1]);
                }
            }
        });
        $(document).on('focusout', '.berocket_slider_start_val, .berocket_slider_end_val', function() {
            if ( ! $(this).is(':disabled') && $(this).data('save_val') == $(this).val() ) {
                var slider = $(this).parents('.berocket_aapf_widget').find('.berocket_filter_slider');
                var number_style = slider.data('number_style');
                if( ! number_style ) {
                    number_style = the_ajax_script.number_style;
                }
                $val = $(this).val();
                $(this).val( berocket_format_number( $val*1, number_style) );
            }
        });
        $(document).on('change', '.berocket_slider_start_val, .berocket_slider_end_val', function() {
            if( ! berocket_slider_input_val_started ) {
                berocket_slider_input_val_started = true;
                var slider = $(this).parents('.berocket_aapf_widget').find('.berocket_filter_slider');
                var values = slider.slider( "option", "values" );
                if( $(this).is('.berocket_slider_start_val') ) {
                    values[0] = $(this).val();
                } else {
                    values[1] = $(this).val();
                }
                slider.slider( "option", "values", values );
                slider
                .trigger('slide', {handle:slider.find('.ui-slider-handle').first(), value: values[0], values: values})
                .trigger('slide', {handle:slider.find('.ui-slider-handle').last(), value: values[1], values: values})
                .trigger('slidestop');
                berocket_slider_input_val_started = false;
            }
        });
        $(document).on('click', '.berocket_aapf_widget_selected_area .br_unselect_all', function (event){
            event.preventDefault();
            br_reset_all_filters();
        });
        $(document).on('mouseenter', '.berocket_aapf_widget_selected_area a, .berocket_aapf_widget a', function (event){
            $(this).addClass('br_hover');
        });
        $(document).on('mouseleave', '.berocket_aapf_widget_selected_area a, .berocket_aapf_widget a', function (event){
            $(this).removeClass('br_hover');
        });
        $(document).on('click', '.berocket_aapf_widget_selected_area a:not(.br_unselect_all)', function (event){
            event.preventDefault();
            $obj = $(this);
            berocket_unselect_all = true;
            var el_type = $obj.data('el_type');
            var el_id = $obj.data('el_id');
            var taxonomy = $obj.data('el_taxonomy');
            reset_filter(el_type, el_id, taxonomy);
            berocket_unselect_all = false;
            updateProducts(true);
        });
        $(document).on('click', '.berocket_aapf_reset_button', function() {
            br_reset_all_filters();
        });
        br_reset_all_filters = function () {
            berocket_unselect_all = true;
            reset_info = get_widget_selected_filters();
            reset_info.forEach(function(element) {
                reset_filter(element.type, element.id, element.taxonomy);
            });
            berocket_unselect_all = false;
            updateProducts(true);
        }
        function reset_filter(el_type, el_id, taxonomy) {
            $berocket_aapf_last_changed = $('.berocket_aapf_widget li').first();
            if ( el_type == 'checkbox' || el_type == 'color' || el_type == 'radio' ) {
                if( $('.' + el_id).prop('checked') ) {
                    $('.' + el_id).first().parents('li').first().find('label').trigger('click');
                }
            } else if ( el_type == 'select' ) {
                $( 'select.'+taxonomy ).find('option.select_'+el_id).prop('selected', false);
                $( 'select.'+taxonomy ).trigger('change.select2');
                updateProducts();
            } else if ( el_type == 'slider' ) {
                $slider = $('#'+el_id).closest('li').find('.berocket_filter_slider');
                val1 = parseFloat($slider.data('min'));
                val2 = parseFloat($slider.data('max'));
                ui = {handle:$slider.children(), values:[val1,val2]};
                $slider.slider( "values", [ val1, val2 ] ).trigger('slide', ui);
                updateProducts();
            } else if ( el_type == '_date' ) {
                $date = $('.'+el_id+'.berocket_date_picker');
                val1 = $date.data('min');
                val2 = $date.data('max');
                berocket_unselect_all = true;
                $('.'+el_id+'.br_start_date').val(val1).trigger('change');
                $('.'+el_id+'.br_end_date').val(val2).trigger('change');
                berocket_unselect_all = false;
                updateProducts();
            }
        }
        $(document).on('click', '.berocket_aapf_widget_update_button', function (event) {
            event.preventDefault();
            updateProducts(true);
        });
        $(document).on('click', '.berocket_label_widgets', function(event) {
            if( $(this).parents('li').first().find('input').attr('type') == 'checkbox' || $(this).parents('li').first().find('input').attr('type') == 'radio' ) {
                event.preventDefault();
                event.stopPropagation();
                $(this).parents('span').first().find('input').trigger('change');
            }
        });
        $(document).on( 'click', '.berocket_aapf_widget-title_div:not(".disable_collapse")', function( event ) {
            event.preventDefault();
            if( $(window).width() > 768 && $(this).parents('.berocket_inline_clickable_hover.berocket_single_filter_visible').length ) {
                return false;
            }
            $(this).trigger('br_showhide');
        });
        $(document).on( 'br_showhide', '.berocket_aapf_widget-title_div:not(".disable_collapse")', function( event ) {
            var widget_block = $(this).next('.berocket_aapf_widget');
            $(this).parents('.berocket_single_filter_widget').data('showbutton', widget_block.css('display') == 'none');
            if(widget_block.css('display') == 'none')
            {
                widget_block.parents('.berocket_single_filter_widget').first().removeClass('berocket_single_filter_hidden').addClass('berocket_single_filter_visible');
                widget_block.slideDown(0, function(){widget_block.find('.mCSB_container').css('width', '');});
                $(this).find('span').removeClass('show_button').addClass('hide_button');
                $(this).parents('.berocket_single_filter_widget').data('showbutton', 'hide');
            }
            else
            {
                widget_block.parents('.berocket_single_filter_widget').first().removeClass('berocket_single_filter_visible').addClass('berocket_single_filter_hidden');
                widget_block.slideUp(0);
                if( typeof(widget_block.find('select').select2) == 'function' ) {
                    widget_block.find('select').select2("close");
                }
                $(this).find('span').removeClass('hide_button').addClass('show_button');
                $(this).parents('.berocket_single_filter_widget').data('showbutton', 'show');
            }
        });
        $(document).on( 'click', '.berocket_aapf_widget .berocket_widget_show_values', function( event ) {
            event.preventDefault();
            var widget_block = $(this).parents('.berocket_single_filter_widget');
            if(widget_block.hasClass('show_o_sel_values'))
            {
                widget_block.removeClass('show_o_sel_values');
            }
            else
            {
                widget_block.addClass('show_o_sel_values');
            }
            berocket_widget_show_values_text(widget_block);
        });
        function berocket_widget_show_values_text($widget) {
            var $this = $widget.find('.berocket_widget_show_values');
            var widget_block = $this.parents('.berocket_single_filter_widget');
            if(widget_block.hasClass('show_o_sel_values')) {
                $this.html(the_ajax_script.translate.hide_value+'<span class="hide_button"></span>');
            } else {
                $this.html(the_ajax_script.translate.show_value+'<span class="show_button"></span>');
            }
        }
        $(document).on( the_ajax_script.description_show, '.berocket_aapf_widget-title_div .berocket_aapf_description i', function( event ) {
            event.preventDefault();
            event.stopPropagation();
            $('.berocket_aapf_description_div').remove();
            if( $('.berocket_aapf_description_div').length == 0 ) {
                $block = $(this).next().clone();
                $block.addClass( 'berocket_aapf_description_div' ).hide();
                $('body').append($block);
                i_top = ( $(this).offset().top - $(document).scrollTop() ) + ( $(this).height() / 2 );
                i_left = $(this).offset().left;
                top_px = parseInt( i_top + $( document ).scrollTop() );
                if( i_top >= (window.innerHeight / 2) ) {
                    top_px -= ( $block.outerHeight() );
                    top_px += 22;
                    top_side = 'bottom';
                } else {
                    top_side = 'top';
                    top_px -= 22;
                }
                side_px = i_left;
                if( i_left < (window.innerWidth / 2) ) {
                    side_px += $(this).width();
                    side = 'left';
                    deside = 'right';
                    bottom_side = 'top';
                    side_px += 9;
                } else {
                    side = 'right';
                    deside = 'left';
                    bottom_side = 'bottom';
                    side_px = (window.innerWidth - side_px) - 3;
                }
                $block.addClass( 'berocket_aapf_description_div_'+top_side+'_'+side ).addClass( 'berocket_aapf_description_div' ).css( 'top', top_px ).css( side, side_px );
                $block.find('.berocket_aapf_description_arrow').css('border-'+deside, '0').css('border-'+bottom_side, '0');
                $block.show();
            }
        });
        $(document).on( the_ajax_script.description_hide, '.berocket_aapf_widget-title_div .berocket_aapf_description i', function( event ) {
            event.preventDefault();
            event.stopPropagation();
            to_element = event.toElement || event.relatedTarget;
            if ( the_ajax_script.description_hide == 'mouseleave' && $(to_element).parents('.berocket_aapf_description_div').length == 0) {
                $('.berocket_aapf_description_div').remove();
            }
        });
        $(document).on(the_ajax_script.description_hide, '.berocket_aapf_description_div', function( event ) {
            event.preventDefault();
            event.stopPropagation();
            if ( the_ajax_script.description_hide == 'mouseleave' ) {
                $(this).remove();
            }
        });
        $(document).on(the_ajax_script.description_hide, function() {
            $('.berocket_aapf_description_div').remove();
        });
        function berocket_aapf_widget_loading() {
            if( $('.berocket_aapf_widget_loading').length > 0 ) {
                loading_top = ( ( $(document).scrollTop() + ( window.innerHeight / 2 ) ) - $('.berocket_aapf_widget_loading').offset().top );
                if ( loading_top < 100 || $('.berocket_aapf_widget_loading').height() < 200 ) {
                    loading_top = 100;
                } else if ( loading_top > ( $('.berocket_aapf_widget_loading').height() - $('.berocket_aapf_widget_loading_container').height() ) ) {
                    loading_top = $('.berocket_aapf_widget_loading').height() - $('.berocket_aapf_widget_loading_container').height();
                }
                $('.berocket_aapf_widget_loading_container').css('top', loading_top);
			}
            $('.berocket_aapf_description_div').remove();
        }
        $(document).scroll( berocket_aapf_widget_loading );
        $(document).on( 'click', function( event ) {
            $('.berocket_aapf_product_count_desc').remove();
        });
        window.onpopstate = function(event) {
            if ( event.state != null && event.state.BeRocket == 'Rules' ) {
                location.reload();
            }
        };
        $(document).on('click', '.berocket_search_box_button', function() {
            var search_url = $(this).data('url');
            if( the_ajax_script.current_page_url == search_url 
            || the_ajax_script.current_page_url.slice(-1) == '/' 
            && the_ajax_script.current_page_url.slice(0, -1) == search_url
            || search_url.slice(-1) == '/' 
            && the_ajax_script.current_page_url == search_url.slice(0, -1) ) {
                update_data_containers();
                updateProducts(true, true);
            } else {
                update_data_containers(true, $(this).parents('.berocket_search_box_block'));
                args = {
                    current_language: the_ajax_script.current_language,
                    terms: berocket_aapf_widget_product_filters,
                    price: berocket_aapf_widget_product_price_limit,
                    limits: berocket_aapf_widget_product_limits,
                    product_cat: the_ajax_script.product_cat,
                    product_taxonomy: the_ajax_script.product_taxonomy,
                    action: 'berocket_aapf_listener',
                    orderby: $('.woocommerce-ordering select.orderby').val(),
                    attributes: [],
                    cat_limit: [],
                    location: $(this).data('url'),
                };
                the_ajax_script.current_page_url = search_url;
                location.href = updateLocation(args, true, true);
            }
        });
        if( the_ajax_script.control_sorting ) {
            $(document).on('submit', 'form.woocommerce-ordering', function (event) {
                event.preventDefault();
            });
            $(document).on('change', 'select.orderby', function (event) {
                event.preventDefault();
                $('select.orderby').val($(this).val());
                updateProducts(true);
            });
        }
        berocket_reset_button_hide_not = function() {
            jQuery('.berocket_single_filter_widget .berocket_aapf_reset_button').parents('.berocket_aapf_widget').show();
            if( ! jQuery('.berocket_aapf_widget-wrapper:not(".berocket_hiden_without_visible_values")').length ) {
                jQuery('.berocket_single_filter_widget.berocket_no_filters .berocket_aapf_reset_button').parents('.berocket_aapf_widget').hide();
            }
            if( get_widget_selected_filters().length ) {
                jQuery('.berocket_single_filter_widget .berocket_aapf_reset_button').parents('.berocket_aapf_widget').show();
            } else {
                jQuery('.berocket_single_filter_widget.berocket_not_selected .berocket_aapf_reset_button').parents('.berocket_aapf_widget').hide();
            }
        }
        berocket_filters_first_load = function() {
            if( $(window).width() <= 767 ) {
                $('.berocket_hide_single_widget_on_mobile').remove();
            }
            if( $(window).width() > 767 && $(window).width() <= 1024 ) {
                $('.berocket_hide_single_widget_on_tablet').remove();
            }
            if( $(window).width() > 1024 ) {
                $('.berocket_hide_single_widget_on_desktop').remove();
            }
            if( $('.berocket_wc_shortcode_fix').length ) {
                berocket_new_product_recount($('.berocket_wc_shortcode_fix'));
                $('.berocket_wc_shortcode_fix').html('');
            }
            if( $(the_ajax_script.pagination_class).length > 0 ){
                woocommerce_pagination_page = parseInt( $(the_ajax_script.pagination_class+' .current').first().text() );
                if( woocommerce_pagination_page < 1 ) woocommerce_pagination_page = 1;
            }
            if( $('.berocket_aapf_widget_update_button').hasClass('berocket_aapf_widget_update_button') ){
                berocket_aapf_widget_wait_for_button = true;
            } else {
                berocket_aapf_widget_wait_for_button = false;
            }
            $( ".berocket_filter_slider" ).each(function (i,o){
                berocket_set_slider ( i, o );
            });
            $(".berocket_aapf_widget_height_control").each(function (i,o){
                if( ! $(o).hasClass('br_height_controled') ) {
                    $(o).css('height', $(o).height()).mCustomScrollbar({
                        axis: "y",
                        theme: $(o).data('scroll_theme'),
                        scrollInertia: 300
                    }).addClass('br_height_controled');
                    berocket_custom_scroll_bar_init();
                }
            });
            init_styler();
            update_data_containers();
            update_selected_area();
            if( the_ajax_script.hide_empty_value ) {
                jQuery('.berocket_aapf_widget:not(.berocket_aapf_widget_selected_area)').each(function(i, o) {
                    if( jQuery(o).parents('.berocket_search_box_block').parents('.berocket_search_box_block').length == 0 && jQuery(o).parents('.br_child_parent_wrapper').length == 0 ) {
                        if( jQuery(o).find('li').not('.berocket_widget_show_values').length == jQuery(o).find('li.berocket_hide_o_value').length ) {
                            jQuery(o).parents('.berocket_aapf_widget-wrapper').addClass('berocket_hiden_without_visible_values').hide();
                        } else {
                            jQuery(o).parents('.berocket_aapf_widget-wrapper').removeClass('berocket_hiden_without_visible_values').show();
                        }
                    }
                });
            }
            if( the_ajax_script.use_select2 && $(".berocket_aapf_widget select").length && typeof $(".berocket_aapf_widget select").select2 == 'function' ) {
                $(".berocket_aapf_widget select").select2({width:'100%'});
            }
            berocket_reset_button_hide_not();
        }
        berocket_filters_first_load();
        $(document).on('click', '.br_child_toggle', function() {
            hide_child_attributes($(this));
        });
        function hide_child_attributes($button, show_child) {
            var term_id = $button.data('term_id');
            var $blocks = $button.parents('.berocket_aapf_widget').find('.berocket_term_parent_' + term_id);
            if( typeof(show_child) == 'undefined' ) {
                show_child = false;
                if( $blocks.is('.berocket_hide_child_attributes') ) {
                    show_child = true;
                }
            }
            $blocks.each(function(i, o) {
                if( show_child ) {
                    $(o).removeClass('berocket_hide_child_attributes');
                } else {
                    $(o).addClass('berocket_hide_child_attributes');
                    hide_child_attributes($(o).find('.br_child_toggle'), show_child);
                }
            });
            $button.find('.fa').removeClass('fa-plus').removeClass('fa-minus');
            if( show_child ) {
                $button.find('.fa').addClass('fa-minus');
            } else {
                $button.find('.fa').addClass('fa-plus');
            }
        }
    });
})(jQuery);
function berocket_format_number (number, number_style) {
    if( typeof number_style == 'undefined' ) {
        number_style = the_ajax_script.number_style;
    }
    var num = number.toFixed(number_style[2]);
    num = num.toString();
    var decimal = num.split('.');
    var new_number = decimal[0];
    if(num.indexOf('.') != -1)
    {
        decimal = decimal[1];
    }
    new_number = new_number.replace(/\d(?=(?:\d{3})+(?:$))/g, function($0, i){
        return $0+number_style[0];
    });
    if(num.indexOf('.') != -1)
    {
        new_number = new_number+number_style[1]+decimal;
    }
    return new_number;
}
function berocket_remove_child( $widget, show_previous ) {
    if( typeof(show_previous) == 'undefined' ) {
        show_previous = true;
    }
    $widget_child = '.'+jQuery($widget).data('attribute')+'_child_';
    $place = $widget.find('.berocket_child_parent_sample');
    $widget_child_number = parseInt( jQuery($widget).data('child_parent_depth') ) ;
    while( jQuery( $widget_child + $widget_child_number ).length > 0 ) {
        $widget_removed = $widget_child + $widget_child_number;
        jQuery( $widget_removed ).find('.berocket_widget_show_values').hide();
        jQuery( $widget_removed ).find( 'li' ).each( function ( i, o ) {
            if( ! jQuery(o).is('.berocket_child_parent_sample') && jQuery(o).parents('.berocket_child_parent_sample').length == 0 && ! jQuery(o).is('.berocket_widget_show_values') ) {
                jQuery(o).remove();
            }
        });
        $widget_child_number++;
        if( show_previous ) {
            var previous = jQuery( $widget_removed ).parents('.berocket_aapf_widget-wrapper').find('.berocket_child_previous');
            var $place = jQuery( $widget_removed ).find('.berocket_child_parent_sample');
            previous = '<li>'+previous.html()+'</li>';
            $place.after(previous);
        } else {
            break;
        }
    }
}
function berocket_child_load ( $widget, taxonomy, term_id, type ) {
    if( term_id !== false && (term_id.length > 1 || term_id[0]) != false && $widget.length > 0 && term_id.length > 0 ) {
        term_id = JSON.stringify(term_id);
        data = {action: 'br_aapf_get_child',taxonomy: taxonomy, term_id: term_id, type: type};
        terms = false;
        jQuery.ajax({
            url: the_ajax_script.ajaxurl,
            data: data,
            type: 'POST',
            success: function (data) {
                terms = data;
            },
            dataType: 'json',
            async: false
        });
        berocket_remove_child( $widget, false );
        berocket_child_replace ( $widget, terms );
        if( the_ajax_script.use_select2 && jQuery(".berocket_aapf_widget select").length && typeof jQuery(".berocket_aapf_widget select").select2 == 'function' ) {
            jQuery(".berocket_aapf_widget select").select2({width:'100%'});
        }
    }
}
function berocket_child_replace ( $widget, terms ) {
    $widget = jQuery($widget);
    var html_sample = $widget.find('.berocket_child_parent_sample ul').html();
    var $place = $widget.find('.berocket_child_parent_sample');
    var select = false;
    var html_single = '';
    if( terms.length > 0  ) {
        if ( $widget.data('type') == 'slider' ) {
            if( terms.length > 1 ) {
                var element = html_sample.replace( /R__term_id__R/g, terms[0].term_id );
                element = element.replace( /R__count__R/g, terms[0].count );
                element = element.replace( /R__slug__R/g, terms[0].slug );
                element = element.replace( /R__name__R/g, terms[0].name );
                element = element.replace( /R__taxonomy__R/g, terms[0].taxonomy );
                element = element.replace( /R__class__R/g, terms[0].r_class );
                allterms = [];
                for ( i = 0; i < terms.length; i++ ) {
                    allterms.push(terms[i].name);
                }
                element = element.replace( /R__min__R/g, 0 );
                element = element.replace( /R__value1__R/g, 0 );
                element = element.replace( /R__max__R/g, ( allterms.length - 1 ) );
                element = element.replace( /R__value2__R/g, ( allterms.length - 1 ) );
                element = element.replace( /"R__allterm__R"/g, "'"+JSON.stringify(allterms)+"'" );
                $element = jQuery(element);
                $place.after($element);
                berocket_set_slider ( 0, $widget.find('.berocket_filter_slider').last() );
            }
        } else {
            if ( $widget.find('.berocket_child_parent_sample').is('.select') ) {
                multiple = false;
                if( $widget.find('.berocket_child_parent_sample').is('.multiple') ) {
                    multiple = true;
                }
                select = true;
                html_sample = html_sample.replace( /<ul/g, '<select' );
                html_sample = html_sample.replace( /<\/ul>/g, '</select>' );
                html_sample = html_sample.replace( /<li/g, '<option' );
                html_sample = html_sample.replace( /<\/li>/g, '</option>' );
                html_sample = '<li>' + html_sample + '</li>';
                if( multiple ) {
                    html_single = html_sample.split('<option')[1];
                    html_single = html_single.split('</option>')[0];
                    html_single = '<option' + html_single + '</option>';
                } else {
                    html_single = html_sample.split('</option>')[1]+'</option>';
                }
            }
            for( i = 0; i < terms.length; i++ ) {
                var element = html_sample;
                element = element.replace( /R__term_id__R/g, terms[i].term_id );
                element = element.replace( /R__count__R/g, terms[i].count );
                element = element.replace( /R__slug__R/g, terms[i].slug );
                element = element.replace( /R__name__R/g, terms[i].name );
                element = element.replace( /R__taxonomy__R/g, terms[i].taxonomy );
                element = element.replace( /R__class__R/g, terms[i].r_class );
                element = element.replace( /#R/g, '#'+terms[i].color );
                $element = jQuery(element);
                $place.after($element);
                if ( select && i == 0 ) {
                    html_sample = html_single;
                    $place = $widget.find('select option').last();
                }
            }
        }
    } else {
        var no_values = $widget.parents('.berocket_aapf_widget-wrapper').find('.berocket_child_no_values').html();
        no_values = '<li>'+no_values+'</li>';
        $place.after(no_values);
    }
}
function load_hash_test() {
    hash = location.hash;
    test_loc = location.href;
    if( test_loc.indexOf('#') != -1 ) {
        test_loc = test_loc.split('#');
        test_loc = test_loc[0];
    }
    reload = false;
    var filtersRegex = /filters=\((.*)\)/;
    if( ( filters_hash = filtersRegex.exec(hash) ) != null ) {
        if( test_loc.indexOf('?') != -1 ) {
            href_param = test_loc.split('?');
            if(href_param[1].indexOf('filters=') != -1) {
                href_params_array = href_param[1].split('&');
                for( var i = 0; i < href_params_array.length; i++ ) {
                    if( href_params_array[i].indexOf('filters=') != -1) {
                        test_loc = test_loc.replace(href_params_array[i],filters_hash[1]).replace('&&','&');
                    }
                }
            } else {
                test_loc = test_loc+"&"+filters_hash[1];
            }
        } else {
            test_loc = test_loc+"?"+filters_hash[1];
        }
        reload = true;
    }
    var filtersRegex = /paged=([0-9]+)/;
    if( ( filters_hash = filtersRegex.exec(hash) ) != null ) {
        if( location.hash != "") {
            location.hash = "";
            test_loc = location.href;
        }
        if( test_loc.indexOf('?') != -1 ) {
            href_param = test_loc.split('?');
            if(href_param[1].indexOf('paged=') != -1) {
                href_params_array = href_param[1].split('&');
                for( var i = 0; i < href_params_array.length; i++ ) {
                    if( href_params_array[i].indexOf('filters=') != -1) {
                        test_loc = test_loc.replace(href_params_array[i],filters_hash[0]).replace('&&','&');
                    }
                }
            } else {
                test_loc = test_loc+"&"+filters_hash[0];
            }
        } else {
            test_loc = test_loc+"?"+filters_hash[0];
        }
        reload = true;
    }
    if(reload) {
        location.href = test_loc;
    }
}
load_hash_test();
(function ($){
    function berocket_rewidth_inline_filters() {
        $('.berocket_single_filter_widget.berocket_hidden_clickable').each(function() {
            $(this).removeClass('berocket_hidden_clickable_left').removeClass('berocket_hidden_clickable_right');
            var position = $(this).offset().left + $(this).outerWidth();
            var width = $(window).width();
            if( position < width/2 ) {
                $(this).addClass('berocket_hidden_clickable_left');
            } else {
                $(this).addClass('berocket_hidden_clickable_right');
            }
        });
        while( $('.berocket_single_filter_widget.berocket_inline_filters:not(".berocket_inline_filters_rewidth")').length ) {
            $element = $('.berocket_single_filter_widget.berocket_inline_filters:not(".berocket_inline_filters_rewidth")').first();
            width_to_set = '12.5%!important';
            $style = $element.attr('style');
            $style = $style.replace(/width:\s?(\d|\.)+%!important;/g, '');
            $style = $style.replace(/clear:both!important;/g, '');
            $style = $style.replace(/opacity:0!important;/g, '');
            $element.attr('style', $style);
            min_width = 200;
            every_clear = 9;
            $(document).trigger('berocket_inline_before_width_calculate');
            var check_array = [];
            check_array.push({clear:2, size:(min_width/4), width:100, block:'.berocket_inline_filters_count_1'});
            check_array.push({clear:3, size:(min_width/2.5), width:50, block:'.berocket_inline_filters_count_2'});
            check_array.push({clear:4, size:(min_width/2), width:33.333, block:'.berocket_inline_filters_count_3'});
            check_array.push({clear:5, size:(min_width/1.6), width:25, block:'.berocket_inline_filters_count_4'});
            check_array.push({clear:6, size:(min_width/1.32), width:20, block:'.berocket_inline_filters_count_5'});
            check_array.push({clear:7, size:(min_width/1.14), width:16.666, block:'.berocket_inline_filters_count_6'});
            check_array.push({clear:8, size:(min_width), width:14.285, block:'.berocket_inline_filters_count_7'});
            check_array.some(function(element) {
                if( $element.outerWidth() < element.size || $element.is(element.block) ) {
                    every_clear = element.clear;
                    width_to_set = element.width+'%!important';
                    return true;
                }
            });
            $(document).trigger('berocket_inline_after_width_calculate');
            var element_i = 0;
            while($element.is('.berocket_single_filter_widget.berocket_inline_filters:not(".berocket_inline_filters_rewidth")') ) {
                $style = $element.attr('style');
                if(typeof($style) == 'undefined' ) {
                    $style = '';
                }
                $style = $style.replace(/width:\s?(\d|\.)+%!important;/g, '');
                $style = $style.replace(/clear:both!important;/g, '');
                $style = $style.replace(/opacity:0!important;/g, '');
                $style = $style+'width:'+width_to_set+';';
                element_i++;
                if( element_i == every_clear ) {
                    $style = $style+'clear:both!important;';
                    element_i = 1;
                }
                $element.attr('style', $style+'width:'+width_to_set+';').addClass('berocket_inline_filters_rewidth');
                $element = $element.next();
            }
        }
    }
    berocket_rewidth_inline_filters();
    $(document).ready(function (){
        berocket_rewidth_inline_filters();
        $(window).on('resize berocket_ajax_filtering_end', function() {
            $('.berocket_single_filter_widget.berocket_inline_filters.berocket_inline_filters_rewidth').removeClass('berocket_inline_filters_rewidth');
            berocket_rewidth_inline_filters();
        });
    });
    $(document).on('mousedown', function(event) {
        if( ! $(event.target).parents('.berocket_aapf_widget').length 
        || ! $(event.target).parents('.berocket_single_filter_widget.berocket_hidden_clickable').length ) {
            $filter = $('.berocket_single_filter_widget.berocket_hidden_clickable').not($(event.target).parents('.berocket_single_filter_widget.berocket_hidden_clickable'));
            $filter.removeClass('berocket_single_filter_visible').addClass('berocket_single_filter_hidden');
            $filter.find('.berocket_aapf_widget').slideUp(40);
            $filter.find('.berocket_aapf_widget-title_div').find('span').removeClass('hide_button').addClass('show_button');
            $filter.find('.berocket_aapf_widget-title_div').parents('.berocket_single_filter_widget').data('showbutton', 'show');
        }
    });
    var berocket_hidden_clickable_mouseenter = setTimeout(function(){},0);
    var berocket_hidden_clickable_mouseleave = setTimeout(function(){},0);
    $(document).on('mouseenter', '.berocket_hidden_clickable .berocket_aapf_widget-title_div', function() {
        $this = $(this);
        clearTimeout(berocket_hidden_clickable_mouseenter);
        berocket_hidden_clickable_mouseenter = setTimeout( function() {
            if( $('.berocket_single_filter_widget.berocket_hidden_clickable.berocket_single_filter_visible').not($this.parents('.berocket_single_filter_widget.berocket_hidden_clickable')).length
            || ($this.parents('.berocket_single_filter_widget.berocket_hidden_clickable.berocket_inline_clickable_hover').length && ! $this.parents('.berocket_single_filter_widget.berocket_hidden_clickable.berocket_single_filter_visible').length ) ) {
                if( $(window).width() > 768 ) {
                    $('.berocket_single_filter_widget.berocket_hidden_clickable.berocket_single_filter_visible .berocket_aapf_widget-title_div').trigger('br_showhide');
                    $this.trigger('br_showhide');
                }
            }
        }, 100);
    });
    $(document).on('mouseenter', '.berocket_single_filter_widget.berocket_hidden_clickable.berocket_inline_clickable_hover, body > .select2-container', function() {
        clearTimeout(berocket_hidden_clickable_mouseleave);
    });
    $(document).on('mouseleave', '.berocket_single_filter_widget.berocket_hidden_clickable.berocket_inline_clickable_hover, body > .select2-container', function() {
        if( $(window).width() > 768 ) {
            clearTimeout(berocket_hidden_clickable_mouseleave);
            berocket_hidden_clickable_mouseleave = setTimeout(function() {
                $('.berocket_single_filter_widget.berocket_hidden_clickable.berocket_single_filter_visible .berocket_aapf_widget-title_div').trigger('br_showhide');
            }, 100);
        }
    });
    $(document).on('mouseleave', '.berocket_hidden_clickable .berocket_aapf_widget-title_div', function() {
        clearTimeout(berocket_hidden_clickable_mouseenter);
    });
    $(document).on('mousedown', '.wc-product-table-reset a', function() {
        $(this).remove();
        br_reset_all_filters();
    });
})(jQuery);
function berocket_ajax_load_product_table_compat() {
    if( jQuery('.berocket_product_table_compat .dataTables_length select').length ) {
        jQuery('.berocket_product_table_compat .wc-product-table').dataTable()._fnSaveState();
    }
    var tableid = jQuery('.berocket_product_table_compat .wc-product-table').attr('id');
    if( typeof(window['config_'+tableid]) != 'undefined' && window['config_'+tableid].serverSide ) {
        jQuery('.berocket_product_table_compat .wc-product-table').DataTable().destroy();
        var table_html = jQuery('.berocket_product_table_compat').html();
        jQuery('.berocket_product_table_compat').html('');
        jQuery('.berocket_product_table_compat').html(table_html);
        jQuery('.berocket_product_table_compat .blockUI.blockOverlay').remove();
        jQuery('.berocket_product_table_compat .wc-product-table').productTable();
    }
}
function berocket_custom_scroll_bar_init() {
    jQuery('.mCustomScrollBox').on('wheel', function (e){ e.stopPropagation; e.preventDefault(); });
};