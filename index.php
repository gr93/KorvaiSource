<html>
<head>
    <title>Index</title>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/jquery.dataTables.css"/>
	<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.js"></script>	

	<div class="">
		<table id="korvais" class="display" width="100%" cellspacing="0">
        <thead>
            <tr>
				<th></th>
				<th>Aksharams</th>
				<th>Total Aksharams</th>
				<th>Talam/Eduppu</th>
				<th>Nadai</th>
				<th>Composer</th>
				<th>Korvai Text</th>
				<th>Recording</th>
				<th>Notes</th>               
            </tr>
        </thead>
    </table>
    </div>
	
	<script type="text/javascript">
		$( document ).ready(function() {
			var table = $('#korvais').DataTable( {
        		"processing": true,
        		"serverSide": false,
        		"ajax": "response.php"
    		});
		});
	</script>
	<script>
	function showUploadButton() {
		document.getElementById("uploadAudio").style.display = "block";
	}

	function hideUploadButton() {
		document.getElementById("uploadAudio").style.display = "none";
	}

	function showAudioRecordButton() {
		document.getElementById("recording").style.display = "block";
	}

	function hideAudioRecordButton() {
		document.getElementById("recording").style.display = "none";
	}

	</script>
	------------------------------------ 
	<br>
	<b>Add new korvai</b>:
	<form action="insertKorvai.php" method="post" enctype="multipart/form-data">
		Aksharams: <input type="text" name="aksharams"><br>
		Total aksharams: <input type="text" name="totalAksharams"><br>
		Talam/Edupppu <input type="text" name="talam"><br>
		Nadai: <input type="text" name="nadai"><br>
		Composer: <input type="text" name="composer"><br>
		Audio recording:
		<input type="radio" name="recordingOption" value="record" onclick="hideUploadButton(); showAudioRecordButton();"> Record
  		<input type="radio" name="recordingOption" value="upload" onclick="showUploadButton(); hideAudioRecordButton();"> Upload file<br>

		<span id="recording" style="display:none;">
			<head>
				<meta charset="UTF-8">
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<meta http-equiv="X-UA-Compatible" content="ie=edge">
				<title>Example 1</title>
			</head>
			<body>
				<div class="">
					<audio controls autoplay></audio>
					<br>
					<button type="button" class="btn waves-effect waves-light js-start">Start</button>
				<button type="button" class="btn waves-effect waves-light js-stop" disabled>Stop</button>
				<br>
				<br>
				</div>
				<pre style="font-family: GillSans, Calibri, Trebuchet, sans-serif;" class="hide"></pre>

				<script src="recorder.js"></script>
				<script src="https://cdn.webrtc-experiment.com/MediaStreamRecorder.js"> </script>
				<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
				<script class="containerScript">
					let recorder;
					let context;
					let audio = document.querySelector('audio');
					let startBtn = document.querySelector('.js-start');
					let stopBtn = document.querySelector('.js-stop');
					let pre = document.querySelector('pre');

					window.URL = window.URL || window.webkitURL;
					/** 
					 * Detecte the correct AudioContext for the browser 
					 * */
					window.AudioContext = window.AudioContext || window.webkitAudioContext;
					navigator.getUserMedia  = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;

					let onFail = function(e) {
						alert('Error '+e);
						console.log('Rejected!', e);
					};

					let onSuccess = function(s) {
						console.log('Recording...');
						let tracks = s.getTracks();
						startBtn.setAttribute('disabled', true);
						stopBtn.removeAttribute('disabled');
						context = new AudioContext();
						let mediaStreamSource = context.createMediaStreamSource(s);
						recorder = new Recorder(mediaStreamSource);
						recorder.record();

						stopBtn.addEventListener('click', ()=>{
							console.log('Stop Recording...');
							stopBtn.setAttribute('disabled', true);
							startBtn.removeAttribute('disabled');
							recorder.stop();
							tracks.forEach(track => track.stop());
							recorder.exportWAV(function(s) {
								audio.src = window.URL.createObjectURL(s);
								var oReq = new XMLHttpRequest();
								oReq.open("POST", 'createAudioRecording.php', true);
								oReq.setRequestHeader("content-type", "audio/wav");
								oReq.onload = function (oEvent) {
									// Uploaded.
								};
								oReq.send(s);
								console.log(audio.src);
							});
						});
					}

					startBtn.addEventListener('click', ()=>{
						if (navigator.getUserMedia) {
							/** 
							 * ask permission of the user for use microphone or camera  
							 */
							navigator.getUserMedia({audio: true}, onSuccess, onFail); 																			
						} else {
							console.warn('navigator.getUserMedia not present');
						}
					});

				</script>
			</body>
		</span>

		<span id="uploadAudio" style="display:none;"><input type="file" name="recording"><br></span>

		Korvai Text: <input type="file" name="korvaiText"><br>
		Notes: <input type="text" name="notes"><br><br>
		<input type="submit" value="Submit">
	</form>
</head>
</html>