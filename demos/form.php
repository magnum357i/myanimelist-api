<html>
<head>
	<title>MyAnimeList Data Receiving Form</title>
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="style.css">
	<script>
		$( document ).ready( function() {

			var form          = $( '#malSearch' );
			var infoElem      = $( '.ajax-content' );
			var buttonElem    = $( '#submitButton' );
			var normalButton  = '<button type="submit" class="btn btn-primary btn-block">Get Now</button>';
			var loadingButton = '<button class="btn btn-primary btn-block" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Getting... Please wait.</button>';

			buttonElem.html( normalButton );

			form.submit( function( e ) {

				e.preventDefault();

				buttonElem.html( loadingButton );

				$.ajax( {
					type: form.attr( 'method' ),
					url: form.attr( 'action' ),
					data: form.serialize(),
					success: function (data) {

						infoElem.html( data );
						buttonElem.html( normalButton );
					},
				} );
			} );
		} );
	</script>
</head>
<body>

<?php include( '../autoload.php' ); ?>

<h1 class="text-center">MyAnimeList API</h1>
<div class="h5 text-danger text-center">by Magnum357</div>

<br>

<div class="border p-3 rounded bg-light">
	<h5>Get anime, manga, character or people info</h5>
	<hr>
	<form class="mb-0" action="submit.php" method="POST" id="malSearch">
		<div class="form-group">
			<div class="row mb-0">
				<div class="col-sm mb-0">
					<label for="malId"><strong>ID</strong></label>
					<input type="number" min="1" class="form-control" name="malId" id="malId" required>
				</div>
				<div class="col-sm mb-0">
					<label for="malType"><strong>TYPE</strong></label>
					<select class="form-control" name="malType" id="malType">
						<option value="a">anime</option>
						<option value="m">manga</option>
						<option value="c">character</option>
						<option value="p">people</option>
					</select>
				</div>
			</div>
		</div>
		<div id="submitButton" class="mb-0"></div>
	</form>
</div>
<div class="ajax-content"></div>
</body>
</html>