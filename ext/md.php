<?php
require_once __DIR__.'/../vendor/autoload.php';
use \Michelf\MarkdownExtra;
$my_html = MarkdownExtra::defaultTransform($vars->contents);
$my_html = str_replace('<table', '<TABLE border="1"', $my_html);

$file = $_GET['file'];
file_put_contents(preg_replace('/\.md$/', '.html', $file), $my_html);
?>

<style>
.md h1, .md h2, .md h3, .md h4, .md h5, .md h6 {
	margin-bottom: 0;
}
.md li {
	margin-left: 20px;
	list-style-type: square;
}
.md table {
	width: 100%;
	height: 0;
//	border-collapse: collapse;
}
.md th, .md td {
//	border: 1px solid #000;
	text-align: center;
}
</style>


<form method="post" class="h100">
<table class="h100 w100">
<tr><td class="h100" style="width:50%;"><textarea class="h100 w100" name="contents" onchange="changed = true;">
<?php echo htmlspecialchars($vars->contents); ?></textarea>
<td class="h100 md" style="width:50%;"><?php echo $my_html; ?>
<tr><td><input type="submit" value="Save(S)" accesskey="s" onclick="changed = false;"><input type="button" value="Line jump(G)" accesskey="g" onclick="line_jump();">
</table>
</form>
