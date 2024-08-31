var newCsvResult = [];
let scanInterval;

$('body').on('change', '#csv_load', function(e) {
	newCsvResult = [];
	var csv = $('#csv_load');
	var csvFile = e.target.files[0];

	$('#progress-num').html('0');
	$('#percent-num').html('0%');
	$('#progress').attr('aria-valuenow', 0);
	$('#progress').css('width', '0%');

	var ext = csv.val().split(".").pop().toLowerCase();
	if ($.inArray(ext, ["csv"]) === -1) {
		toastr.error('CSVファイルを選択してください。');
		return false;
	}
	
	if (csvFile !== undefined) {

		reader = new FileReader();
		reader.onload = function (e) {
			$('#csv-name').html(csvFile.name)
			$('#count').css('visibility', 'visible');

			csvResult = e.target.result.split(/\n/);

			for (let i of csvResult) {
				let code = i.split('\r');
				code = i.split('"');

				if (code.length == 1) {
					code = i.split('\r');
					if (code[0] != '') {
						newCsvResult.push(code[0]);
					}
				} else {
					newCsvResult.push(code[1]);
				}
			}
			
			if (newCsvResult[0] == 'ASIN') {
				newCsvResult.shift();
			}

			if (newCsvResult.length > 5000) {
				toastr.error('ファイルに5000以上のコードがあります。別のファイルを選択するか、個数を減らしてください。');
				return;
			}

			$('#total-num').html(newCsvResult.length);
		}
		reader.readAsText(csvFile);
	}
});

const scanDB = () => {
	$.ajax({
		url: "./scan",
		type: "get",
		success: function(response) {
			$('#progress-num').html(response);
			let percent = Math.floor(response / localStorage.getItem('len') * 100);
			$('#percent-num').html(percent + '%');
			$('#progress').attr('aria-valuenow', percent);
			$('#progress').css('width', percent + '%');
			if (percent == 100) {
				clearInterval(scanInterval);
				toastr.success('ファイルが正常にアップロードされました。');

				localStorage.setItem('isRegistering', 0);
				localStorage.setItem('len', 0);
			}
		}
	})
};

const addCsv = () => {
	if (!isPermitted) {
		toastr.error('管理者から許可を得てください。');
		return;
	}

	if (!newCsvResult.length) {return;}
	toastr.info('トラッキングを開始します。');
	jQuery.ajax({
		// url: "http://keepaautobuy.xsrv.jp/fmproxy/api/v1/rakutens/get_info",
		url: "http://localhost:32768/api/v1/rakutens/get_info",
		type: "post",
		data: {
			index: index,
			code_kind: $('input[name="code_kind"]:checked').val(),
			code: JSON.stringify(newCsvResult),
		},
	});
	scanInterval = setInterval(scanDB, 5000);

	localStorage.setItem('isRegistering', 1); 	
	localStorage.setItem('len', newCsvResult.length);
	localStorage.setItem('name', $('#csv-name').html());
};

if (localStorage.getItem('isRegistering') == 1) {
	setInterval(scanDB, 5000);

	$('#csv-name').html(localStorage.getItem('name'))
	$('#total-num').html(localStorage.getItem('len'));
}