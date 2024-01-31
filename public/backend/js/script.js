function numberOnly(input) {
    var regex = /[^0-9]/gi;
    input.value = input.value.replace(regex, "");
}
 
$(document).ready(function() {
   var navListItems = $('div.setup-panel div a'),
     allWells = $('.setup-content'),
     allNextBtn = $('.nextBtn');

   allWells.hide();

   navListItems.click(function(e) {
     e.preventDefault();
     var $target = $($(this).attr('href')),
       $item = $(this);
     if (!$item.hasClass('disabled')) {
       navListItems.removeClass('btn-primary').addClass('btn-default');
       $item.addClass('btn-primary');
       allWells.hide();
       $target.show();
       $target.find('input:eq(0)').focus();
     }
   });

   allNextBtn.click(function() {
     var curStep = $(this).closest(".setup-content"),
       curStepBtn = curStep.attr("id"),
       nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
       curInputs = curStep.find("input[type='text'],input[type='url'],textarea[textarea]"),
       isValid = true;
     console.log(curStepBtn);
     $(".form-group").removeClass("has-error");
     for (var i = 0; i < curInputs.length; i++) {
       console.log(curInputs);
       if (!curInputs[i].validity.valid) {
         isValid = false;
         $(curInputs[i]).closest(".form-group").addClass("has-error");
       }
     }

     if (isValid)
       nextStepWizard.removeAttr('disabled').trigger('click');
   });

   $('div.setup-panel div a.btn-primary').trigger('click');

      

    // International telephone format
    // $("#phone").intlTelInput();
    // get the country data from the plugin
    var countryData = window.intlTelInputGlobals.getCountryData(),
      input = document.querySelector("#phone"),
      input_dropoff = document.querySelector("#dropoff_phone"),
      addressDropdown = document.querySelector("#address-country");

    // init plugin
    var iti = window.intlTelInput(input, {
      hiddenInput: "full_phone",
      utilsScript: "https://intl-tel-input.com/node_modules/intl-tel-input/build/js/utils.js?1549804213570" // just for formatting/placeholders etc
    });

    // init plugin
    var iti_dropoff = window.intlTelInput(input_dropoff, {
      hiddenInput: "full_phone",
      utilsScript: "https://intl-tel-input.com/node_modules/intl-tel-input/build/js/utils.js?1549804213570" // just for formatting/placeholders etc
    });

    // populate the country dropdown
    for (var i = 0; i < countryData.length; i++) {
      var country = countryData[i];
      var optionNode = document.createElement("option");
      optionNode.value = country.iso2;
      var textNode = document.createTextNode(country.name);
      optionNode.appendChild(textNode);
    }


});


// show map and select a location
$('#show-map').on("click",function(){
  // toronto default lat lng
  var button_id = $(this).attr("id");
  var myLatLng = {
    lat: 23.6486,
    lng: 45.1677
  }
  show_map_func(myLatLng,button_id);
});

$('#dropoff-show-map').on("click",function(event){
  var button_id = $(this).attr("id");
  // toronto default lat lng
  var myLatLng = {
    lat: 23.6486,
    lng: 45.1677
  }
  show_map_func(myLatLng,button_id);
});

$("#search-show-map").on("click",function(){  
  var button_id = "show-map";
  var type = "pickup";
  var address = $("#pickup_address").val();    
  search_map_func(address,button_id,type);
});

$("#dropoff-search-show-map").on("click",function(){  
  var button_id = "dropoff-show-map";
  var type = "dropoff";
  var address = $("#dropoff_address").val();
  search_map_func(address,button_id,type);
});

// search and open google map with location and fill the fields
function search_map_func(address,button_id,type){
    
    $.ajax({
      url: "https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyDTK4viphUKcrJBSuoidDqRhVA4AWnHOo0&v&address="+address,
      type: 'get',
      success: function(responses){
          if (responses.status == 'OK') {
            $("#"+type+"_latitude").val(responses.results[0].geometry.location.lat);
            $("#"+type+"_longitude").val(responses.results[0].geometry.location.lng);
            for (let i = 0; i < responses.results[0].address_components.length; i++) {
              console.log(responses.results[0].address_components[i]['types'] +i);

              if(responses.results[0].address_components[i]['types'][0] == 'postal_code'){
                $("#"+type+"_postal_code").val(responses.results[0].address_components[i]['short_name']);                
              }
              if(responses.results[0].address_components[i]['types'][0] == 'locality'){
                $("#"+type+"_city").val(responses.results[0].address_components[i]['short_name']);                
              }
              if(responses.results[0].address_components[i]['types'][0] == 'administrative_area_level_1'){
                $("#"+type+"_state").val(responses.results[0].address_components[i]['long_name']);                
              }
              if(responses.results[0].address_components[i]['types'][0] == 'country'){
                $("#"+type+"_country").val(responses.results[0].address_components[i]['long_name']);                
              }
            }
            var myLatLng = {
              lat: responses.results[0].geometry.location.lat,
              lng: responses.results[0].geometry.location.lng
            }
            show_map_func(myLatLng,button_id);
          } else {
            alert('Geocode was not successful for the following reason: ' + responses.status);
          }
      },
      error: function(err){
          console.log(err);
      }
  });
}


// function to fill from map
function show_map_func(myLatLng,button_id){
  if(button_id == "show-map"){
    var map_canvas = "map-canvas";
    var type = "pickup";
  }
  else{
    var map_canvas = "dropoff-map-canvas";
    var type = "dropoff";
  }
   
    var map = new google.maps.Map(document.getElementById(map_canvas), {
      zoom: 12,
      center: myLatLng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var marker = new google.maps.Marker({
      position: myLatLng,
      map: map,
      draggable: true,
      animation: google.maps.Animation.DROP,
    });

    function toggleBounce() {
      if (marker.getAnimation() !== null) {
        marker.setAnimation(google.maps.Animation.BOUNCE);
      } else {
        marker.setAnimation(google.maps.Animation.BOUNCE);
      }
      
    }
    
    function dragMarker(){
      geocoder = new google.maps.Geocoder();
      google.maps.event.addListener(marker, 'dragend', function() {
        geocodePosition(marker.getPosition());
      });
    }

    function geocodePosition(pos) {
      geocoder.geocode({
        latLng: pos
      }, function(responses) {
          if (responses && responses.length > 0) {
            $("#"+type+"_address").val(responses[0].formatted_address);
            $("#"+type+"_latitude").val(marker.position.lat());
            $("#"+type+"_longitude").val(marker.position.lng());
            for (let i = 0; i < responses[0].address_components.length; i++) {
              if(responses[0].address_components[i]['types'][0] == 'postal_code'){
                $("#"+type+"_postal_code").val(responses[0].address_components[i]['short_name']);                
              }
              if(responses[0].address_components[i]['types'][0] == 'locality'){
                $("#"+type+"_city").val(responses[0].address_components[i]['short_name']);                
              }
              if(responses[0].address_components[i]['types'][0] == 'administrative_area_level_1'){
                $("#"+type+"_state").val(responses[0].address_components[i]['long_name']);                
              }
              if(responses[0].address_components[i]['types'][0] == 'country'){
                $("#"+type+"_country").val(responses[0].address_components[i]['long_name']);                
              }
              console.log(responses[0].address_components[i]['types'][0]);
            } 
        }
      });
    }
    marker.addListener("click", toggleBounce);     
    marker.addListener("position_changed", dragMarker);     

}

// submiting form data function
$("#finalsubmit").on("click",function(){
  $("#basicform").find(':input:disabled').removeAttr('disabled');
  var url = $('#basicform').attr('action');
  $.ajax({
    url: url,
    type: 'post',
    data: $("#basicform").serialize(),
    success: function(responses){
      alert('success');
      window.location.href = '../public/label-order/index';
        // window.location('');
        console.log(responses)
    },
    error: function(err){
        console.log(err);
    }
  });
});