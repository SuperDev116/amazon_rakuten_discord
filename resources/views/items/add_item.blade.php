@extends("layouts.main")

@section('content')
<div class="buy-now">
	<a href={{ route('item_list', $category->id) }} class="btn btn-danger btn-buy-now" style="bottom: 6rem;">{{ $category->name }}:商品⼀覧へ</a>
</div>

<div class="content-wrapper">
	<div class="container-xxl flex-grow-1 container-p-y">
		<!-- <div class="pagetitle">
			<nav>
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="/">Rakuten</a></li>
					<li class="breadcrumb-item active">{{ $category->name }}</li>
				</ol>
			</nav>
		</div> -->

		<div class="row">
			<div class="col-xl-12">
				<div class="nav-align-top mb-4">
					<ul class="nav nav-pills mb-3 nav-fill" role="tablist">
						<li class="nav-item">
							<button
								type="button"
								class="nav-link active"
								role="tab"
								data-bs-toggle="tab"
								data-bs-target="#navs-pills-justified-amazon"
								aria-controls="navs-pills-justified-amazon"
								aria-selected="true"
							>
								<i class="tf-icons bx bx-cog" style="padding-bottom: 2px;"></i> Amazon
								<!-- <span class="badge rounded-pill badge-center h-px-20 w-px-20 bg-danger">3</span> -->
							</button>
						</li>
						<li class="nav-item">
							<button
								type="button"
								class="nav-link"
								role="tab"
								data-bs-toggle="tab"
								data-bs-target="#navs-pills-justified-rakuten"
								aria-controls="navs-pills-justified-rakuten"
								aria-selected="false"
							>
								<i class="tf-icons bx bx-cog" style="padding-bottom: 2px;"></i> Rakuten
							</button>
						</li>
						<li class="nav-item">
							<button
								type="button"
								class="nav-link"
								role="tab"
								data-bs-toggle="tab"
								data-bs-target="#navs-pills-justified-yahoo"
								aria-controls="navs-pills-justified-yahoo"
								aria-selected="false"
							>
								<i class="tf-icons bx bx-cog" style="padding-bottom: 2px;"></i> Yahoo
							</button>
						</li>
					</ul>
					<div class="tab-content" style="padding-bottom: 0.75rem;">
						<div class="tab-pane fade show active" id="navs-pills-justified-amazon" role="tabpanel">
							<div class="form-group row mb-3">
								<label for="access_key" class="col-md-2 col-form-label">アクセスキー<span style="color: red;">&nbsp;&nbsp;*</span></label>
								<div class="col-md-10">
									<input type="text" class="form-control" id="access_key" name="access_key" value="{{ $category->access_key }}" onchange="setColumn(event);" />
								</div>
							</div>

							<div class="form-group row mb-3">
								<label for="secret_key" class="col-md-2 col-form-label">シークレット キー<span style="color: red;">&nbsp;&nbsp;*</span></label>
								<div class="col-md-10">
									<input type="text" class="form-control" id="secret_key" name="secret_key" value="{{ $category->secret_key }}" onchange="setColumn(event);" />
								</div>
							</div>

							<div class="form-group row mb-3">
								<label for="partner_tag" class="col-md-2 col-form-label">パートナータグ<span style="color: red;">&nbsp;&nbsp;*</span></label>
								<div class="col-md-10">
									<input type="text" class="form-control" id="partner_tag" name="partner_tag" value="{{ $category->partner_tag }}" onchange="setColumn(event);" />
								</div>
							</div>

							<div class="form-group row mb-3">
								<label for="fall_pro" class="col-md-2 col-form-label">下落(%)</label>
								<div class="col-md-10">
									<input class="form-control" min='0' max='100' type="number" value="{{ $category->am_fall_pro }}" id="am_fall_pro" name="am_fall_pro" onchange="setColumn(event);" />
								</div>
							</div>

							<div class="form-group row mb-3">
								<label for="fall_pro" class="col-md-2 col-form-label">目標価格</label>
								<div class="col-md-10">
									<input class="form-control" min='0' type="number" value="{{ $category->am_target_price }}" id="am_target_price" name="am_target_price" onchange="setColumn(event);" />
								</div>
							</div>

							<div class="form-group row mb-3">
								<label for="web_hook" class="col-md-2 col-form-label">Web Hook</label>
								<div class="col-md-10">
									<input class="form-control" type="text" id="am_web_hook" name="am_web_hook" value="{{ $category->am_web_hook }}" onchange="setColumn(event);" />
								</div>
							</div>
						</div>
						<div class="tab-pane fade" id="navs-pills-justified-rakuten" role="tabpanel">
							<div class="form-group row mb-3">
								<label for="affiliate_id" class="col-md-2 col-form-label">アフィリエイトID</label>
								<div class="col-md-10">
									<input type="text" class="form-control" id="affiliate_id" name="affiliate_id" value="{{ $category->affiliate_id }}" onchange="setColumn(event);" />
								</div>
							</div>
						
							<div class="form-group row mb-3">
								<label for="application_id" class="col-md-2 col-form-label">アプリケーションID</label>
								<div class="col-md-10">
									<input type="text" class="form-control" id="application_id" name="application_id" value="{{ $category->application_id }}" onchange="setColumn(event);" />
								</div>
							</div>

							<div class="form-group row mb-3">
								<label for="fall_pro" class="col-md-2 col-form-label">下落(%)</label>
								<div class="col-md-10">
									<input class="form-control" min='0' max='100' type="number" value="{{ $category->ra_fall_pro }}" id="ra_fall_pro" name="ra_fall_pro" onchange="setColumn(event);" />
								</div>
							</div>

							<div class="form-group row mb-3">
								<label for="fall_pro" class="col-md-2 col-form-label">目標価格</label>
								<div class="col-md-10">
									<input class="form-control" min='0' type="number" value="{{ $category->ra_target_price }}" id="ra_target_price" name="ra_target_price" onchange="setColumn(event);" />
								</div>
							</div>

							<div class="form-group row mb-3">
								<label for="web_hook" class="col-md-2 col-form-label">Web Hook</label>
								<div class="col-md-10">
									<input class="form-control" type="text" id="ra_web_hook" name="ra_web_hook" value="{{ $category->ra_web_hook }}" onchange="setColumn(event);" />
								</div>
							</div>

							<div class="form-group row mb-3">
								<label class="col-md-2 col-form-label"> </label>
								<div class="col-md-10">
									<input class="form-control" type="text" style="opacity: 0;" />
								</div>
							</div>
						</div>
						<div class="tab-pane fade" id="navs-pills-justified-yahoo" role="tabpanel">
							<div class="form-group row mb-3">
								<label for="yahoo_id" class="col-md-2 col-form-label">Yahoo ID</label>
								<div class="col-md-10">
									<input type="text" class="form-control" id="yahoo_id" name="yahoo_id" value="{{ $category->yahoo_id }}" onchange="setColumn(event);" />
								</div>
							</div>
							<div class="form-group row mb-3">
								<label for="fall_pro" class="col-md-2 col-form-label">下落(%)</label>
								<div class="col-md-10">
									<input class="form-control" min='0' max='100' type="number" value="{{ $category->ya_fall_pro }}" id="ya_fall_pro" name="ya_fall_pro" onchange="setColumn(event);" />
								</div>
							</div>

							<div class="form-group row mb-3">
								<label for="fall_pro" class="col-md-2 col-form-label">目標価格</label>
								<div class="col-md-10">
									<input class="form-control" min='0' type="number" value="{{ $category->ya_target_price }}" id="ya_target_price" name="ya_target_price" onchange="setColumn(event);" />
								</div>
							</div>

							<div class="form-group row mb-3">
								<label for="web_hook" class="col-md-2 col-form-label">Web Hook</label>
								<div class="col-md-10">
									<input class="form-control" type="text" id="ya_web_hook" name="ya_web_hook" value="{{ $category->ya_web_hook }}" onchange="setColumn(event);" />
								</div>
							</div>

							<div class="form-group row mb-3">
								<label class="col-md-2 col-form-label"> </label>
								<div class="col-md-10">
									<input class="form-control" type="text" style="opacity: 0;" />
								</div>
							</div>

							<div class="form-group row mb-3">
								<label class="col-md-2 col-form-label"> </label>
								<div class="col-md-10">
									<input class="form-control" type="text" style="opacity: 0;" />
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-xl-12">
				<div class="card mb-4">
					<div class="card-body">
						<div class="form-group row mb-3">
							<label for="access_key" class="col-md-2 col-form-label">カテゴリー</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="name" name="name" value="{{ $category->name }}" onchange="setColumn(event);" />
							</div>
						</div>

						<div class="form-group row mb-3">
							<label for="csv_load" class="col-md-2 col-form-label">CSV選択</label>
							<div class="col-md-10">
								<input type="file" class="form-control" id="csv_load" name="csv_load">
							</div>
						</div>

						<div class="col-lg-12 mt-4" id="register-status" style="display: block;">
							<div class="row">
								<div class="col text-center">
									<span id="progress-num">0</span> 件/ <span id="total-num">0</span> 件
								</div>
								<div class="col text-center">
									<span id="round">0</span>回目
								</div>
							</div>
							<div class="row mt-4">
								<div class="progress col-12 p-0" id="count">
									<div class="progress-bar progress-bar-animated bg-primary progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;" id="progress">
										<span id="percent-num">0%</span>
									</div>
								</div>
							</div>
						</div>

						<div class="col-lg-12 mt-4" id="track-status" style="display: none;">
							<div class="row">
								<div class="col text-center">
									<span id="progress-num1">0</span> 件/ <span id="total-num1">0</span> 件
								</div>
								<div class="col text-center">
									<span id="round1">0</span>回目
								</div>
							</div>
							<div class="row mt-4">
								<div class="progress col-12 p-0" id="count1">
									<div class="progress-bar progress-bar-animated bg-info progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;" id="progress1">
										<span id="percent-num1">0%</span>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="card-footer text-center">
						<button type="button" id="register" class="btn btn-raised btn-primary waves-effect" onclick="register1();">登 録</button>
						<button type="button" id="stop" class="btn btn-raised btn-danger waves-effect" onclick="stop1();">停 止</button>
						<button type="button" id="restart" class="btn btn-raised btn-warning waves-effect" onclick="restart1();">起 動</button>
					</div>
				</div>
			</div>
		</div>

		<!-- <div class="card p-4">
			<form class="form-horizontal">
				<div class="card-body" style="padding:0px">
				
					<div class="form-group row mb-3">
						<label for="access_key" class="col-md-2 col-form-label">カテゴリー</label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="name" name="name" value="{{ $category->name }}" onchange="setColumn(event);" />
						</div>
					</div>

					<div class="form-group row mb-3">
						<label for="access_key" class="col-md-2 col-form-label">アクセスキー</label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="access_key" name="access_key" value="{{ $category->access_key }}" onchange="setColumn(event);" />
						</div>
					</div>

					<div class="form-group row mb-3">
						<label for="secret_key" class="col-md-2 col-form-label">シークレット キー</label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="secret_key" name="secret_key" value="{{ $category->secret_key }}" onchange="setColumn(event);" />
						</div>
					</div>

					<div class="form-group row mb-3">
						<label for="partner_tag" class="col-md-2 col-form-label">パートナータグ</label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="partner_tag" name="partner_tag" value="{{ $category->partner_tag }}" onchange="setColumn(event);" />
						</div>
					</div>
				
					<div class="form-group row mb-3">
						<label for="affiliate_id" class="col-md-2 col-form-label">Affiliate ID<br/>(Rakuten)</label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="affiliate_id" name="affiliate_id" value="{{ $category->affiliate_id }}" onchange="setColumn(event);" />
						</div>
					</div>
				
					<div class="form-group row mb-3">
						<label for="application_id" class="col-md-2 col-form-label">Application ID<br/>(Rakuten)</label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="application_id" name="application_id" value="{{ $category->application_id }}" onchange="setColumn(event);" />
						</div>
					</div>
					
					<div class="form-group row mb-3">
						<label for="fall_pro" class="col-md-2 col-form-label">下落(%)</label>
						<div class="col-md-10">
							<input class="form-control" min='0' max='100' type="number" value="{{ $category->fall_pro }}" id="fall_pro" name="fall_pro" onchange="setColumn(event);" />
						</div>
					</div>

					<div class="form-group row mb-3">
						<label for="fall_pro" class="col-md-2 col-form-label">目標価格</label>
						<div class="col-md-10">
							<input class="form-control" min='0' type="number" value="{{ $category->target_price }}" id="target_price" name="target_price" onchange="setColumn(event);" />
						</div>
					</div>
					
					<div class="form-group row mb-3">
						<label for="web_hook" class="col-md-2 col-form-label">Web Hook</label>
						<div class="col-md-10">
							<input class="form-control" type="text" id="web_hook" name="web_hook" value="{{ $category->web_hook }}" onchange="setColumn(event);" />
						</div>
					</div>

					<div class="form-group row mb-3">
						<label for="csv_load" class="col-md-2 col-form-label">CSV選択</label>
						<div class="col-md-10">
							<input type="file" class="form-control" id="csv_load" name="csv_load">
						</div>
					</div>

					<div class="col-lg-12 mt-4" id="register-status" style="display: block;">
						<div class="row">
							<div class="col text-center">
								<span id="progress-num">0</span> 件/ <span id="total-num">0</span> 件
							</div>
							<div class="col text-center">
								<span id="round">0</span>回目
							</div>
						</div>
						<div class="row mt-4">
							<div class="progress col-12 p-0" id="count">
								<div class="progress-bar progress-bar-animated bg-primary progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;" id="progress">
									<span id="percent-num">0%</span>
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-12 mt-4" id="track-status" style="display: none;">
						<div class="row">
							<div class="col text-center">
								<span id="progress-num1">0</span> 件/ <span id="total-num1">0</span> 件
							</div>
							<div class="col text-center">
								<span id="round1">0</span>回目
							</div>
						</div>
						<div class="row mt-4">
							<div class="progress col-12 p-0" id="count1">
								<div class="progress-bar progress-bar-animated bg-info progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;" id="progress1">
									<span id="percent-num1">0%</span>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="card-footer text-center">
					<button type="button" id="register" class="btn btn-raised btn-primary waves-effect" onclick="register1();">登 録</button>
					<button type="button" id="stop" class="btn btn-raised btn-danger waves-effect" onclick="stop1();">停 止</button>
					<button type="button" id="restart" class="btn btn-raised btn-warning waves-effect" onclick="restart1();">起 動</button>
				</div>
			</form>
		</div> -->
	</div>
</div>
@endsection

@section('script')
<script>

	var scanInterval = setInterval(scan, 5000);

	function scan() {
		$.ajax({
			url: "{{ route('scan', $category->id) }}",
			type: "get",
			success: function(response) {
				if (response.is_reg == 1) {
					$('#register-status').css('display', 'block');
					$('#track-status').css('display', 'none');

					$('#total-num').html(response.len);
					$('#progress-num').html(response.reg_num);
					var percent = Math.floor(response.reg_num / response.len * 100);
					$('#percent-num').html(percent + '%');
					$('#progress').attr('aria-valuenow', percent);
					$('#progress').css('width', percent + '%');
					$('#round').html(0);
				} else if (response.is_reg == 0) {
					$('#register-status').css('display', 'none');
					$('#track-status').css('display', 'block');

					$('#total-num1').html(response.len);
					$('#progress-num1').html(response.trk_num);
					var percent = Math.floor(response.trk_num / response.len * 100);
					$('#percent-num1').html(percent + '%');
					$('#progress1').attr('aria-valuenow', percent);
					$('#progress1').css('width', percent + '%');
					$('#round1').html(response.round);
				}

				if (percent == 100) {
					if (response.round == 1) {
						toastr.success('正常に登録されました。');
						location.href = "{{ route('item_list', $category->id) }}";
					}
				}
			}
		});
	}

	const setColumn = (e) => {
		$.ajax({
			url: "{{ route('set_column') }}",
			type: "post",
			data:{
				cId: `{{ $category->id }}`,
				col: e.target.name,
				content: e.target.value,
			},
			success: function () {
				toastr.success(`正常に更新されました。`);
			}
		});
	};

	const stop1 = () => {
		clearInterval(scanInterval);
		$.ajax({
			url: "{{ route('stop', $category->id) }}",
			type: "get",
			success: function () {
				toastr.info('サーバーが停止されました。');

				$('#round1').html(0);
				$('#round1').html(0);
				$('#progress-num1').html(0);
				$('#percent-num1').html('0%');
				$('#progress1').attr('aria-valuenow', );
				$('#progress1').css('width', '0%');
			}
		});
	};

	const restart1 = () => {
		scanInterval = setInterval(scan, 5000);
		$.ajax({
			url: "{{ route('restart', $category->id) }}",
			type: "get",
			success: function () {
				toastr.info('サーバーが起動されました。');
			}
		});
	};

	const register1 = async () => {
		var user = <?php echo $user; ?>;
		
		clearInterval(scanInterval);
		await $.ajax({
			url: "{{ route('stop', $category->id) }}",
			type: "get",
			success: function () {
				$('#round1').html(0);
				$('#round1').html(0);
				$('#progress-num1').html(0);
				$('#percent-num1').html('0%');
				$('#progress1').attr('aria-valuenow', );
				$('#progress1').css('width', '0%');
			}
		});

		if (csvFile === undefined) {
			toastr.error('CSVファイルを選択してください。');
			return;
		}

		let postData = {
			category_id : '{{ $category->id }}',
			file_name: csvFile.name,
			len: newCsvResult.length,
		};

		// first save user exhibition setting
		await $.ajax({
			url:  "{{ route('save_category', $category->id) }}",
			type: 'post',
			data: {
				exData: JSON.stringify(postData)
			},
			success: function () {
				scanInterval = setInterval(scan, 5000);
				toastr.info('商品登録を開始します。');

				$('#register-status').css('display', 'block');
				$('#track-status').css('display', 'none');
		
				$('#csv_load').attr('disabled', true);
				$('#register').attr('disabled', true);
			}
		});

		// then start registering products with ASIN code
		postData = {
			category_id: '{{ $category->id }}',
			codes: newCsvResult
		};

		$.ajax({
			// url: "http://localhost:32768/api/v1/amazon/get_info",
			url: "https://xs766690.xsrv.jp/fmproxy/api/v1/rakuten/get_info",
			type: "post",
			data: {
				asin: JSON.stringify(postData)
			},
		});
	};

	var newCsvResult, csvFile;
	// select csv file and convert its content into an array of ASIN code
	$('#csv_load').on('change', function(e) {
		result = e.target.id;
		clearInterval(scanInterval);

		csvFile = e.target.files[0];
		newCsvResult = [];

		$('#progress-num').html('0');
		$('#percent-num').html('0%');
		$('#progress').attr('aria-valuenow', 0);
		$('#progress').css('width', '0%');

		var ext = $('#csv_load').val().split(".").pop().toLowerCase();
		if ($.inArray(ext, ["csv", "xlsx"]) === -1) {
			toastr.error('CSV、XLSXファイルを選択してください。');
			return false;
		}

		if (csvFile !== undefined) {
			reader = new FileReader();
			reader.onload = function (e) {
				$('#count').css('visibility', 'visible');
				csvResult = e.target.result.split(/\n/);

				for (const i of csvResult) {
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

				if (newCsvResult[0] == 'ASIN') { newCsvResult.shift(); }

				// $('#csv-name').html(csvFile.name);
				$('#total-num').html(newCsvResult.length);
				console.log(newCsvResult.length);
			}
			reader.readAsText(csvFile);
		}
	});

</script>
@endsection