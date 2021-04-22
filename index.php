<?php

/******************************************
PRAKTIKUM RPL
******************************************/

include("conf.php");
include("includes/Template.class.php");
include("includes/DB.class.php");
include("includes/Task.class.php");

// Membuat objek dari kelas task
$otask = new Task($db_host, $db_user, $db_password, $db_name);
$otask->open();

// Memanggil method getTask di kelas Task
$otask->getTask();

// Proses mengisi tabel dengan data
$data = null;
$no = 1;

if(isset($_POST['add'])){
	$otask->insertTask($_POST);

	header("Location:index.php");
}

while (list($id, $tteamname, $tleader, $taddres, $tnotelp, $ttype, $tstatus) = $otask->getResult()) {
	// Tampilan jika status task nya sudah dikerjakan
	if($tstatus == "Sudah Terdaftar"){
		$data .= "<tr>
		<td>" . $no . "</td>
		<td>" . $tteamname . "</td>
		<td>" . $tleader . "</td>
		<td>" . $taddres . "</td>
		<td>" . $tnotelp . "</td>
		<td>" . $ttype . "</td>
		<td>" . $tstatus . "</td>
		<td>
		<button class='btn btn-danger'><a href='index.php?id_hapus=" . $id . "' style='color: white; font-weight: bold;'>Hapus</a></button>
		</td>
		</tr>";
		$no++;
	}

	// Tampilan jika status task nya belum dikerjakan
	else{
		$data .= "<tr>
		<td>" . $no . "</td>
		<td>" . $tteamname . "</td>
		<td>" . $tleader . "</td>
		<td>" . $taddres . "</td>
		<td>" . $tnotelp . "</td>
		<td>" . $ttype . "</td>
		<td>" . $tstatus . "</td>
		<td>
		<button class='btn btn-danger'><a href='index.php?id_hapus=" . $id . "' style='color: white; font-weight: bold;'>Hapus</a></button>
		<button class='btn btn-success' ><a href='index.php?id_status=" . $id .  "' style='color: white; font-weight: bold;'>Selesai</a></button>
		</td>
		</tr>";
		$no++;
	}
}

if(isset($_GET['id_hapus'])){
	$id_task = $_GET['id_hapus'];

	$otask->deleteTask($id_task);

	unset($_GET['id_hapus']);

	header("Location: index.php");
}

if(isset($_GET['id_status'])){
	$id_status = $_GET['id_status'];

	$otask->updateTask($id_status);

	unset($_GET['id_status']);
	
	header("Location: index.php");
}

// Menutup koneksi database
$otask->close();

// Membaca template skin.html
$tpl = new Template("templates/skin.html");

// Mengganti kode Data_Tabel dengan data yang sudah diproses
$tpl->replace("DATA_TABEL", $data);

// Menampilkan ke layar
$tpl->write();