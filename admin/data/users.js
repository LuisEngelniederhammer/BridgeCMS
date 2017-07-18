function listUsers(limit) {
	$("#main").html("<img src='data/load.svg'>");
	$.ajax({
		method : "POST",
		url : "users.process.php",
		data : {
			request : "list",
			start: limit
		}
	}).done(function(msg) {

		$("#main").html(msg);

	});
}
function editUser(userID) {
	$("#main").html("<img src='data/load.svg'>");
	$.ajax({
		method : "POST",
		url : "users.process.php",
		data : {
			request : "edit",
			user : userID
		}
	}).done(function(msg) {

		$("#main").html(msg);

	});
}
function updateUser(userID, updateSerializedArray) {
	$("#main").html("<img src='data/load.svg'>");
	$.ajax({
		method : "POST",
		url : "users.process.php",
		data : {
			request : "update",
			user : userID,
			updates : updateSerializedArray
		}
	}).done(function(msg) {

		listUsers(0)

	});
}
function createForm() {
	$("#main").html("<img src='data/load.svg'>");
	$.ajax({
		method : "POST",
		url : "users.process.php",
		data : {
			request : "createForm"
		}
	}).done(function(msg) {

		$("#main").html(msg);

	});
}
function createUser(name, permissions) {
	$("#main").html("<img src='data/load.svg'>");
	$.ajax({
		method : "POST",
		url : "users.process.php",
		data : {
			request : "createUser",
			name : name,
			perm : permissions
		}
	}).done(function(msg) {

		listUsers()

	});
}