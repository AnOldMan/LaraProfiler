var myRemote = {
	msgTimeout: null,
	isMacro: false,
	msg: null,
	timer: null,
	radio: null,
	init: function($, url, cmd) {
		this.url = url;
		$wrapper = $('#response');
		this.msg = $('.message', $wrapper),
		this.timer = $('.timer', $wrapper);
		this.radio = $('input[name=player]');
		$('button').on('click',function (e) {
			var $this = $(this), data = $this.data();
			if (data && data.cmd) {
				e && (e.preventDefault());
				if (!myRemote.isMacro) { myRemote.sendCommand(data); }
				return false;
			}
		});
		if (cmd) {
			this.startTimer(cmd);
		}
	},
	sendCommand: function (cmd) {
		if (!cmd) { return; }
		cmd.player = this.radio.filter(':checked').val();
		$.ajax({
			type: 'POST',
			url: this.url,
			data: cmd,
			success: function (response) {
				if (response.sec && response.nxt) {
					myRemote.startTimer(response);
				}
				myRemote.showMessage(response.msg);
			},
			error: function () {
				this.msg.html(JSON.stringify(arguments));
			}
		});
	},
	showMessage: function (msg) {
		if (!msg) { return; }
		if (this.msgTimeout) {
			clearTimeout(this.msgTimeout);
			this.msgTimeout = null;
		}
		if (typeof msg != 'string') {
			msg = msg.join('<br/>');
		}
		this.msg.html(msg);
		this.msgTimeout = setTimeout( function () { myRemote.msg.html(''); myRemote.msgTimeout = null; }, 5000 );
	},
	startTimer: function (data) {
		myRemote.showMessage(data.msg);
		this.timer.show();
		this.isMacro = true;
		myRemote.countdown(data.sec, data.nxt);
	},
	countdown: function (sec, nxt) {
		if (!sec || sec < 1)
		{
			this.timer.hide();
			this.isMacro = false;
			myRemote.sendCommand(nxt);
			return;
		}
		this.timer.text(sec);
		setTimeout( function () { myRemote.countdown(sec - 1, nxt); }, 1000 );
	}
};