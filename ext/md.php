<?php
require_once __DIR__.'/../vendor/autoload.php';
use \Michelf\MarkdownExtra;
$my_html = MarkdownExtra::defaultTransform($vars->contents);
?>

<style>
.md h1, .md h2, .md h3, .md h4, .md h5, .md h6 {
	margin-top: 0;
}
.md li {
	margin-left: 20px;
	list-style-type: square;
}
</style>


<form method="post" class="h100 md">
<table class="h100 w100">
<tr><td class="h100" style="width:50%;"><textarea class="h100 w100" name="contents" onchange="changed = true;">
<?php echo htmlspecialchars($vars->contents); ?></textarea>
<td class="h100" style="width:50%;"><?php echo $my_html; ?>
<tr><td><input type="submit" value="Save(S)" accesskey="s" onclick="changed = false;"><input type="button" value="Line jump(G)" accesskey="g" onclick="line_jump();">
</table>
</form>
