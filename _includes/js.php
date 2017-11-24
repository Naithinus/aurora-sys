
	<script id="datatableScript">
	function cb(start, end)
	{
			$('#reportrange span').html(start.format('D MMMM, YYYY') + ' - ' + end.format('D MMMM, YYYY'));
	}

	cb(moment().subtract(30, 'days'), moment());

    $('#reportrange').daterangepicker({
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);



	$('#reportrange').on('apply.daterangepicker', function(ev, picker) {

		  $('#loading').show();
		  $('#sends').html('');

		  var startDate = picker.startDate.format('YYYY-MM-DD');
		  var endDate   = picker.endDate.format('YYYY-MM-DD');
		  var page = "original";

		  $.post('getSendsByInterval.php',{startDate : startDate , endDate : endDate, page : page},function(data){

			  $('#loading').hide();
			  $('#sends').html(data);

			  $('.datatable-basic').on( 'draw.dt', function () {






$(".btnSend").unbind( "click" );
$(".btnTestSend").unbind( "click" );
$(".btnEditIPS").unbind( "click" );
$(".btnSend").unbind( "click" );
$(".btnStop").unbind( "click" );
$(".btnCopy").unbind( "click" );


$('.btnSend').click(function(){
   var divLoading = $(this).children();

   divLoading.html('').html('<img src="loading.gif" style="width:15px;height:15px;"/>');
   var idSend   = $(this).attr('id');
   var Fraction = $(this).parent().prev().prev().prev().prev().children().val();
   var Seed     = $(this).parent().prev().prev().prev().children().val();
   var xDelay   = $(this).parent().prev().prev().children().val();

   if(xDelay=="")
	  xDelay = 0;

   var counterObject = $(this).parent().prev().children();
   //var currentCount = parseInt(counterObject.html());
   var newCount     = 0;

   $.post('RealSend.php',{id_Send:idSend,fraction:Fraction,seed:Seed,xDelay:xDelay},function(data){
      alert(data);
	  divLoading.html('').html('<i class="icon-target2"></i>');

	  var countListExplode      = data.split('/');
	  var countListFinal        = parseInt(countListExplode[1]);

	  if(countListFinal < 0)
		  countListFinal = 0;
	  counterObject.html(countListFinal);
   });

});
	</script>
