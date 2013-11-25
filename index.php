<!DOCTYPE html>
<meta charset="utf-8">
<style>
body { margin: 0; }
html, body, table { height: 100%; }
td { padding: 0; vertical-align: top; }
ul { margin-top: 0; padding-left: 0; }
.h100 { height: 100%; }
.w100 { width: 100%; }
.bordered { border:1px solid #ccc; }
</style>
<title>phpShellAdmin</title>
<table>
<tr>
<td class="bordered" colspan="2">
/path/to/cwd/

<tr>
<td class="bordered">
<form method="post" enctype="multipart/form-data">
<table><tr>
<td><input type="file">
<td><input type="submit" value="Upload">
</table>
</form>

<td class="bordered">
<form method="post">
<table class="w100"><tr>
<td class="w100"><input type="text" class="w100">
<td><input type="submit" value="Execute">
</table>
</form>

<tr>
<td class="bordered">
<ul>
<li><a href="">dir1</a>
<li><a href="">dir2</a>
</ul>
<ul>
<li><a href="">file1</a>
<li><a href="">file2</a>
</ul>

<td class="bordered h100 w100">
<form method="post" class="h100">
<table class="h100 w100">
<tr><td class="h100"><textarea class="h100 w100"></textarea>
<tr><td><input type="submit" value="Save">
</table>
</form>

</table>
