/* Input elements */
var text_box_message = document.getElementById('text-box-message');
var text_box_name = document.getElementById('text-box-name');
var color_picker = document.getElementById('color-picker');

/* Send message events */
document.getElementById('send-button').addEventListener('click', function()
{
	send();
	text_box_message.focus();
},
false);

text_box_message.addEventListener('keydown', function(e)
{
	if(e.keyCode == 13)
	{
		send();
	}
}, false);

/* Send message */
function send()
{
	var message = text_box_message.value;
	// Nur senden wenn es was zum senden gibt.
	if(message.length > 0)
	{
		var xhr = new XMLHttpRequest();
		var name = text_box_name.value;
		var color = color_picker.value;
		xhr.open('POST', 'insert.php');
		xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		xhr.send(
			'color=' + encodeURI(color) +
			'&name=' + encodeURI(name) +
			'&message=' + encodeURI(message));

		text_box_message.value = '';
	}
}

/* Get messages every second */
function loadChatMessages(messageId)
{
	var resultArray = [];
	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'load.php');
	xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	xhr.onreadystatechange = function()
	{
		if(xhr.readyState == 4 && xhr.status == 200)
		{
			var messages = JSON.parse(xhr.responseText);
			for(message of messages)
			{
				var color, index;
				document.getElementById('message-container').innerHTML +=
					'<tr style=\"color: ' + message.color + '\">' +
						'<td>' + message.datetime + '</td>' +
						'<td>' + message.name + '</td>' +
						'<td>' + message.message + '</td>' +
					'</tr>';
			}

			messageId += messages.length;
			window.scrollTo(0, document.body.scrollHeight);
			setTimeout(function() { loadChatMessages(messageId); }, 1000);
		}
	}

	xhr.send('startat=' + messageId);
}

loadChatMessages(0);
