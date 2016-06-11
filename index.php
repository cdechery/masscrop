<?php
	$ini = parse_ini_file('./config.ini');
?>
<script type="text/javascript" src="jquery-1.11.3.min.js"></script>
<script type="text/javascript">
var to_crop_data = {};
var img_max_width = <?php echo $ini['img_max_width']?>;

$(function() {
	var coords = [];

    $('canvas').click(function(e) {
		var ctx = this.getContext('2d');
		// alert( event.offsetX );

        ctx.rect(event.offsetX, event.offsetY, 4, 4);
        ctx.fillStyle = "<?php echo $ini['marker_color']?>";
        ctx.fill();

    	to_crop_data[ this.img ] = { 'x' : event.offsetX, 'y' : event.offsetY }
    });

    $('#do_crop').submit(function( e ) {
		e.preventDefault();

		var j = JSON.stringify( to_crop_data );

		$.post($("#do_crop").attr("action"),
			{ to_crop_data : j }, function(data) {
				$('#btnSubmit').prop('disabled', true);
				$('#btnSubmit').val('REFRESH TO SELECT NEW FILES TO CROP');
				alert ( data );
		}).fail( function() { alert('GENERAL FAILURE!'); } );
		return false;
	});
});
</script>
<body>
<form id="do_crop" method="post" action="do_crop.php">
<?php
	$cropped_files = file('cropped_files.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

	$pics = array();
	$id = 1;
	if ($handle = opendir('./to_crop')) {
		while (false !== ($entry = readdir($handle))) {
			if ($entry != "." && $entry != "..") {
				if( in_array($entry, $cropped_files) ) {
					continue; 
				} else if (stristr($entry, ".jpg")) {
					echo "<canvas id=\"".($id)."\"></canvas>\n";
					$id++;
					$pics[] = "'".$entry."'";
				}
			}
		}
		closedir($handle);
	}
	if( count($pics)==0 ) {
		echo "No new files to crop. Check your <i>cropped_files.txt</i> file.";
	}
?>
<p><input type="submit" id="btnSubmit" value="      DO THE CROP!      " action="do_crop.php"></p>
<div id="result"></div>
</form>
<script type="text/javascript">
	var pics = [ <?php echo implode($pics, ",")?> ];
	function loadImage( i ) {
	    var img = new Image();
	    var canvas = $( '#'+(i+1) )[0];
	    canvas.img = pics[i];
	    canvas.postop = 0;
	    var ctx = canvas.getContext('2d');
	    var h, w, pct, prop = 0;

	    img.onload = function() { 
	    	w = this.width;
	    	h = this.height;

	    	pct = img_max_width / w;
	    	h = h * pct;
	    	console.log('h='+h);

		    canvas.width = img_max_width;
		    canvas.height = h;

	        ctx.drawImage(img, 0, 0, img_max_width, h);
	    };
	    img.src = './to_crop/'+pics[i];
	}

	for(var i=0; i < pics.length; i++) {
	     loadImage( i );
	}
</script>
</body>