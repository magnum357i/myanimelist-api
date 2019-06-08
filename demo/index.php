<html>
<head>
	<title>MyAnimeList Data Receiving Form</title>
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
	<script src="jquery.toggleFormElement.js"></script>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
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

						$( '[data-toggle="tooltip"]' ).tooltip();
						$( '[data-toggle="popover"]' ).popover();

						$( 'html, body' ).animate( { scrollTop: $( '.ajax-content' ).offset().top }, 300 );
					},
				} );
			} );

			$( '[data-toggleformelement]' ).toggleFormElement();
		} );
	</script>
</head>
<body>

<?php
error_reporting( E_ALL );
ini_set( 'display_errors', 1 );
?>

<h1 class="text-center">MyAnimeList API</h1>
<div class="h5 text-danger text-center">by Magnum357</div>

<br>

<div class="border p-3 rounded bg-light mx-auto" style="width: 800px;">
	<h5>Get anime, manga, character or people data</h5>
	<hr>
	<form class="mb-0" action="submit.php" method="POST" id="malSearch">
		<div class="row">
			<div class="col-sm form-group">
				<div>
					<div class="mb-2"><strong>TYPE</strong></div>
					<div class="custom-control custom-radio">
						<input
						data-toggleformelement
						data-toggleformelement-groups="malType"
						data-toggleformelement-target="#selectMalTypePage,#broadcastSetting"
						checked
						type="radio"
						name="malType"
						class="custom-control-input"
						value="p"
						id="malTypePage">
	 					<label class="custom-control-label" for="malTypePage">Page</label>
					</div>
					<div class="custom-control custom-radio">
						<input
						data-toggleformelement
						data-toggleformelement-groups="malType"
						data-toggleformelement-target="#selectMalTypeSearch"
						type="radio"
						name="malType"
						class="custom-control-input"
						value="q"
						id="malTypeSearch">
	 					<label class="custom-control-label" for="malTypeSearch">Search</label>
					</div>
					<div class="custom-control custom-radio">
						<input
						data-toggleformelement
						data-toggleformelement-groups="malType"
						data-toggleformelement-target="#selectMalTypeWidget"
						type="radio"
						name="malType"
						class="custom-control-input"
						value="w"
						id="malTypeWidget">
	 					<label class="custom-control-label" for="malTypeWidget">Widget</label>
					</div>
				</div>
				<br>
				<div id="selectMalTypePage">
					<div class="mb-2"><strong>CATEGORY</strong></div>
					<select class="form-control" name="malCategory1" id="malCategory1">
						<option value="a">Anime</option>
						<option value="m">Manga</option>
						<option value="c">Character</option>
						<option value="p">People</option>
					</select>
					<br>
					<div class="mb-2"><strong>ID</strong></div>
					<input type="number" min="1" class="form-control" name="malId" id="malId" required>
				</div>
				<div id="selectMalTypeSearch">
					<div class="mb-2"><strong>CATEGORY</strong></div>
					<select class="form-control" name="malCategory2" id="malCategory2">
						<option value="a">Anime</option>
						<option value="m">Manga</option>
						<option value="c">Character</option>
						<option value="p">People</option>
					</select>
					<br>
					<div class="mb-2"><strong>TEXT</strong></div>
					<input type="text" class="form-control" name="malQuery" id="malQuery" required>
				</div>
				<div id="selectMalTypeWidget">
					<div class="mb-2"><strong>TYPE</strong></div>
					<select class="form-control" name="malCategory3" id="malCategory3">
						<option value="n">New Anime</option>
						<option value="u">Upcoming Anime</option>
						<option value="c">Anime Calendar</option>
					</select>
				</div>
			</div>
			<div class="col-sm">
				<div>
					<div class="mb-2"><strong>SETTINGS</strong></div>
					<div class="custom-control custom-checkbox">
	 					<input type="checkbox" name="reversename" class="custom-control-input" id="reverseName" checked>
	 					<label class="custom-control-label" for="reverseName">Reverse name</label>
					</div>
					<div class="custom-control custom-checkbox">
	 					<input type="checkbox" name="bigimages" class="custom-control-input" id="bigImages" checked>
	 					<label class="custom-control-label" for="bigImages">Use big images</label>
					</div>
					<div class="custom-control custom-checkbox">
	 					<input type="checkbox" name="enablecache" class="custom-control-input" id="enableCache" checked>
	 					<label class="custom-control-label" for="enableCache">Enable cache</label>
					</div>
					<div class="form-group row mb-0">
						<div class="col-sm-auto col-form-label">
							Expired time
						</div>
						<div class="col-sm">
							<input type="number" min="0" value="2" class="form-control" name="expiredtime" id="expiredTime">
						</div>
					</div>
					<div id="broadcastSetting" class="form-group row mb-0 mt-2">
						<div class="col-sm-auto col-form-label">
							Timezone for broadcast
						</div>
						<div class="col-sm">
							<input type="text" class="form-control" value="America/Los_Angeles" name="timezone" id="timeZone">
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="submitButton" class="mb-0"></div>
	</form>
</div>
<div class="ajax-content"></div>
</body>
</html>