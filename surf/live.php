<?php
	function formatSeconds( $seconds ) {
		$milliseconds = str_replace( "0.", '', $seconds - floor( $seconds ) );

		return intval( gmdate( 'i', $seconds ) )
			. gmdate( ':s', $seconds )
			. ($milliseconds ? ".$milliseconds" : '')
		;
	}

	$server = "amrcommunity.com";
	$user   = "amrcommunity";
	$pass   = "PSJcxV6NKg";
	$db_name= "amrcommunity_surf";

	//create connection
	$conn = mysqli_connect( $server, $user, $pass, $db_name );

	//check connection
	if ( !$conn ) {
		die( "Connection failure: " . mysqli_connect_error() );
	}

	$sql = "SELECT *\n"
	    . "FROM ck_playertimes\n"
	    . "WHERE runtimepro = (SELECT min(runtimepro) FROM ck_playertimes AS f WHERE f.mapname = ck_playertimes.mapname)\n"
	    . "ORDER BY mapname ASC";
	$result = mysqli_query( $conn, $sql );

	echo "<div class='row'>";
	echo "<table class='results'>";

	while ( $row = mysqli_fetch_array( $result ) ) {
		if ( file_exists( $row[ "mapname" ] . ".jpg" ) ) {
			$ico = $row[ "mapname" ] . ".jpg";
		} elseif ( file_exists( $row[ "mapname" ] . ".png" ) ) {
			$ico = $row[ "mapname" ] . ".png";
		} else {
			$ico = "placeholder.jpg";
		}

		echo "<tr>";
		echo "<td class='map'>";
		echo "<div class='media'>";
		echo "<div class='media-left'>";
		echo "<img class='media-object img-rounded' src='../images/" . $ico . "' width='32' height'32'>";
		echo "</div>";
		echo "<div class='media-body'>";
		echo $row[ "mapname" ];
		echo "</div>";
		echo "</td>";
		echo "<td class='name'>";
		echo $row[ "name" ];
		echo "</td>";
		echo "<td class='runtime'>";
		echo formatSeconds( $row[ "runtimepro" ] );
		echo "</td>";
		echo "</tr>";
	}

	echo "</table>";
	echo "</div>";
	mysqli_close( $conn );
?>