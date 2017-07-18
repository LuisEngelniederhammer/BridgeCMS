<?php
$upload_folder = "temp/";
$filename = pathinfo($_FILES['datei']['name'], PATHINFO_FILENAME);
$extension = strtolower(pathinfo($_FILES['datei']['name'], PATHINFO_EXTENSION));


//Überprüfung der Dateiendung
$allowed_extensions = array('zip', 'rar');
if(!in_array($extension, $allowed_extensions)) {
	die("Forbidden File extension, only zip and rar");
}

//Überprüfung der Dateigröße
$max_size = 500*1024; //500 KB
if($_FILES['datei']['size'] > $max_size) {
	die("File too big");
}

//Pfad zum Upload
$new_path = $upload_folder.$filename.'.'.$extension;

//Neuer Dateiname falls die Datei bereits existiert
if(file_exists($new_path)) { //Falls Datei existiert, hänge eine Zahl an den Dateinamen
	$id = 1;
	do {
		$new_path = $upload_folder.$filename.'_'.$id.'.'.$extension;
		$id++;
	} while(file_exists($new_path));
}

//Alles okay, verschiebe Datei an neuen Pfad
move_uploaded_file($_FILES['datei']['tmp_name'], $new_path);

$zip = new ZipArchive;
if ($zip->open($new_path) === TRUE) {
	$zip->extractTo(__DIR__. '../../style_templates/');
	$zip->close();
	echo 'ok';
} else {
	echo 'Fehler';
}

?>
<meta http-equiv="refresh" content="0; URL=styles.php">
