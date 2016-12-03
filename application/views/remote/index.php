<?php

Asset::style( 'main-css', 'main.min.css' )
	->script( 'main-js', 'main.min.js' );

$c = Config::get( 'player.players', 1 ) + 1;

if( empty( $irport ) ) $irport = 1;

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>TEST</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="initial-scale=1,user-scalable=yes" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<?= Asset::styles() ?>
		<?= Asset::scripts() ?>
		<script type="text/javascript">
			jQuery(document).ready(function ($) {                
				myRemote.init($, '<?= URL::to_route( 'macro' ) ?>'<?= empty( $macro ) ? '' : ', ' . json_encode( $macro ) ?>);
			});
		</script>
	</head>
	<body class="remote-form">
		<div class="container">
			<?= Form::open(); ?>

				<div class="row">
					<div class="grid-third">
<?php if( $c > 2 ) : ?>
						<fieldset class="radio">

<?php   for( $i = 1; $i < $c; $i++ ) : ?>
							<label class="input-radio input-inline">
								<input type="radio" name="player" value="<?= $i ?>"<?= $i == $irport ? ' checked="checked"' : '' ?> />
								<i></i><span>Player <?= $i ?></span>
							</label>
<?php   endfor; ?>
						</fieldset>
<?php else: ?>
						<input style="display: none;" type="radio" name="player" value="1" checked="checked" />
<?php   endif; ?>
						<div id="response">
							<div class="message"></div>
							<div class="timer" style="display: none;"></div>
						</div>
					</div>
					<div class="grid-third">
						<fieldset>
							<button class="btn-grn" data-cmd="poweron">ON</button>
							<button class="btn-red" data-cmd="poweroff">OFF</button>
							<button class="btn-blu" data-cmd="easyplay">EasyPlay</button>
						</fieldset>
						<fieldset>            
							<button class="btn-num" data-cmd="number1">1</button>
							<button class="btn-num" data-cmd="number2">2</button>
							<button class="btn-num" data-cmd="number3">3</button>
						</fieldset>
						<fieldset>
							<button class="btn-num" data-cmd="number4">4</button>
							<button class="btn-num" data-cmd="number5">5</button>
							<button class="btn-num" data-cmd="number6">6</button>
						</fieldset>
						<fieldset>
							<button class="btn-num" data-cmd="number7">7</button>
							<button class="btn-num" data-cmd="number8">8</button>
							<button class="btn-num" data-cmd="number9">9</button>
						</fieldset>
						<fieldset>
							<button class="btn-blu" data-cmd="open">Eject</button>
							<button class="btn-num" data-cmd="number0">0</button>
							<button class="btn-blu" data-cmd="flip">Flip</button>
						</fieldset>
					</div>
					<div class="grid-third">
						<fieldset>
							<button class="btn-blu" data-cmd="display">Display</button>
							<button class="btn-sym" data-cmd="up">&uarr;</button>
							<button class="btn-blu" data-cmd="menu">Menu</button>
						</fieldset>
						<fieldset>
							<button class="btn-sym" data-cmd="left">&larr;</button>
							<button class="btn-blu" data-cmd="enter">Enter</button>
							<button class="btn-sym" data-cmd="right">&rarr;</button>
						</fieldset>
						<fieldset>
							<button class="btn-blu" data-cmd="folder">Folder</button>
							<button class="btn-sym" data-cmd="down">&darr;</button>
							<button class="btn-blu" data-cmd="clear">Clear</button>
						</fieldset>
						<fieldset>
							<button class="btn-sym" data-cmd="rewind">&lt;&lt;</button>
							<button class="btn-sym" data-cmd="play">&gt;</button>
							<button class="btn-sym" data-cmd="fastforward">&gt;&gt;</button>
						</fieldset>
						<fieldset>
							<button class="btn-sym" data-cmd="pause">&par;</button>
							<button class="btn-sym" data-cmd="stop">[]</button>
							<button class="btn-blu" data-cmd="subtitle">SubTitle</button>
						</fieldset>
					</div>
				</div>
			<?= Form::close(); ?>

		</div>
	</body>
</html>