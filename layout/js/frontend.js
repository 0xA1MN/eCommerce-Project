$(function() {
  "use strict";

  // GENERAL FUNCTIONS
  // hide placeholder
  $("[placeholder]").focus(function() {
    $(this).attr("data-text", $(this).attr("placeholder"));
    $(this).attr("placeholder", "");
  }).blur(function() {
    $(this).attr("placeholder", $(this).attr("data-text"));
  });

  // Add * on required field
  $("input").each(function() {
    if ($(this).attr("required") === "required") {
      $(this).after('<span class="asterisk">*</span>');
    }
  });

  // convert pass field to text on hover
  $(".show-pass").hover(function(){
      $(".show-pass-filed").attr("type", "text");
    }, function(){
      $(".show-pass-filed").attr("type", "password");
  });

  // Confirmation Message on Button
  $(".confirm").click(function() {
    return confirm("Are You Sure");
  });

  // start Login page
  // switch between login and signup 
  $(".login-page h1 span").click(function(){
    $(this).addClass("selected").siblings().removeClass("selected");
    $(".login-page form").hide();
    $("." + $(this).data("class")).fadeIn(100);
  });
  // end Login page
  
  // start New Ad page
  $(".live").keyup(function(){
    $($(this).data("class")).text($(this).val());
    // selector from data-class to class of target
  });
  // end New Ad page

});