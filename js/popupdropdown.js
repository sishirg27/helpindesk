
jQuery(document).ready(function (e) {
  function t(t) {
    e(t).bind("click", function (t) {
      t.preventDefault();
      e(this).parent().fadeOut()
    })
  }

  e(".dropdown-toggle").click(function () {
    var t = e(this).parents(".button-dropdown").children(".dropdown-menu").is(":hidden");
    e(".button-dropdown .dropdown-menu").hide();
    e(".button-dropdown .dropdown-toggle").removeClass("active");
    if (t) {
      e(this).parents(".button-dropdown").children(".dropdown-menu").toggle().parents(".button-dropdown").children(".dropdown-toggle").addClass("active")
    }
  });

  e(document).bind("click", function (t) {
    var n = e(t.target);
    if (!n.parents().hasClass("button-dropdown")) e(".button-dropdown .dropdown-menu").hide();
  });
  e(document).bind("click", function (t) {
    var n = e(t.target);
    if (!n.parents().hasClass("button-dropdown")) e(".button-dropdown .dropdown-toggle").removeClass("active");
  })
});



/* When the user clicks on the button,
toggle between hiding and showing the dropdown content */
function myFunction() {
  document.getElementById("myDropdown").classList.toggle("show");
}

//  Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {

    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}



jQuery(document).ready(function($) {
  //open popup
  $(".cd-popup-trigger").on("click", function(event) {
    event.preventDefault();
    $(".cd-popup").addClass("is-visible");
  });

  //close popup
  $(".cd-popup").on("click", function(event) {
    if (
      $(event.target).is(".cd-popup-close") ||
      $(event.target).is(".cd-popup")
    ) {
      event.preventDefault();
      $(this).removeClass("is-visible");
    }
  });

  //close popup when clicking the esc keyboard button
  $(document).keyup(function(event) {
    if (event.which == "27") {
      $(".cd-popup").removeClass("is-visible");
    }
  });

// ********************************
// Popup for ask a question
//****************************

$(".cd-popup-question").on("click", function(event) {
  event.preventDefault();
  $(".cd-question").addClass("is-visible");
});

//close popup
$(".cd-question").on("click", function(event) {
  if (
    $(event.target).is(".cd-question-close") ||
    $(event.target).is(".cd-question")
  ) {
    event.preventDefault();
    $(this).removeClass("is-visible");
  }
});

//close popup when clicking the esc keyboard button
$(document).keyup(function(event) {
  if (event.which == "27") {
    $(".cd-question").removeClass("is-visible");
  }
});
});
