function listRoles() {
	$("#main").html("<img src='data/load.svg'>");
	$.ajax({
		method : "POST",
		url : "roles.process.php",
		data : {
			request : "list"
		}
	}).done(function(msg) {

		$("#main").html(msg);

	});
}
function showRole(roleID) {
	$("#main").html("<img src='data/load.svg'>");
	$.ajax({
		method : "POST",
		url : "roles.process.php",
		data : {
			request : "edit",
			role : roleID
		}
	}).done(function(msg) {

		$("#main").html(msg);

	});
}
function updateRole(roleID, name, permissions) {
	$("#main").html("<img src='data/load.svg'>");
	$.ajax({
		method : "POST",
		url : "roles.process.php",
		data : {
			request : "update",
			role : roleID,
			name : name,
			perm : permissions
		}
	}).done(function(msg) {

		listRoles();

	});
}
function createForm() {
	$("#main").html("<img src='data/load.svg'>");
	$.ajax({
		method : "POST",
		url : "roles.process.php",
		data : {
			request : "createForm"
		}
	}).done(function(msg) {

		$("#main").html(msg);

	});
}
function createRole(name, permissions) {
	$("#main").html("<img src='data/load.svg'>");
	$.ajax({
		method : "POST",
		url : "roles.process.php",
		data : {
			request : "createRole",
			name : name,
			perm : permissions
		}
	}).done(function(msg) {

		listRoles();

	});
}