<!-- <script src="/js/tinymce/tinymce.min.js"></script>   -->
  
<style>
.draggable
{
  cursor: pointer;
}
</style>

<div class="container">
        <h3 class="text-center">Dynamic Drag and Drop table rows in PHP Mysql - ItSolutionStuff.com</h3>
        <table class="table table-bordered tbl">
            <tr class='first'> 
                <td>#</td>
                <td>Name</td>
                <td>Defination</td>
            </tr>
            <tbody class="draggable">
                <tr id="1">
                    <td class='Field'>1_1</td>
                    <td class='Field'>1_2</td>
                    <td class='Field'>1_3</td>
                </tr>
                <tr id="2">
                    <td class='Field'>2_1</td>
                    <td class='Field'>2_2</td>
                    <td class='Field'>2_3</td>
                </tr>
                <tr id="3">
                    <td class='Field'>3_1</td>
                    <td class='Field'>3_2</td>
                    <td class='Field'>3_3</td>
                </tr>
                <tr id="4">
                    <td class='Field'>4_1</td>
                    <td class='Field'>4_2</td>
                    <td class='Field'>4_3</td>
                </tr>
            </tbody>
        </table>
    </div> <!-- container / end -->

<script>
$( function() 
{
    $( ".draggable" ).sortable({
        delay: 150,
        stop: function() {
            var selectedData = new Array();
            $('.draggable > tr').each(function() {
                selectedData.push( $( this ).attr( "id" ));
            });
            updateOrder(selectedData);
        }
    });

    function updateOrder(data) {
        $.ajax({
            url:"/project/test/ajaxPro.php",
            type:'post',
            data:{ position : data },
            success:function()
            {
                alert('your change successfully saved');
            }
        })
    }
} );
</script>


