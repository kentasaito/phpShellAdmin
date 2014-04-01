<?php
$image_file = preg_replace('/\.dot$/', '.png', $_GET['file']);
shell_exec('cd '.$_GET['dir'].'; dot -Tpng '.$_GET['file'].' -o '.$image_file);
?>
<form method="post" class="h100 md">
<table class="h100 w100">
<tr><td class="h100" style="width:50%;"><textarea class="h100 w100" name="contents" onchange="changed = true;">
<?php echo htmlspecialchars($vars->contents); ?></textarea>
<td class="h100" style="width:50%;">
<img src="ext/png.php?image=<?php echo $_GET['dir']; ?>/<?php echo $image_file; ?>" />
<tr><td><input type="submit" value="Save(S)" accesskey="s" onclick="changed = false;"><input type="button" value="Line jump(G)" accesskey="g" onclick="line_jump();">
</table>
</form>
