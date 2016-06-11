<?php
	$ini = parse_ini_file('./config.ini');

	$left_offset = $ini['left_offset'];
	$top_offset = $ini['top_offset'];

	$rect_w = $ini['rect_w'];
	$rect_h = $ini['rect_h'];

	$img_max_width = $ini['img_max_width'];

	$to_crop_data = json_decode( $_POST['to_crop_data'], TRUE );

	$f = fopen('./cropped_files.txt', 'a');
	if( !$f ) {
		echo "Error: cannot open cropped_files.txt!";
		return;
	}

	$num_imgs = count($to_crop_data);
	$imgs_cropped = 0;
	foreach ($to_crop_data as $img_name => $coords) {
		$img = @imagecreatefromjpeg( "./to_crop/".$img_name );
		if( !$img ) {
			continue;
		}

		$orig_w = imagesx( $img );
		$orig_h = imagesx( $img );

		$pct = $img_max_width / $orig_w;
		$resized_h = $orig_h * $pct;

		$new_x = ( $orig_w * $coords['x'] ) / $img_max_width  - $left_offset;
		$new_y = ( $orig_h * $coords['y'] ) / $resized_h - $top_offset;

		$tmp_img = @imagecreatetruecolor( $rect_w, $rect_h );
		if( !$tmp_img ) { continue; }

		imagecopy( $tmp_img, $img , 0 , 0 , $new_x , $new_y , $rect_w , $rect_h );

		$ret = @imagejpeg($tmp_img, './cropped/'.$img_name, 100);
		if( $ret ) {
			fputs($f, $img_name."\n");
			$imgs_cropped++;
		}
		
		@imagedestroy( $img );
		@imagedestroy( $tmp_img );
	}
?>
Total images marked: <?php echo $num_imgs?> - 
Total images cropped: <?php echo $imgs_cropped?>