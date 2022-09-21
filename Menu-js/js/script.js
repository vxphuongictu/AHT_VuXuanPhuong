$(document).ready(function(){
  $('.one-stop-service').mouseover(function(event) {

    $('.sub-menu').css({'display': 'block'})
    $('.one-stop-service a').css({'border-bottom': '1px solid blue', 'color': 'blue'});

  }).mouseout(function(event) {
      $('.sub-menu').css({'display': 'none'});
      $('.one-stop-service a').css({'border-bottom': '', 'color': ''});
    });

  $('.sub-menu').mouseover(function(){
      $('.sub-menu').css({'display': 'block'});
      $('.one-stop-service a').css({'border-bottom': '1px solid blue', 'color': 'blue'});
  }).mouseout(function(event) {
      $('.sub-menu').css({'display': 'none'});
      $('.one-stop-service a').css({'border-bottom': '', 'color': ''});
    });
});