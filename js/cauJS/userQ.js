/*function scrollInf(user_id,fg){
  $(window).data('ajaxready', true).scroll(function(e) {
    if ($(window).data('ajaxready') == false) return;

    if ($(window).scrollTop() >= ($(document).height() - $(window).height())) {
      $('#loadmoreajaxloader').show();
      $(window).data('ajaxready', false);
      $.ajax({
        cache: false,
        url:  "queryfunction.php?f=que",
        data: {
          'user_id': u_id,
          'offset': flag,
          'limit': 10,
        },

        success: function(html) {
          if (html) {
            flag += 10;
            $('#diss_queries_Science').append(html);
            $('#loadmoreajaxloader').hide();

          } else {
            $('#loadmoreajaxloader').hide();
          }
          $(window).data('ajaxready', true);
        }
      });
    }
  });
} */

function getResolvedNum(user_id) {
  var u_id = user_id;

  $.ajax({
    cache: false,
    type: 'GET',
    url: 'queryfunction.php?f=resNum',

   data: {
     'user_id': u_id
   },

   success:function(data){
      $('#resQuenum').html(data);
   }
  });
}

function uQue(user_id){
  var u_id = user_id;
  var flag = 0;
  var link = 'queryfunction';
  var div = '#diss_queries_Science';

  $.ajax({
    type: 'GET',
    url: 'queryfunction.php?f=que',
    data: {
      'user_id': u_id,
      'offset': 0,
      'limit': 10,
    },
    success:function(data){
      if (!$.trim(data)){
        $('#noquery').show();
        $('#loadmoreajaxloader').hide();
      }
      else {
        $('#diss_queries_Science').html(data);
        flag += 10;
        $('#loadmoreajaxloader').hide();
      }
    loadMoreContext(u_id,'user_id',flag,link,div);
    }
  });
}

function toggleQue() {
  $('.priQue').click(function (){
    $('.pubQue').css('border-color', 'lightgray');
    $('.priQue').css('border-color', 'blue');
  });

  $('.pubQue').click(function (){
    $('.priQue').css('border-color', 'lightgray');
    $('.pubQue').css('border-color', 'blue');
  });

  $('.transfer').click(function() {
    $('#first').show();
    $('#second').hide();
    $('.transfer').css('font-weight', 'bold');
    $('.transfer1').css('font-weight', 'lighter');
    $('.transfer1').css('color', 'lightgray');
    $('.transfer').css('color', 'blue');
  });

  $('.transfer1').click(function (){
    $('#second').show();
    $('#first').hide();
    $('.transfer1').css('font-weight', 'bold');
    $('.transfer').css('font-weight', 'lighter');
    $('.transfer').css('color', 'lightgray');
    $('.transfer1').css('color', 'blue');
  });
}
