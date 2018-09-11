<?php echo "<script>
setTimeout('setscrl()', 400);
function setscrl(){
var ascrl = 0".$_GET['p3'].";
if (ascrl>0) {
  document.getElementById(\"vpdiv\").scrollTop = 0-(0-document.getElementById('hash_tp_".$_GET['p3']."').parentNode.parentNode.offsetTop-document.getElementById('hash_tp_".$_GET['p3']."').parentNode.parentNode.parentNode.offsetTop-document.getElementById('hash_tp_".$_GET['p3']."').parentNode.parentNode.parentNode.parentNode.offsetTop)-90;
}
}
</script>"; ?>