function changetype(p_id,u_id){ // Connected with Resolve Question
  var post_id = p_id;
  var user_id = u_id;
  $.ajax({
    type: 'POST',
    url: 'classfunctions.php?f=ctype',
    data: {
      'p_id': post_id,
      'u_id': user_id
    },
    success:function(data){
       window.location.reload();
    }
  });
}

function get_question(p_id) {
  var post_id = p_id;
  $.ajax ({
    type: 'GET',
    url: 'classfunctions.php?f=singleque',
    data: {  
      'post_id': post_id
    },
    success:function(data){
      $('#questiontxt').html(data);
    }
  });
}

function get_comments(p_id,t_id) {
var post_id = p_id;
var type_id = t_id;
  $.ajax ({
    type: 'GET',
    url: 'classfunctions.php?f=discomm',
    data: {
      'post_id': post_id,
      'type_id': type_id
    },
    success:function(data){
      $('#caucus_answers').html(data);
    }
  });
}

function get_bestans(p_id){
 var post_id = p_id;

  $.ajax ({
    type: 'GET',
    url: 'classfunctions.php?f=bestans',
    data: {
      'post_id': post_id
    },
    success:function(data){
      $('#best_ans').html(data);
    }
  });
}

/***********************************
* JS FOR SUBMITTING THE COMMENT
***********************************/

function sub_comm(p_id,u_id,t_id){
  var post_id = p_id;
  var user_id = u_id;
  var type_id = t_id;
  var comment_co;

  if(type_id == 1) {
    comment_co = CKEDITOR.instances.pri_txtcmt.getData();
  }

  else if(type_id == 0) {
    comment_co = CKEDITOR.instances.textcmt.getData();
  }

 $.ajax({
    type: 'POST',
    url: 'classfunctions.php?f=comm',
    data: {
      'comm_post':1,
      'comment_comm': comment_co,
      'user_id': user_id,
      'post_id': post_id,
      'type_id': type_id
    },

    success:function(data){
     window.location.reload();

    }
  });
}
