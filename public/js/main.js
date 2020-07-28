jQuery(document).ready(function(){
    /////////////////////////////////////////////
    var width_1 = 0;
    $('#cells_wrapper > *').each(function() { width_1 += $(this).width(); });
    /////////////////////////////////////////////
    /////////////////////////////////////////////
    ///change attendance type
    $(document).on('click',".change_type_btn",function() {
        let user_id = $(this).attr('user_id');
        let ticket_id = $(this).attr('ticket_id');
        that=this;
        $('.preloader').fadeIn('slow');
        $.ajax({
        type: 'POST',
        url: "/set_attendance_type",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: {
            'user_id': user_id,
            'ticket_id':ticket_id
        },
        success: function (data) {
            window.location.reload();
            // that.classList.remove('btn-outline-dark')
            // that.classList.remove('btn-outline-primary')
            // that.classList.add('btn-outline-dark')
            // that.classList.add('btn-outline-primary')
        },
        });
    });
    $(document).on('input','.min-zero-val',function(){
        let value = $(this).val();
        if (value < 0){
            $(this).val(0);
        }
    });

    ///change attendance type END
    /////////////////////////////////////////////
    $("input[name$='user_type']").click(function() {
        var test = $(this).val();
        if (test == 'partner'){
            $("#franchise_input").show();
            $("#partner_percent_row").hide();
            $("#contracts_dates_scan").show();
        }
        else if(test == 'sales'){
            $("#franchise_input").hide();
            $("#partner_percent_row").hide();
            $("#contracts_dates_scan").hide();
        }
        else{
            $("#franchise_input").hide();
            $("#partner_percent_row").show();
            $("#contracts_dates_scan").show();
        }
    });
/////BLOCKUSER/////////////////////////////////////////
    let selected_users_array=[];
    // let current_pane = "user_list_franch";


    $(".user_list_btn_switch").click(function() {
        // current_pane = this.id;
        selected_users_array = [];
        uncheck_boxes();
        document.getElementById('show_checked').innerHTML ="";

    });
    function uncheck_boxes(){
        document.querySelectorAll("input[name=franchise_partner_user]").forEach(i=>{
            i.checked = false;
        })
    }
    function getCheckBoxCount() {
      return document.querySelectorAll('input[name=franchise_partner_user]:checked').length;
    }
    function showChecked(){
      document.getElementById('show_checked').innerHTML = getCheckBoxCount();
    }
    document.querySelectorAll("input[name=franchise_partner_user]").forEach(i=>{
        let  parent = i.parentElement.getAttribute('user_id');
        let  checkbox_id = i.getAttribute('id');
        i.onclick = function(){
            showChecked();
            if (document.getElementById(checkbox_id).checked){
                selected_users_array.push(checkbox_id)
            }
            else{
                selected_users_array.splice( selected_users_array.indexOf(checkbox_id), 1 );
            }
        };

    });





    function send_users_to_block(){
    $.ajax({
        type: 'POST',
        url: "/franchise.block",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: {
            'selected_users_array': selected_users_array,
        },
        success: function (data) {
            selected_users_array.forEach(function(element){
                document.getElementById(element).parentNode.parentNode.style.display='none'

            })
            document.location.reload();
        },
    });
    }
    //////////////////////////////////////////////
    $("#block_user_btn").click(function() {
        send_users_to_block();
    })

    $(document).on('click','.block_icon',function () {
        selected_users_array.push(this.getAttribute('user_id'))
        send_users_to_block();
    })
/////ENDBLOCKUSER/////////////////////////////////////////////////////////////////////////////////
//////ARCHIVE/////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////
    let selected_users_array_archive=[];
    function getCheckBoxCount_archive() {
      return document.querySelectorAll('input[name=blocked_user]:checked').length;
    }
    function showChecked_archive(){
      document.getElementById('show_checked_blocked').innerHTML = getCheckBoxCount_archive();
    }

    document.querySelectorAll("input[name=blocked_user]").forEach(i=>{
        let  parent_archive = i.parentElement.getAttribute('user_id');
        let  checkbox_id_archive = i.getAttribute('id');
        i.onclick = function(){
            showChecked_archive();
            if (document.getElementById(checkbox_id_archive).checked){
                selected_users_array_archive.push(checkbox_id_archive)
            }
            else{
                selected_users_array_archive.splice(selected_users_array_archive.indexOf(checkbox_id_archive), 1 );
            }
        };

    });


    ///////////////////////////////////////////////
    function send_users_to_unblock(){
    $.ajax({
        type: 'POST',
        url: "/franchise.unblock",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: {
            'selected_users_array_archive': selected_users_array_archive,
        },
        success: function (data) {
            selected_users_array_archive.forEach(function(element){
                document.getElementById(element).parentNode.parentNode.style.display='none';
            });
            document.location.reload();

        },
    });
    }
    //////////////////////////////////////////////
    $("#unblock_user_btn").click(function() {
        send_users_to_unblock();
    })
    //////////////////////////////////////////////
    function send_users_to_delete(){
        $.ajax({
        type: 'POST',
        url: "/archive.delete",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: {
            'selected_users_array_archive': selected_users_array_archive,
        },
        success: function (data) {
            selected_users_array_archive.forEach(function(element){
                document.getElementById(element).parentNode.parentNode.style.display='none';
            });
            // document.location.reload();

        },
    });
    }
    $("#delete_user_btn").click(function() {
        send_users_to_delete();
    })



///////ENDARCHIVE///////////////////////////////////////////////////////////////////////////////////

    // $(".reff_link_btn").click(function() {
    $('body').on('click', '.reff_link_btn', function() {

        let  event_id = this.getAttribute('event_id');
        $.ajax({
        type: 'POST',
        url: "/reff_gen",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: {
            'event_id': event_id,
        },
        success: function (data) {
            document.location.reload();

        },
    });

    })


    $('body').on('click', '.partner_reff_link_btn', function() {
        $.ajax({
        type: 'POST',
        url: "/part_reff_gen",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: {
            'id': "id",
        },
        success: function (data) {
            $('#part_ref_link_parent_span_id').show();
            $('.partner_reff_link_btn').hide();
            $('#part_ref_link_span_id').html(data.link);
        },
    });

    })

//////////////////////////////////////////////////////////////////

    $(document).on('click','.change_to_event_manager',function(){
        let user_id = this.getAttribute('user_id');
        let event_rights = this.getAttribute('event_rights');


        let text = (!event_rights) ? 'Разрешить создание мероприятия' : 'Запретить создание мероприятия'
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: text,
            showConfirmButton: true,
            showCancelButton: true,
        }).then(function(result) {
              if (result.isConfirmed) {
                change_franch_event_rights(user_id);
              }
            })
        });

    function change_franch_event_rights(id){
        $.ajax({
            type: 'POST',
            url: "/change_franch_event_rights",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {
                'id': id,
            },
            success: function (data) {
                window.location.reload();
            },
        });
    }


///////////////////////////////////////////////////////////////////
    $(".reff_link_btn_share").click(function() {
            $(".social-buttons").css('display:block');

    //     let  event_id = this.getAttribute('event_id');
    //     $.ajax({
    //     type: 'POST',
    //     url: "/reff_gen_social",
    //     headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    //     data: {
    //         'event_id': event_id,
    //     },
    //     success: function (data) {

    //         $(".social-buttons").styles('display:block');

    //     },
    // });

    })

});

// var ds = new DragSelect({
//   selectables: document.getElementsByClassName('place'), // node/nodes that can be selected. This is also optional, you could just add them later with .addSelectables.
//   area: document.getElementById('cells_wrapper'), // area in which you can drag. If not provided it will be the whole document.
//   customStyles: false,  // If set to true, no styles (except for position absolute) will be applied by default.
//   multiSelectKeys: ['ctrlKey', 'shiftKey', 'metaKey'],  // special keys that allow multiselection.
//   multiSelectMode: true,  // If set to true, the multiselection behavior will be turned on by default without the need of modifier keys. Default: false
//   autoScrollSpeed: 3,  // Speed in which the area scrolls while selecting (if available). Unit is pixel per movement. Set to 0 to disable autoscrolling. Default = 1
//   onDragStart: function(element) {}, // fired when the user clicks in the area. This callback gets the event object. Executed after DragSelect function code ran, befor the setup of event listeners.
//   onDragMove: function(element) {}, // fired when the user drags. This callback gets the event object. Executed before DragSelect function code ran, after getting the current mouse position.
//   onElementSelect: function(element) {}, // fired every time an element is selected. (element) = just selected node
//   onElementUnselect: function(element) {}, // fired every time an element is de-selected. (element) = just de-selected node.
//   callback: function(elements) {
//   } ,// fired once the user releases the mouse. (elements) = selected nodes.
//   selectedClass:'place-choice',
// });


const selection = Selection.create({

    // Class for the selection-area
    // class: 'place-choice',
    class: 'white_class',
    // All elements in this container can be selected
    // selectables: ['#cells_wrapper > div'],
    selectables: ['.place'],

    // The container is also the boundary in this case
    // boundaries: ['#cells_wrapper']
    boundaries: ['#cells_wrapper_out']
}).on('start', ({inst, selected, oe}) => {

    // Remove class if the user isn't pressing the control key or ⌘ key
    if (!oe.ctrlKey && !oe.metaKey) {

        // Unselect all elements
        for (const el of selected) {

            el.classList.remove('place-choice');

            if (el.getAttribute('shape') == 'triangle_shape'){
                el.classList.add('triangle_shape');
            }
            else if (el.getAttribute('shape') == 'oval_shape'){
                el.classList.add('oval_shape');
            }
            else if (el.getAttribute('shape') == 'round_shape'){
                el.classList.add('round_shape');
            }
            else if (el.getAttribute('shape') == 'round_shape'){
                el.classList.add('round_shape');
            }
            else if (el.getAttribute('shape') == 'block_shape'){
                el.classList.add('block_shape');
            }
            inst.removeFromSelection(el);

            /// дубль тест

           	///

        }

        // Clear previous selection
        inst.clearSelection();
        selectedRowCol_set.clear();
    }

}).on('move', ({changed: {removed, added}}) => {

    // Add a custom class to the elements that where selected.
    for (const el of added) {
    	if (el.id != ""){
    		selectedRowCol_set.add(el.id);
    	}
        el.classList.add('place-choice');
        el.classList.remove('yellow','blue','grey','white','default_red','triangle_shape','oval_shape','round_shape','block_shape');
    }

    // Remove the class from elements that where removed
    // since the last selection
    for (const el of removed) {
    	    el.classList.remove('place-choice');

            if (el.getAttribute('shape') == 'triangle_shape'){
                el.classList.add('triangle_shape');
            }
            else if (el.getAttribute('shape') == 'oval_shape'){
                el.classList.add('oval_shape');
            }
            else if (el.getAttribute('shape') == 'round_shape'){
                el.classList.add('round_shape');
            }
            else if (el.getAttribute('shape') == 'round_shape'){
                el.classList.add('round_shape');
            }
            else if (el.getAttribute('shape') == 'block_shape'){
                el.classList.add('block_shape');
            }


        el.classList.remove('place-choice');
        // el.classList.remove('golden_style','vip_style','standart_style','removed_style','default_red');
    }

}).on('stop', ({inst}) => {
    inst.keepSelection();
});
////////////////////////////////////////////


const selection_out = Selection.create({

    // Class for the selection-area
    // class: 'place-choice',
    class: 'white_class',

    // All elements in this container can be selected
    // selectables: ['#cells_wrapper > div'],
    selectables: ['.place_out'],

    // The container is also the boundary in this case
    // boundaries: ['#cells_wrapper']
    boundaries: ['#cells_wrapper_out']
}).on('start', ({inst, selected, oe}) => {

    // Remove class if the user isn't pressing the control key or ⌘ key
    if (!oe.ctrlKey && !oe.metaKey) {

        // Unselect all elements
        for (const el of selected) {

            el.classList.remove('place-choice');

            if (el.getAttribute('shape') == 'triangle_shape'){
                el.classList.add('triangle_shape');
            }
            else if (el.getAttribute('shape') == 'oval_shape'){
                el.classList.add('oval_shape');
            }
            else if (el.getAttribute('shape') == 'round_shape'){
                el.classList.add('round_shape');
            }
            else if (el.getAttribute('shape') == 'block_shape'){
                el.classList.add('block_shape');
            }
            inst.removeFromSelection(el);

            /// дубль тест

            ///

        }

        // Clear previous selection
        inst.clearSelection();
        selectedRowCol_set_out.clear();
    }

}).on('move', ({changed: {removed, added}}) => {

    // Add a custom class to the elements that where selected.
    for (const el of added) {
        if (el.id != ""){
            selectedRowCol_set_out.add(el.id);
        }
        el.classList.add('place-choice');
        el.classList.remove('yellow','blue','grey','white','default_red','triangle_shape','oval_shape','round_shape','block_shape');
    }

    // Remove the class from elements that where removed
    // since the last selection
    for (const el of removed) {

        if (el.getAttribute('shape') == 'block_shape'){
                el.classList.add('block_shape');
            }

        el.classList.remove('place-choice');
        // el.classList.remove('golden_style','vip_style','standart_style','removed_style','default_red');
    }

}).on('stop', ({inst}) => {
    inst.keepSelection();
});

/////////////////////////////////////////////////////////////////////////////////////////////////////////
// target elements with the "draggable" class
interact('.draggable')
  .draggable({
    // enable inertial throwing
    inertia: true,
    // keep the element within the area of it's parent
    modifiers: [
      interact.modifiers.restrictRect({
        restriction: 'parent',
        endOnly: true
      })
    ],
    // enable autoScroll
    autoScroll: true,

    listeners: {
      // call this function on every dragmove event
      move: dragMoveListener,

      // call this function on every dragend event
      end (event) {
        var textEl = event.target.querySelector('p')

        textEl && (textEl.textContent =
          'moved a distance of ' +
          (Math.sqrt(Math.pow(event.pageX - event.x0, 2) +
                     Math.pow(event.pageY - event.y0, 2) | 0))
            .toFixed(2) + 'px')
      }
    }
  })

function dragMoveListener (event) {
  var target = event.target
  // keep the dragged position in the data-x/data-y attributes

  var x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx
  var y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy
  // translate the element
  target.style.webkitTransform =
    target.style.transform =
      'translate(' + x + 'px, ' + y + 'px)'

  // update the posiion attributes
  target.setAttribute('data-x', x)
  target.setAttribute('data-y', y)
}

// this function is used later in the resizing and gesture demos
window.dragMoveListener = dragMoveListener


    // window.location.pathname.indexOf('zilla')
