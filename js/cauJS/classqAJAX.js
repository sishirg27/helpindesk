/***********************************
* INSERTINGQUESTION PRIVATE
***************************************/

 //$.getScript("scrollAnchor.js");

 /*function loadMoreContext(cate_id, flag) {
   $(window).data('ajaxready', true).scroll(function(e) {
     if ($(window).data('ajaxready') == false) return;

     if ($(window).scrollTop() >= ($(document).height() - $(window).height())) {
          $('#loadmoreajaxloader').show();

       $(window).data('ajaxready', false);
       $.ajax({
         cache: false,
         url:  "classfunctions.php?f=que",
         data: {
           'cateid': cate_id,
           'offset': flag,
           'limit': 10,
         },

         success: function(html) {
           if (html) {
             flag += 10;
             $('#diss_queries').append(html);
             $('#loadmoreajaxloader').hide();

           } else {
             $('#loadmoreajaxloader').show();
           }
           $(window).data('ajaxready', true);
         }
       });
     }
   });
 } */



 function submitsecret_query(u_id,c_id,s_id){
  var userid = u_id;
   var cate_id = c_id;
   var sub_id = s_id;
   var query = CKEDITOR.instances.editor_priquestion.getData();
   var sendsecret_query = $('#secret_select').val();
   $.ajax({
     type: 'POST',
     url: 'classfunctions.php?f=ins_quePri',
     data: {
       'submit_secret': 1,
       'query_secret':query,
       'userid_secret': userid,
       'cateid_secret': cate_id,
       'subid_secret' : sub_id,
       'secret_senduser':sendsecret_query
     },
     success:function(data){
       window.location.reload(); // This is not jQuery but simple plain ol' JS
     }
   });
 }

 function submit_query(u_id,c_id,s_id){
   var userid = u_id;
   var cate_id = c_id;
   var sub_id = s_id;
   //  var query = $('#fname').val();
   var query = CKEDITOR.instances.editor_question.getData();

   $.ajax({
     type: 'POST',
     url: 'classfunctions.php?f=ins_que',
     data: {
       'submit_query': 1,
       'query':query,
       'userid': userid,
       'cateid':cate_id,
       'subid': sub_id
     },
     success:function(data){
       window.location.reload(); // This is not jQuery but simple plain ol' JS
     }
   });
 }


function getPosts(cate_id) {
  var flag = 0;
  var c_id = cate_id;
  var link = 'classfunctions';
  var div = '#diss_queries';

  $.ajax({
    type: 'GET',
    url: 'classfunctions.php?f=que',
    data: {
      'cateid':c_id,
      'offset': 0,
      'limit': 10,
    },

    success:function(data) {
      $('#diss_queries').html(data);
      flag += 10;
      $('#loadmoreajaxloader').hide();
      loadMoreContext(c_id,'cateid',flag,link,div);
    },
  });
}


function getLeaders(c_id) {
  var cate_id = c_id;

  $.ajax({
    type: 'GET',
    url: 'classfunctions.php?f=leaders',
    data: {
      'c_id': cate_id
    },

    success:function(data){
      $('#leaders').html(data);
    }
  });
}
