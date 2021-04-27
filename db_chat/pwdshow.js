window.addEventListener("load", function()
{
	/* hide/show passwort */
	var chkbox = document.getElementById("showpwd");
	var pwdfield = document.getElementById("pwdfield");

	chkbox.addEventListener("change", function()
	{
		pwdfield.type = chkbox.checked ? "text" : "password";
	}, false);
}, false);
