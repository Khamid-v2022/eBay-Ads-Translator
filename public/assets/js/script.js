//appending store field
$(document).ready(function () {
    // Select the dynamic input container
    // var rowHTML = $("#dynamic-input-container");

    // var store_list = JSON.parse($("input[name=store-list]").val());
    // console.log(store_list);

    // var store_options_list = '';
    // store_list.forEach(function(value){
    //     store_options_list += `<option value="${value.id}">${value.store_name}</option>`;
    // });
    // // Define the action when the button is clicked
    // var row_count = 0;
    // $(document).on('click','.add-button',function () {
    //     row_count++;

    //     var rowCopy = `<div class="row job-row">
    //         <div class="mb-4 col-6 col-md-6 col-sm-12">
    //             <label for="source-store-${row_count}" class="form-label text-gray-600 font-semibold">Source Store:</label>
    //             <div class="input-group mb-3">
    //                 <select class="form-select single-select" name="source_store_name_${row_count}[]" id="source-store-${row_count}" aria-label="Source Store">
    //                     <option selected>Select Source Store</option>
    //                     ${store_options_list}
    //                 </select>
    //             </div>
    //         </div>
    //         <div class="mb-4 col-6 col-md-6 col-sm-12">
    //             <label for="target-store-${row_count}" class="form-label text-gray-600 font-semibold">Target Stores:</label>
    //             <select class="form-select multi-select" data-placeholder="Select Target Stores" id="target-store-${row_count}" name="target_store_name_${row_count}[]" multiple>
    //             ${store_options_list}
    //             </select>   
    //         </div>
    //     </div>`;
    //     $("#dynamic-input-container").append(rowCopy);
    //     $("#field_count").val($(".job-row").length);
    //     refreshSelect2();
    // });
    $(".single-select").change(function(){
        if(!$(this).val()){
            return;
        }
        // $(".multi-select option[value='"+ $(this).val() +"']").prop('disabled',false);
        // refreshSelect2();
    });
    $('.add-button').trigger('click');


    // Store Manage Form
    var options = { 
        // target:        '#output1',   // target element(s) to be updated with server response 
        beforeSubmit:  ajxshowRequest,  // pre-submit callback 
        success:       ajxshowResponse  // post-submit callback 
 
        // other available options: 
        //url:       url         // override for form's 'action' attribute 
        //type:      type        // 'get' or 'post', override for form's 'method' attribute 
        //dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 
        //clearForm: true        // clear all form fields after successful submit 
        //resetForm: true        // reset the form after successful submit 
 
        // $.ajax options can be used here too, for example: 
        //timeout:   3000 
    }; 
 
    // bind form using 'ajaxForm' 
    // $('#store-manage-form').ajaxForm(options); 
    // $('#store-manage-form button:submit').prop('disabled',false);
});

function refreshSelect2(){
    $('.job-row').last().find('select').select2( {
        theme: 'bootstrap-5'
    });
}
$('#target_marketplace').select2( {
    theme: 'bootstrap-5'
});
// pre-submit callback 
function ajxshowRequest(formData, jqForm, options) { 
    // formData is an array; here we use $.param to convert it to a string to display it 
    // but the form plugin does this for you automatically when it submits the data 
    var queryString = $.param(formData); 
 
    // jqForm is a jQuery object encapsulating the form element.  To access the 
    // DOM element for the form do this: 
    // var formElement = jqForm[0]; 
 
    // alert('About to submit: \n\n' + queryString); 
 
    // here we could return false to prevent the form from being submitted; 
    // returning anything other than false will allow the form submit to continue 
    return true; 
} 
 
// post-submit callback 
function ajxshowResponse(responseText, statusText, xhr, $form)  { 
    // for normal html responses, the first argument to the success callback 
    // is the XMLHttpRequest object's responseText property 
 
    // if the ajaxForm method was passed an Options Object with the dataType 
    // property set to 'xml' then the first argument to the success callback 
    // is the XMLHttpRequest object's responseXML property 
 
    // if the ajaxForm method was passed an Options Object with the dataType 
    // property set to 'json' then the first argument to the success callback 
    // is the json data object returned by the server 
 
    alert('status: ' + statusText + '\n\nresponseText: \n' + responseText + 
        '\n\nThe output div should have already been updated with the responseText.'); 
} 

//Select 2 bootstrap 5
// $( '#prepend-text-single-field' ).select2( {
//     theme: "bootstrap-5",
//     width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
//     placeholder: $( this ).data( 'placeholder' ),
// } );

// $( '#prepend-text-multiple-field' ).select2( {
//     theme: "bootstrap-5",
//     width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
//     placeholder: $( this ).data( 'placeholder' ),
//     closeOnSelect: false,
// } );
  