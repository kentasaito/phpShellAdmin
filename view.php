<!DOCTYPE html>
<meta charset="utf-8">
<script>
const psa = {
	cwd: <?php echo json_encode($psa->cwd); ?>,
<?php if (isset($psa->filename)): ?>
	filename: <?php echo json_encode($psa->filename); ?>,
<?php endif; ?>
};
</script>
<script src="./default.js"></script>
<link rel="stylesheet" href="./default.css">

<table>
	<tr>
		<td colspan="2">
			<!-- 現在のディレクトリを表示する -->
			<div><span id="henko"></span><?php echo htmlspecialchars($psa->cwd); ?>/<?php if (isset($psa->filename)) echo htmlspecialchars($psa->filename); ?></div>
		</td>
	</tr>
	<tr>
		<td>
			<form enctype="multipart/form-data" method="post" action="./?cwd=<?php echo htmlspecialchars($psa->cwd); ?>">
				<table>
					<tr>
						<td class="w100">
							<input type="file" name="file" class="w100">
						</td>
						<td>
							<button>Upload</button>
						</td>
					</tr>
				</table>
			</form>
		</td>
		<td>
			<form method="post" action="./?cwd=<?php echo htmlspecialchars($psa->cwd); ?>">
				<table>
					<tr>
						<td class="w100">
							<input name="command" class="w100">
						</td>
						<td>
							<button>Execute</button>
						</td>
					</tr>
				</table>
			</form>
		</td>
	</tr>
	<tr class="h100">
		<td>
			<!-- 現在のディレクトリに存在するディレクトリ一覧をリンクとして表示する -->
			<!-- 	リンクのクリックでそのディレクトリへ移動する -->
			<ul>
			<?php foreach($file_manager->directories as $node): ?>
			<li><a href="./?cwd=<?php echo htmlspecialchars($psa->cwd); ?>/<?php echo $node; ?>"><?php echo $node; ?></a></li>
			<?php endforeach; ?>
			</ul>

			<!-- 現在のディレクトリに存在するファイル一覧をリンクとして表示する -->
			<!-- 	リンクのクリックでそのそのファイルの編集を開始する -->
			<ul>
			<?php foreach($file_manager->files as $node): ?>
			<li><a href="./?cwd=<?php echo htmlspecialchars($psa->cwd); ?>&filename=<?php echo $node; ?>"><?php echo $node; ?></a></li>
			<?php endforeach; ?>
			</ul>
		</td>
		<td id="main" class="w100">
<?php if (isset($psa->output)): ?>
			<pre class="h100 w100"><?php echo htmlspecialchars($psa->output); ?></pre>
<?php elseif (isset($psa->contents)): ?>
			<form>
				<table>
					<tr class="h100">
						<td>
							<textarea id="contents" class="h100 w100"><?php echo htmlspecialchars($psa->contents); ?></textarea>
						</td>
					</tr>
					<tr>
						<td>
							<button id="save" accesskey="s">Save(<u>S</u>)</button>
							<button type="button" id="go_to_line" accesskey="g">Go to line(<u>G</u>)</button>
						</td>
					</tr>
				</table>
			</form>
<?php endif; ?>
		</td>
	</tr>
</table>
