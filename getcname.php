<script>

try

{

var ax = new ActiveXObject("WScript.Network");

document.write('User: ' + ax.UserName + '<br />');

document.write('Computer: ' + ax.ComputerName + '<br />');

}

catch (e)

</script>