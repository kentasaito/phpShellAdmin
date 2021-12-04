let henko_mae;

document.addEventListener('DOMContentLoaded', () => {
	document.querySelectorAll('form').forEach(form => form.reset());

	if (psa.filename !== undefined) {
		document.getElementById('contents').setSelectionRange(0, 0);

		// ファイルに変更があるときは示す
		henko_mae = document.getElementById('contents').value;
		document.getElementById('contents').addEventListener('keyup', () => {
			document.getElementById('henko').innerText = document.getElementById('contents').value !== henko_mae ? '* ' : '';
		});	

		// 保存ボタンのクリックで編集中のファイルを保存する
		document.getElementById('save').addEventListener('click', e => {
			e.preventDefault();
			const xhr = new XMLHttpRequest();
			xhr.open('POST', './?cwd=' + psa.cwd + '&filename=' + psa.filename);
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			xhr.onreadystatechange = () => {
				if(xhr.readyState === 4) {
					henko_mae = document.getElementById('contents').value;
					document.getElementById('henko').innerText = '';
					document.getElementById('contents').focus();
				}
			};
			xhr.send('contents=' + encodeURIComponent(document.getElementById('contents').value));
		});

		document.getElementById('contents').addEventListener('keydown', e => {
			if (e.code === 'Tab') {
				e.preventDefault();

				const start = e.target.selectionStart;
				const end = e.target.selectionEnd;

				if (end === start) {
					// Tabキーの押下でTabの挿入を行う
					e.target.value = e.target.value.substring(0, start) + '\t' + e.target.value.substring(start);
					e.target.setSelectionRange(start + 1, start + 1);
				} else {
						// 文字列選択時はTabキーの押下でインデントを行う
						// 文字列選択時はShift + Tabキーの押下でインデントの解除を行う
						const sentaku = e.target.value.substring(start, end).replace(e.shiftKey ? /^\t/gm : /^/gm, e.shiftKey ? '' : '\t').replace(/^\s+$/gm, '');
					e.target.value = e.target.value.substring(0, start) + sentaku + e.target.value.substring(end);
					e.target.setSelectionRange(start, start + sentaku.length);
				}
			}
		});

		document.getElementById('contents').focus();
	}
	else {
		document.querySelector('[name="command"]').focus();
	}
});
