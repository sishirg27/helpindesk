function user_profileScroll(theDiv) {
  // This function will be executed when the user scrolls the page.
  $(window).scroll(function(e) {
    // Get the position of the location where the wrapper_leaderboard starts.
    var scroller_anchor = $(".scroller_anchor").offset().top;

    if(theDiv === '.user_profile'){}
     else {
      $(theDiv).css('left',-$(window).scrollLeft()); // Makes the div position fixed when vertical scrolling while absolute while horizontal scrolling
    }

    // Check if the user has scrolled and the current position is after the wrapper_leaderboard start location and if its not already fixed at the top
    if ($(this).scrollTop() >= scroller_anchor && $(theDiv).css('position') != 'fixed')
    {    // Change the CSS of the wrapper_leaderboard to hilight it and fix it at the top of the screen.
      if(theDiv === '.user_profile'){
        $(theDiv).css({
          //  'background': '#CCC',
          //'border': '1px solid #000',

          'position': 'fixed',
          'top': '125px',
          'transition': 'all 0.5s ease'
        });
      }

      else if(theDiv === '.wrapper_leaderboard'){

      $(theDiv).css('left',-$(window).scrollLeft());

        $(theDiv).css({
          //  'background': '#CCC',
          //'border': '1px solid #000',

           'position': 'fixed',
           'top': '75px',
          'transition': 'all 0.5s ease'
        });
      }

      else if(theDiv === '#questiontxt'){  // this is for the image diss_posts in anspage

      $(theDiv).css('left',-$(window).scrollLeft());

        $(theDiv).css({
          //  'background': '#CCC',
          //'border': '1px solid #000',

           'position': 'fixed',
           'top': '75px',
          'transition': 'all 0.5s ease'
        });
      }

      else if(theDiv === '#queries_stats'){
        $(theDiv).css({
          // 'background': 'red',
          //'border': '1px solid #000',
          'position': 'fixed',
          'top': '80px',
          'width': '300px',

         'transition': 'all 0.5s ease',
        });
      }

      // Changing the height of the wrapper_leaderboard anchor to that of wrapper_leaderboard so that there is no change in the overall height of the page.
      $('.scroller_anchor').css('height', '50px');
    }
    else if ($(this).scrollTop() < scroller_anchor && $(theDiv).css('position') != 'relative')
    {    // If the user has scrolled back to the location above the wrapper_leaderboard anchor place it back into the content.

      // Change the height of the wrapper_leaderboard anchor to 0 and now we will be adding the wrapper_leaderboard back to the content.
      $('.scroller_anchor').css('height', '0px');

      // Change the CSS and put it back to its original position.
      $(theDiv).css({
        //  'background': '#FFF',
        //  'border': '1px solid #CCC',
        'position': 'relative',
        'top': '0px'

      });
    }
  });
}

function loadMoreContext(id, text, flag, linkUrl, idDiv) {
  var txt;
  $(window).data('ajaxready', true).scroll(function(e) {
    if ($(window).data('ajaxready') == false) return;

    if ($(window).scrollTop() >= ($(document).height() - $(window).height())) {
    //$('#loadmoreajaxloader').show();
      $(window).data('ajaxready', false);
      if(text === 'cateid'){
        var txt = {
          cateid: id,
          offset: flag,
          limit: 10,
        };
      }
      else if(text === 'user_id'){
        var txt = {
          user_id: id,
          offset: flag,
          limit: 10,
        }
      }

      $.ajax({
        cache: false,
        url:  linkUrl+".php?f=que",
        data: txt,

        success: function(html) {
          if (html) {
            flag += 10;
            $(idDiv).append(html);
            $('#loadmoreajaxloader').hide();
          } else {
            //$('#loadmoreajaxloader').show();
          }
          $(window).data('ajaxready', true);
        }
      });
    }
  });
}
