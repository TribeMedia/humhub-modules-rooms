/**
 * Created by gqadonis on 5/28/15.
 */
var chosen = "";
var roomCount = 0;

$.fn.roompicker = function(options) {

    // set standard options
    options = $.extend({
        inputId: "",
        maxSpaces: 0,
        searchUrl: "",
        currentValue: "",
        templates: {
            inputStructure: '<div class="room_picker_container"><ul class="tag_input" id="room_tags"><li id="room_tag_input"><input type="text" id="room_input_field" class="tag_input_field" value="" autocomplete="off" placeholder="Add a room"></li></ul><ul class="dropdown-menu" id="roompicker" role="menu" aria-labelledby="dropdownMenu"></ul></div>'
        }

    }, options);


    init();


    function init() {

        // remove picker if existing
        $('.room_picker_container').remove();

        // insert the new input structure after the original input element
        $(options.inputId).after(options.templates.inputStructure);

        // hide original input element
        $(options.inputId).hide();

        if (options.currentValue != "") {

            // restore data from database
            restoreSpaceTags(options.currentValue);
        }

        // simulate focus in
        $('#room_input_field').focusin(function() {
            $('#room_tags').addClass('focus');
        })

        // simulate focus out
        $('#room_input_field').focusout(function() {
            $('#room_tags').removeClass('focus');
        })
    }

    function restoreSpaceTags(html) {

        // add html structure for input element
        $('#room_tags').prepend(html);

        // create function for every room tag to remove the element
        $('#room_tags .roomInput i').each(function() {

            $(this).click(function() {

                // remove user tag
                $(this).parent().remove();

                // reduce the count of added rooms
                roomCount--;

            })

            // raise the count of added rooms
            roomCount++;

        })


    }


    // Set focus on the input field, by clicking the <ul> construct
    jQuery('#room_tags').click(function() {

        // set focus
        $('#room_input_field').focus();
    })

    $('#room_input_field').keydown(function(event) {

        // by pressing the tab key an the input is empty
        if ($(this).val() == "" && event.keyCode == 9) {

            //do nothing

            // by pressing enter, tab, up or down arrow
        } else if (event.keyCode == 40 || event.keyCode == 38 || event.keyCode == 13 || event.keyCode == 9) {

            // ... disable the default behavior to hold the cursor at the end of the string
            event.preventDefault();

        }

        // if there is a room limit and the user didn't press the tab key
        if (options.maxSpaces != 0 && event.keyCode != 9) {

            // if the max room count is reached
            if (roomCount == options.maxSpaces) {

                // show hint
                showHintSpaces();

                // block input events
                event.preventDefault();
            }
        }

    })

    $('#room_input_field').keyup(function(event) {

        // start search after a specific count of characters
        if ($('#room_input_field').val().length >= 3) {

            // set roompicker position in bottom of the room input
            $('#roompicker').css({
                position: "fixed",
                top: $('#room_input_field').offset().top + 30,
                left: $('#room_input_field').offset().left + 2
            })

            if (event.keyCode == 40) {

                // select next <li> element
                if (chosen === "") {
                    chosen = 1;
                } else if ((chosen + 1) < $('#roompicker li').length) {
                    chosen++;
                }
                $('#roompicker li').removeClass('selected');
                $('#roompicker li:eq(' + chosen + ')').addClass('selected');
                return false;

            } else if (event.keyCode == 38) {

                // select previous <li> element
                if (chosen === "") {
                    chosen = 1;
                } else if (chosen > 0) {
                    chosen--;
                }
                $('#roompicker li').removeClass('selected');
                $('#roompicker li:eq(' + chosen + ')').addClass('selected');
                return false;

            } else if (event.keyCode == 13 || event.keyCode == 9) {

                var href = $('#roompicker .selected a').attr('href');
                // simulate click event when href is not undefined.
                if (href !== undefined) {
                    window.location.href = href;
                }

            } else {

                // save the search string to variable
                var str = $('#room_input_field').val();

                // show roompicker with the results
                $('#roompicker').show();

                // load rooms
                loadSpaces(str);
            }
        } else {

            // hide roompicker
            $('#roompicker').hide();
        }


    })


    $('#room_input_field').focusout(function() {

        // set the plain text including user guids to the original input or textarea element
        $(options.inputId).val(parseSpaceInput());
    })


    function loadSpaces(string) {

        // remove existings entries
        $('#roompicker li').remove();

        // show loader while loading
        $('#roompicker').html('<li><div class="loader"><div class="sk-spinner sk-spinner-three-bounce"><div class="sk-bounce1"></div><div class="sk-bounce2"></div><div class="sk-bounce3"></div></div></div></li>');

        jQuery.getJSON(options.searchUrl.replace('-keywordPlaceholder-', string), function(json) {

            // remove existings entries
            $('#roompicker li').remove();


            if (json.length > 0) {


                for (var i = 0; i < json.length; i++) {
                    // build <li> entry
                    var str = '<li><a tabindex="-1" href="javascript:addSpaceTag(\'' + json[i].guid + '\', \'' + json[i].image + '\', \'' + addslashes(htmlDecode(json[i].title)) + '\');"><img class="img-rounded" src="' + json[i].image + '" height="20" width="20" alt=""/> ' + json[i].title + '</a></li>';

                    // append the entry to the <ul> list
                    $('#roompicker').append(str);

                }

                // reset the variable for arrows keys
                chosen = "";

            } else {

                // hide roompicker, if no room was found
                $('#roompicker').hide();
            }


            // remove hightlight
            $("#roompicker li").removeHighlight();

            // add new highlight matching strings
            $("#roompicker li").highlight(string);

            // add selection to the first room entry
            $('#roompicker li:eq(0)').addClass('selected');

        })
    }

    function showHintSpaces() {

        // remove hint, if exists
        $('#maxSpaceHint').remove();

        // build html structure
        var _html = '<div id="maxSpaceHint" style="display: none;" class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">×</button><strong>Sorry!</strong> You can add a maximum of ' + options.maxSpaces + ' default rooms for this group.</div>';

        // add hint to DOM
        $('#room_tags').after(_html);

        // fadein hint
        $('#maxSpaceHint').fadeIn('fast');
    }

}

// Add a room tag for invitation
function addSpaceTag(guid, image_url, name) {

    // Building a new <li> entry
    var _tagcode = '<li class="roomInput" id="' + guid + '"><img class="img-rounded" src="' + image_url + '" alt="' + name + '" width="24" height="24" alt="24x24" data-src="holder.js/24x24" style="width: 24px; height: 24px;" />' + name + '<i class="fa fa-times-circle"></i></li>';


    // insert the new created <li> entry into the <ul> contruct
    $('#room_tag_input').before(_tagcode);


    // remove tag, by clicking the close icon
    $('#' + guid + " i").click(function() {

        // remove room tag
        $('#' + guid).remove();

        // reduce the count of added rooms
        roomCount--;

    })

    // hide room results
    $('#roompicker').hide();

    // set focus to the input element
    $('#room_input_field').focus();

    // Clear the textinput
    $('#room_input_field').val('');

    // raise the count of added rooms
    roomCount++;


}

function parseSpaceInput() {

    // create and insert a dummy <div> element to work with
    $('#room_tags').after('<div id="roomInputResult"></div>')

    // set html form input element to the new <div> element
    $('#roomInputResult').html($('#room_tags').html());


    $('#roomInputResult .roomInput').each(function() {

        // add the room guid as plain text
        $(this).after(this.id + ",");

        // remove the link
        $(this).remove();
    })

    // save the plain text
    var result = $('#roomInputResult').text();

    // remove the dummy <div> element
    $('#roomInputResult').remove();

// return the plain text
    return result;

}

function addslashes(str) {

    return (str + '').replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
}

function htmlDecode(value) {
    return $("<textarea/>").html(value).text();
}