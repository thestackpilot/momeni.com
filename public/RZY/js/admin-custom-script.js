$(document).ready(function() {
    var addButton = $('.add_button'); //Add button selector
    var removeButton = $('.remove_button'); //Add button selector
    var wrapper = $('.field_wrapper'); //Input field wrapper
    var fieldHTML = '<div class="meta-menu form-inline my-2">'+
        '<div class="form-group">'+
        '<input name="key[]" placeholder="Menu Key" class="mx-2 form-control">'+
        '</div>'+
        '<div class="form-group">'+
        '<input name="title[]" placeholder="Menu Title" class="mx-2 form-control">'+
        '</div>'+
        '<div class="form-group">'+
        '<input name="url[]" class="mx-2 form-control" placeholder="Menu URL">'+
        '</div>'+
        '<div class="form-group">'+
        '<input name="parent[]" class="mx-2 form-control" placeholder="Menu Parent">'+
        '</div>'+
        '<div class="form-group">'+
        '<input name="image[]" class="mx-2 form-control" placeholder="Menu Image">'+
        '</div>'+
        '</div>'
//Once add button is clicked
    $(addButton).click(function(){
        $(wrapper).append(fieldHTML);
    });

//Once remove button is clicked
    $(removeButton).click(function(){
        $(wrapper).find('.meta-menu:last').remove(); //Remove field html
    });
})

