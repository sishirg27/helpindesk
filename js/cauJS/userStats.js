function user_queries() {
  $.ajax({
    type: 'GET',
    url: 'ext_functions/homefunction.php?f=queries',
    success:function(data){
       $('#q_txt').html(data);
    }
  });
}

function user_bans() {
  $.ajax({
    type: 'GET',
    url: 'ext_functions/homefunction.php?f=ans',
    success:function(data){
      $('#q_ans').html(data);
    }
  });
}

function user_rank(){
  $.ajax({
    type: 'GET',
    url: 'ext_functions/homefunction.php?f=uRank',
    success:function(data){
      $('#u_rank').html(data);
    }
  });
}

// function to display the selected class on Your Caucuses()
function text_uclass(type, msgclass){
  $('.caucus_user').html(msgclass);
}

function get_uclass(){
  $.ajax({
    type: 'GET',
    url: 'userfunctions/pecaucus.php',
    async: true,
    cache: false,
    timeout: 50000,

    success:function(data){
      text_uclass("new", data);
      setTimeout(get_uclass,1000);
    },

    error: function(XMLHttpRequest, textStatus, errorThrown){
      text_uclass("error", textStatus + " (" + errorThrown + ")");
      setTimeout(get_uclass,15000);
    }
  });
}

function corrBtnClick(){
$('.editReal').click(function (){
hideshowEdit();
$('.modifytxt').html('Full Name');
});

$('.editReal1').click(function (){
hideshowEdit();
$('.modifytxt').html('User Name');
});

$('.editReal2').click(function (){
hideshowEdit();
$('.modifytxt').html('Password');
$("#newProf").attr('type', 'password');
$("#cnewProf").attr('type', 'password');
});

$('.editReal3').click(function (){
hideshowEdit();
$('.modifytxt').html('Email Address');
$('#currProf').attr('type', 'email');
$("#newProf").attr('type', 'email');
$("#cnewProf").attr('type', 'email');
});
}
