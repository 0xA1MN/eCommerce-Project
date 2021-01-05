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

  
  // DASHBOARD
  $(".toggle-info").click(function() {
    $(this).toggleClass("selected").parent().next(".card-body").fadeToggle(100);
    if ($(this).hasClass("selected")) {
      $(this).html('<i class="fa fa-plus fa-lg toggle-info"></i>')
    } else {
      $(this).html('<i class="fa fa-minus fa-lg toggle-info"></i>')
    }
  })

  // CATEGORY
  // category view option
  $(".categories .cat h3").click(function() {
    $(this).next(".full-view").fadeToggle(200);
  });
  
  // category view option
  $(".option span").click(function() {
    $(this).addClass("active").siblings("span").removeClass("active");
    
    if($(this).data("view") === "full") {
      $(".cat .full-view").fadeIn(200);
    } else {
      $(".cat .full-view").fadeOut(200);
    }
  });
  
});