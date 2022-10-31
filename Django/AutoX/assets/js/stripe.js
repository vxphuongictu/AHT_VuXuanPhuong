$(document).ready(function (){
   // add stripe option to payment method
   $('body').on('click', '.payment-method .box', function (){
      $('#payment_method').append('<option value="stripe">Stripe</option>');
   })
});