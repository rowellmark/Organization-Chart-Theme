(function($) {

    var tdp_org = {

        vars: {
             $new_arr: [],
        },

        init: function(){

            this.radio_label();
            this.on_publish();
            this.archive_func();

            this.upload_media();

            //template_meta_hierarchy_view, template_meta_hierarchy

            if( $('.text-draft').length > 0 ) {
                this.hierarchy_scripts();
                this.hierarchy_functions.update_test_view();
            }
            

        },

        upload_media: function(){
            /*
                Use for Upload and Delete Media
                
                Customizable: (Please do not make any changes in HTML structure except for the following)

                    [AA] - Area Identier
                    [BB] - Popup Title
                    Your Label:
                    your_name
                    your-custom-class
                    Upload Photo - Text
                    Remove Photo - Text

                HTML Code:
                <div class="metabox-row" data-connection="[AA]">
                    <label>Your Label:</label>
                    <input class="photo-field" name="your_name" type="hidden" />
                    <div class="photo-holder your-custom-class">
                        <canvas></canvas>
                    </div>
                    <div class="logo-controls">
                        <a data-title="[BB]" data-connection="[AA]" href="javascript:;" class="meta-button upload">Upload Photo</a>
                        <a data-connection="[AA]" href="javascript:;" class="meta-button delete">Remove Photo</a>
                    </div>
                </div>
            */

            $('.postbox').on('click','.delete',function(event){
                event.preventDefault();
                event.stopPropagation();

                connection = $(this).data('connection');
                photo_holder = $('.metabox-row[data-connection="'+connection+'"] .photo-holder');

                if( photo_holder.find('canvas').length <= 0) {
                    photo_holder.find('img').remove();
                    photo_holder.append('<canvas></canvas>');
                    photo_holder.parent().find('.photo-field').val('');
                }
            });

            $('.postbox').on('click','.upload',function(event){
                event.preventDefault();
                event.stopPropagation();

                popup_title = $(this).data('title');
                connection = $(this).data('connection');
                photo_holder = $('.metabox-row[data-connection="'+connection+'"] .photo-holder');

                var image = wp.media({ 
                    title: popup_title,
                    // mutiple: true if you want to upload multiple files at once
                    library: { 
                      type: 'image' // limits the frame to show only images
                    },
                    multiple: false

                }).open()
                .on( 'select', function(e){
                    // This will return the selected image from the Media Uploader, the result is an object

                    var uploaded_image = image.state().get( 'selection' ).first();

                    // We convert uploaded_image to a JSON object to make accessing it easier
                    var image_url = uploaded_image.toJSON().url;
                    var image_id = uploaded_image.toJSON().id;

                    if( photo_holder.find('canvas').length > 0) {
                        photo_holder.find('canvas').remove();

                        photo_holder.append('<img src="'+image_url+'" alt="Photo"/>');

                    }else {
                        photo_holder.find('img').attr('src', image_url);
                    }

                    photo_holder.parent().find('.photo-field').val(image_id);

                });

            });



        },

        radio_label: function() {
            $('.metabox-radio-wrap').on('click','span',function(){
                $(this).parent().find('input[type=radio]').attr('checked','checked');
            })
        },

        on_publish: function(){
            // PUBLISH POST OVERWRITE
            $('#publish').click(function(){
                if( $('input#title').val() == "" ) {

                    if( $('input[name="employee_first_name"]').length > 0 || $('input[name="employee_last_name"]').length > 0 ){
                        $('input#title').val( $('input[name="employee_first_name"]').val() + ' ' + $('input[name="employee_last_name"]').val()  );
                    }
                    
                }
            });
        },

        archive_func: function() {
            $('.bod-chk').on('click', function(){
                if ( $(this).attr('checked') == "checked" ) {
                    $('.bod-txt').val('yes');
                }else {
                    $('.bod-txt').val('no');
                }
            })


            $( '.department_logo_category_upload' ).on( 'click', function(e) {
                e.preventDefault();

                var image = wp.media({ 
                    title: 'Upload Department Logo',
                    // mutiple: true if you want to upload multiple files at once
                    multiple: false

                }).open()
                .on( 'select', function(e){
                    // This will return the selected image from the Media Uploader, the result is an object
                    var uploaded_image = image.state().get( 'selection' ).first();

                    // We convert uploaded_image to a JSON object to make accessing it easier
                    var image_url = uploaded_image.toJSON().url;


                    $( '.department_logo_category').val( image_url );

                    if ( jQuery('.department-logo-wrap img').length <= 0 ) {
                        $('.department-logo-wrap').append('<img src="'+image_url+'"/>');
                    }else {
                        jQuery('.department-logo-wrap img').attr("src",image_url)
                    }
                    
                });

            });

           $('.department_logo_category_remove').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                $('.department-logo-wrap').find('img').remove();
                $( '.department_logo_category').val('');
            })
        },

        hierarchy_scripts: function(){

            tdp_org.hierarchy_functions.data_list_popup_actions();

            // add action buttons in list
            $('.metabox-list li').not('.add-new').each(function(){
                $action_btn = '<em class="h-add-sub">Add Sub Level</em> <em class="h-edit">Edit</em> <em class="h-del">Delete</em>';
                $(this).find('> span').append($action_btn);
            });

            tdp_org.hierarchy_functions.list_sortables();

            $(window).load(function(){
                tdp_org.hierarchy_functions.update_test_view();
            });


            // scroll to demo view
            $('.scroll-demo').click(function(){
                $('html,body').stop(true,true).animate({
                    scrollTop: $('#template_meta_hierarchy_view').offset().top - 50
                });
            });

            // scroll to hierarchy view
            $('.scroll-hierarchy').click(function(){
                $('html,body').stop(true,true).animate({
                    scrollTop: $('#template_meta_hierarchy').offset().top - 50
                });
            });

            // scroll to publish box
            $('.scroll-publish').click(function(){
                $('html,body').stop(true,true).animate({
                    scrollTop: $('#submitdiv').offset().top - 100
                },function(){
                    $("#submitdiv").addClass("highlight-list");
                    setTimeout(function(){
                        $("#submitdiv").removeClass("highlight-list");
                    },1500);
                });
            });

            // on save update hierarchy fields
            $('#publish').click(function(event){
                // remove add new entries li in list
                $('.add-new').remove();
                tdp_org.hierarchy_functions.update_hierarchy_fields();
            });

        },

        hierarchy_functions: {

            list_sortables: function(){
                $(".metabox-list ul").not("#top-level").droppable({
                    hoverClass: 'ui-state-active',
                    //tolerance: 'pointer',

                    connectWith:    '.metabox-list ul',
                    cursor:         'move',
                    placeholder:    'sortable-placeholder',
                    cursorAt:       { left: 150, top: 17 },
                    /*tolerance:      'pointer',*/
                    scroll:         false,
                    zIndex:         9999,

                    accept: function (event, ui) {
                        return true;
                    },
                    drop: function (event, ui) {
                        //alert($(this).parent().html());
                        //alert($(ui.helper).attr('class'));
                        var obj;
                        if ($(ui.helper).hasClass('draggable')) {
                            //alert('draggable');
                          obj = $(ui.helper).clone();  
                          obj.removeClass('draggable').addClass('editable').removeAttr('style');
                          //obj.addClass('droppable');
                          $(this).append(obj);

                        }

                        setTimeout(function(){
                            tdp_org.hierarchy_functions.update_test_view();
                            tdp_org.hierarchy_functions.update_hierarchy_fields();
                        },500);
                      
                    }


                }).sortable({
                    revert: false
                });
            },

            update_test_view:function(){

                data_source = jQuery('.metabox-list > ul');

                if ( data_source.find('> li').length <= 0 ) {
                    $('.text-draft').empty();
                    $('#top-level').append('<li class="add-new top-level"><span class="h-add">Add Top Level</span></li>');
                };

                $('.text-draft').empty().orgchart({
                  'data' : jQuery('.metabox-list > ul'),
                  'nodeContent': '',
                  'depth': 9999,
                  'nodeId': 'id',
                  'pan': true,
                  /*'zoom': true,
                  'zoomoutLimit': 0.5,
                  'zoominLimit': 7*/
                });

                $('.text-draft table .title').each(function(){
                    text = $(this).text();
                    text = text.replace("Add Sub Level Edit Delete", "");
                    $(this).text(text);
                });

            },

            update_hierarchy_fields: function(){

                $list = $('.metabox-list > ul > li');

                var obj = $list.length > 0 ? JSON.stringify(tdp_org.hierarchy_functions.get_list_children( $list  ) ) : '[]';

                $('textarea[name=template_hierarchy_text]').val(obj);

            },

            get_list_children: function(obj){


                var data = [];
                obj.each(function() {
                    data.push(tdp_org.hierarchy_functions.build_JSON($(this)));
                });

                return data;
            },

            build_JSON: function( $li ){

                var subObj = {
                    "id": $li.attr('data-id')
                };
                $li.children('ul').children().each(function() {
                    if (!subObj.children) {
                        subObj.children = [];
                    }
                    subObj.children.push(tdp_org.hierarchy_functions.build_JSON($(this)));
                });
                return subObj;
            },

            data_list_popup_actions: function(){

                $selected_object = '';
                $selected_object_type = '';
                $action = '';

                // delete action
                $('.metabox-list').on('click','.h-del',function(){
                    text = $(this).parent().text();

                    text = text.replace("Add Sub Level Edit Delete", "");

                    if( confirm('Delete "'+text+'" ?\nNote: Deleting Item will also delete it\'s children.') ) {
                        $(this).parent().parent().remove();
                        tdp_org.hierarchy_functions.update_test_view();
                        tdp_org.hierarchy_functions.update_hierarchy_fields();
                    }
                });

                // add new sub level icon
                $('.metabox-list').on('click','.h-add-sub',function(){
                    parent = $(this).parent().parent();

                    if ( parent.find(' > ul').length > 0 ) {
                        parent.find(' > ul ').append('<li class="add-new highlight-list"><span class="h-add">Add New Entry <em class="h-del">Delete</em></span></li>');
                    }else {
                        parent.append('<ul class="new-sub-level"><li class="add-new highlight-list"><span class="h-add">Add New Entry <em class="h-del">Delete</em></span></li></ul>');
                    }
                    
                });

                // edit current level
                $('.metabox-list').on('click','.h-edit',function(){
                        
                    $action = "edit";

                    current_id = String($(this).parent().parent().data("id"));
                    current_id = current_id.substring(0, 1);

                    if( current_id == "#" ) {
                        $text = $(this).parent().text().replace("Add Sub Level Edit Delete", "");
                        $('.static-box-details').fadeIn();
                        $('input[name=static-box-text]').val( $text );
                        $('input[name=static-box]').attr('checked','checked');
                    }else {
                        $('.static-box-details').fadeOut(0);
                        $('input[name=static-box-text]').val('');
                        $('input[name=static-box]').removeAttr('checked');
                    }


                    $('.metabox-popup-wrapper').fadeIn();

                    // set first element as selected
                    $('select[name=list_employee] option:first-child').attr('selected');

                    // clear field and focus
                    $('input[name=search_in_data_list]').focus();

                    // set clicked item as selected object
                    $selected_object = $(this);
                    
                });



                //show popup
                $('.postbox-container').on('click','.add-new',function(){

                    $action = "add";

                    $('.static-box-details').hide();
                    $('input[name=static-box-text]').val('');
                    $('input[name=static-box]').removeAttr('checked');

                    $('.metabox-popup-wrapper').fadeIn();

                    // set first element as selected
                    $('select[name=list_employee] option:first-child').attr('selected');

                    // clear field and focus
                    $('input[name=search_in_data_list]').focus();

                    // set clicked item as selected object
                    $selected_object = $(this);
                });


                // set as textbox
                $('input[name=static-box').click(function(){
                    if ( $(this).is(':checked') ) {
                        $('.static-box-details').fadeIn();
                    }else {
                        $('.static-box-details').fadeOut();
                    }
                });

                //close popup
                $('.postbox-container').on('click','.close-popup',function(){
                    $('.metabox-popup-wrapper').fadeOut();
                });

                // change data list set
                $('.postbox-container').on('change','.data-type',function(){
                    $('.data-list-wrap').hide();
                    $('.data-list-wrap.'+ $(this).val() ).show();
                });


                // on popupbg click - close popup
                $('.metabox-popup-bg').click(function(){
                    $('input[name=search_in_data_list]').val('');
                    $(this).parent().fadeOut();
                });

                // on key press enter - confirm selection
                $( "select[name=list_employee], input[name=search_in_data_list], input[name=static-box-text]" ).keypress(function(event) {
                    if(event.which == 13) {
                        event.preventDefault();
                        event.stopPropagation();
                        tdp_org.hierarchy_functions.confirm_selection( $selected_object, $action );
                    }
                });

                // confirm selection
                $('.postbox-container').on('click','.confirm-selection',function(){
                    tdp_org.hierarchy_functions.confirm_selection( $selected_object, $action );
                });

                // filter select box
                $('.data-list-wrap select').filterByText( $('input[name=search_in_data_list]'), true );
 
            }, 

            confirm_selection: function( $selected_object, $action ) {

                $action_btn = '<em class="h-add-sub">Add Sub Level</em> <em class="h-edit">Edit</em> <em class="h-del">Delete</em>';
                $select_type = $('select.data-type').val();
                $list_obj = $('.data-list-wrap.'+ $select_type + ' select');


                if ( $('input[name=static-box]').is(':checked') ) {
                    // use custom field
                    if ( $action == "add") {
                        if( tdp_org.hierarchy_functions.add_static_value( $action_btn, $select_type, $list_obj, $selected_object ) == false ) {
                            return;
                        }
                    }else {
                        if( tdp_org.hierarchy_functions.update_static_value( $action_btn, $select_type, $list_obj, $selected_object ) == false ) {
                            return;
                        }
                    }

                }else {
                    // use data 
                    if ( $action == "add") {
                        
                        if( tdp_org.hierarchy_functions.set_value_for_selected( $action_btn, $select_type, $list_obj, $selected_object ) == false ) {
                            return;
                        }

                    }else if( $action == "edit") {

                        if ( tdp_org.hierarchy_functions.update_value_for_selected( $action_btn, $select_type, $list_obj, $selected_object ) == false ) {
                            return;
                        }

                    }else {
                        console.log('Action must be set. (action is being set when add or edit button has been clicked.)');
                    }

                }

                $('input[name=static-box-text]').val('');
                $('input[name=static-box]').attr('checked','false').removeAttr('checked');

                $selected_object.parent().removeClass('new-sub-level');
                tdp_org.hierarchy_functions.update_test_view();
                tdp_org.hierarchy_functions.list_sortables();
                tdp_org.hierarchy_functions.update_hierarchy_fields();

                $('input[name=search_in_data_list]').val('');
                $('.metabox-popup-wrapper').fadeOut();
                

            },

            set_value_for_selected: function( $action_btn, $select_type, $list_obj, $selected_object ){

                if ( $list_obj.find('option:selected').text() == "") return false;

                if( $select_type == "department") {
                    $selected_object.removeClass().attr('data-id','d'+$list_obj.val());
                    $selected_object.find('span').removeClass().empty().text( $list_obj.find('option:selected').text() ).append($action_btn);
                }else {
                    $selected_object.removeClass().attr('data-id', $list_obj.val());  
                    $selected_object.find('span').removeClass().empty().text( $list_obj.find('option:selected').text() ).append($action_btn);
                }
            },

            update_value_for_selected: function( $action_btn, $select_type, $list_obj, $selected_object ){

                if ( $list_obj.find('option:selected').text() == "") return false;

                if( $select_type == "department") {
                    $selected_object.parent().parent().removeClass().attr('data-id','d'+$list_obj.val());
                    $selected_object.parent().removeClass().empty().text( $list_obj.find('option:selected').text() ).append($action_btn);
                }else {
                    $selected_object.parent().parent().removeClass().attr('data-id', $list_obj.val());  
                    $selected_object.parent().removeClass().empty().text( $list_obj.find('option:selected').text() ).append($action_btn);
                }
            },

            add_static_value: function( $action_btn, $select_type, $list_obj, $selected_object ){
                if ( $('input[name=static-box-text]').val() == "") return false;

                $field_value = $('input[name=static-box-text]').val();
                $field_value_class = $field_value.split(" ").join("-");

                $selected_object.removeClass().attr('data-id', '#'+$field_value_class);
                $selected_object.find('span').removeClass().empty().text( $field_value ).append($action_btn);

                $('input[name=static-box-text]').val();

            },

            update_static_value: function( $action_btn, $select_type, $list_obj, $selected_object ){
                if ( $('input[name=static-box-text]').val() == "") return false;

                $field_value = $('input[name=static-box-text]').val();
                $field_value_class = $field_value.split(" ").join("-");

                $selected_object.parent().parent().removeClass().attr('data-id', '#'+$field_value_class);
                $selected_object.parent().removeClass().empty().text( $field_value ).append($action_btn);

                $('input[name=static-box-text]').val();

            }

        },

        
        
    } 
    

 	$(document).ready( function() {
        tdp_org.init();
    });


    /* Extensions */

    jQuery.fn.filterByText = function(textbox, selectSingleMatch) {
      return this.each(function() {
        var select = this;
        var options = [];
        $(select).find('option').each(function() {
          options.push({value: $(this).val(), text: $(this).text()});
        });
        $(select).data('options', options);
        $(textbox).bind('change keyup', function() {
          var options = $(select).empty().scrollTop(0).data('options');
          var search = $.trim($(this).val());
          var regex = new RegExp(search,'gi');
     
          $.each(options, function(i) {
            var option = options[i];
            if(option.text.match(regex) !== null) {
              $(select).append(
                 $('<option>').text(option.text).val(option.value)
              );
            }
          });
          if (selectSingleMatch === true /*&& 
              $(select).children().length === 1*/) {
                if( $(select).children().length > 0) {
                    $(select).children().get(0).selected = true;
                }
          }
        });
      });
    };

})(jQuery);