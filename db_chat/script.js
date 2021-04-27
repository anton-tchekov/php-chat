var recvMsg = 0;

var sendUrl = "insert.php";
var recvUrl = "getchat.php";
var msgc;

window.addEventListener("load", function()
{
	var messageTB = document.getElementById("msgtb");
	var sendBtn = document.getElementById("sendbtn");
	var message = "";

	msgc = document.getElementById("messagecont");

	sendBtn.addEventListener("click", function()
	{
		messageTB.focus();
		message = messageTB.value;
		if(message.length > 0)
		{
			send(message);
			messageTB.value = "";
		}
	}, false);

	messageTB.addEventListener("keydown", function(e)
	{
		message = messageTB.value;
		if(e.keyCode == 13 && message.length > 0)
		{
			send(message);
			messageTB.value = "";
		}
	}, false);
}, false);

function send(msg)
{
	var datatypes = document.getElementsByName('dttype');
	var datatype_value = 1;

	for(var i = 0; i < datatypes.length; i++)
	{
		if(datatypes[i].checked)
		{
			datatype_value = datatypes[i].value;
		}
	}

	var xhr = new XMLHttpRequest();
	var param = "message=" + encodeURI(msg) + "&dttype=" + datatype_value;
	xhr.open("POST", sendUrl);
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.send(param);
}

function receive(startfrom)
{
	var resultArray = [];

	var xhr = new XMLHttpRequest();
	var param = "startat=" + startfrom;
	xhr.open("POST", recvUrl);
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

	xhr.onreadystatechange = function()
	{
		if(xhr.readyState == 4 && xhr.status == 200)
		{
			var recvString = xhr.responseText;
			var json_enc = JSON.parse(recvString);
			var len = json_enc.length;

			for(var i = 0; i < len; i++)
			{
				var uName = json_enc[i].uname;
				var message = json_enc[i].msg;
				var timeDate = json_enc[i].dtsent;
				var dataType = json_enc[i].dttype;

				if(dataType < 2)
				{
					msgc.innerHTML +=
							"<tr><td>" + uName + ":</td><td>" + message + "</td><td>" + timeDate + "</td></tr>";
				}
				else if(dataType == 2)
				{
					msgc.innerHTML +=
							"<tr><td>" + uName + ":</td><td><a href='" + message + "'>" + message + "</a></td><td>" + timeDate + "</td></tr>";
				}
				else if(dataType == 3)
				{
					msgc.innerHTML +=
							"<tr><td>" + uName + ":</td><td><img src='" + message + "'></td><td>" + timeDate + "</td></tr>";
				}
			}

			recvMsg += len;

			document.getElementById("msgc").scrollTo(0, msgc.scrollHeight);
			setTimeout(function() {receive(recvMsg); }, 2000);
		}
	}

	xhr.send(param);
}

receive(recvMsg);
