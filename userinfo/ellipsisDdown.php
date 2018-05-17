
<button onclick='myFunctionopt<?php echo $post_id; ?>()' class='dropbtnopt' style = 'margin-right: 5px; font-size: 22px; font-weight: 600;margin-top: 0px;outline: none; float: right;'>   &#8942;  </button>

<div id='myDropdownopt<?php echo $post_id; ?>' class='dropdown-contentopt'>
<button type='button' name='deletequery'  id = 'deletemyquery' class = 'dQue<?php echo $post_id?>'>Delete</button>
</div>

<script>
function myFunctionopt<?php echo $post_id; ?>() {
  document.getElementById('myDropdownopt<?php echo $post_id?>').classList.toggle('show');
}


window.onclick = function(event) {
  if (!event.target.matches('.dropbtnopt')) {
    var dropdowns = document.getElementsByClassName('dropdown-contentopt');
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}
</script>
