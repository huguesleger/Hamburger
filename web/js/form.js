////////form add price////////////
/////////////////////////////


$(document).ready(function(){

$('#appbundle_burger_price').hide();
//$('#LabelPrice label').hide();
    

$('#BtnPrice').click(function(){
    $('#appbundle_burger_price').show();
    $("#LabelPrice").addClass('visible');
});
   
/////////////////////////////////////////////////////
////////////////////////////////////////////////////

//----------ajout champs ingredient----------///

    var $container = $('div#appbundle_burger_ingredient');
    var index = $container.find(':input').length;

    $('#add_category').click(function(e) {
      addCategory($container);

      e.preventDefault(); // évite qu'un # apparaisse dans l'URL
      return false;
    });

    if (index == 0) {
      addCategory($container);
    } else {

      $container.children('div').each(function() {
        addDeleteLink($(this));
      });
    }

    function addCategory($container) {

      var template = $container.attr('data-prototype')
        .replace(/__name__label__/g, 'n°' + (index+1))
        .replace(/__name__/g,        index)
      ;

      var $prototype = $(template);
      addDeleteLink($prototype);
      $container.append($prototype);

      index++;
    }

    function addDeleteLink($prototype) {
      var $deleteLink = $('<a href="#" class="btn btn-danger">Supprimer</a>');

      $prototype.append($deleteLink);

      $deleteLink.click(function(e) {
        $prototype.remove();

        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
      });
    }

/////////////////////////////////////////////////////
////////////////////////////////////////////////////


    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    });
    
    /////////////////////////////////////////////////////
    ////////////////////////////////////////////////////



 function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                
                $('#blah').attr('src', e.target.result);
                $('#blah').show();
                
            };
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    $("#appbundle_burger_thumbnail").change(function(){
        readURL(this);
        
    });
       
});
