@extends("layouts.main")

@section('css')
<style>
	table th, table td {
		text-align: center !important;
		vertical-align: middle !important;
	}
</style>
@endsection

@section('content')

<div class="content-wrapper">
	<div class="container-xxl flex-grow-1 container-p-y">
		<div class="pagetitle">
			<nav>
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="/">{{ env('APP_NAME') }}</a></li>
					<li class="breadcrumb-item active">カテゴリー</li>
				</ol>
			</nav>
		</div><!-- End Page Title -->
		<div class="content" style="padding-top: 0.5rem;">
			<div class="col-12">
				<div class="card card-info card-outline">
					<div class="card-body">
						<div class="row">
							<div id="table-wrapper" class="col-12">
								<table class="table table-bordered" style="width: 100%;" id="item-table">
									<thead>
										<tr>
											<th rowspan="2" style="min-width: 90px;">
												<span class="text-primary"
													data-bs-toggle="modal" 
													data-bs-target="#categoryModal">
													<i class='bx bxs-plus-circle'></i>
												</span>
											</th>
											<th rowspan="2">カテゴリー名</th>
											<th colspan="3">下落(%)</th>
											<th colspan="3">目標価格</th>
										</tr>
										<tr>
											<th>Amazon</th>
											<th>Yahoo</th>
											<th>Rakuten</th>
											<th>Amazon</th>
											<th>Yahoo</th>
											<th>Rakuten</th>
										</tr>
									</thead>

									<tbody id="item-table-body">
										@foreach($categories as $c)
										<tr id={{ "category". $c->id }}>
											<td style="min-width: 90px;">
												<span
													data-category={{ $c }}
													data-bs-toggle="modal"
													data-bs-target="#categoryModal">
													<i class='bx bxs-edit text-primary'></i>
												</span>
												<span
													data-id={{ $c->id }}
													data-bs-toggle="modal"
													data-bs-target="#confirmModal1">
													<i class='bx bxs-trash text-danger'></i>
												</span>
											</td>
											<td>{{$c['name']}}</td>
											<td>{{$c['am_fall_pro']}}</td>
											<td>{{$c['am_target_price']}}</td>
											<td>{{$c['ya_fall_pro']}}</td>
											<td>{{$c['ya_target_price']}}</td>
											<td>{{$c['ra_fall_pro']}}</td>
											<td>{{$c['ra_target_price']}}</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!-- /.card-body -->
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="categoryModal">
	<div class="modal-dialog">
		<div class="modal-content">

		<!-- Modal Header -->
		<div class="modal-header bg-primary">
			<h4 class="modal-title text-white">カテゴリー編集</h4>
			<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
		</div>

		<!-- Modal body -->
		<div class="modal-body m-4">
			<div class="row mt-2">
				<div class="col-4">
					<strong>カテゴリー名</strong>
				</div>
				<div class="col-8">
					<input class="form-control" type="text" id="c_name" name="c_name" value="" required />
					<input class="form-control" type="hidden" id="category_id" name="category_id" value="" />
				</div>
			</div>

			<div class="row mt-2">
				<div class="col-4">
					<strong>アクセスキー</strong>
				</div>
				<div class="col-8">
					<input class="form-control" type="text" id="access_key" name="access_key" value="" required />
				</div>
			</div>
			
			<div class="row mt-2">
				<div class="col-4">
					<strong>シークレット キー</strong>
				</div>
				<div class="col-8">
					<input class="form-control" type="text" id="secret_key" name="secret_key" value="" required />
				</div>
			</div>

			<div class="row mt-2">
				<div class="col-4">
					<strong>パートナータグ</strong>
				</div>
				<div class="col-8">
					<input class="form-control" type="text" id="partner_tag" name="partner_tag" value="" required />
				</div>
			</div>
			
			<div class="row mt-2">
				<div class="col-4">
					<strong>Affiliate ID (Rakuten)</strong>
				</div>
				<div class="col-8">
					<input class="form-control" type="text" id="affiliate_id" name="affiliate_id" value="" required />
				</div>
			</div>
			
			<div class="row mt-2">
				<div class="col-4">
					<strong>Application ID (Rakuten)</strong>
				</div>
				<div class="col-8">
					<input class="form-control" type="text" id="application_id" name="application_id" value="" required />
				</div>
			</div>

			<div class="row mt-2">
				<div class="col-4">
					<strong>Application ID (Yahoo)</strong>
				</div>
				<div class="col-8">
					<input class="form-control" type="text" id="yahoo_id" name="yahoo_id" value="" required />
				</div>
			</div>
			
			<!-- <div class="row mt-2">
				<div class="col-4">
					<strong>下落(%)</strong>
				</div>
				<div class="col-8">
					<input class="form-control" type="num" id="fall_pro" name="fall_pro" min="0" max="100" value="" required />
				</div>
			</div>
			
			<div class="row mt-2">
				<div class="col-4">
					<strong>目標価格</strong>
				</div>
				<div class="col-8">
					<input class="form-control" type="num" id="target_price" name="target_price" min="0" max="100" value="" required />
				</div>
			</div>
			
			<div class="row mt-2">
				<div class="col-4">
					<strong>WEB HOOK</strong>
				</div>
				<div class="col-8">
					<input class="form-control" type="text" id="web_hook" name="web_hook" value="" required />
				</div>
			</div>			 -->
		</div>

		<!-- Modal footer -->
		<div class="modal-footer" id="button-container">
			
		</div>
	</div>
	</div></div>

<div class="modal fade" id="confirmModal1" tabindex="-1" aria-modal="true" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-12 mb-3 text-center">
						<h4>本当にデータを削除しますか?</h4>
					</div>
				</div>
			</div>
			<div class="modal-footer" id="btns">
			</div>
		</div>
	</div>
</div>

@endsection
	
@section("script")
	<script>

		$('#confirmModal1').on('shown.bs.modal', function(e) {
			var target = e.relatedTarget.dataset;
			$('#btns').html(
				`<button type="button" class="btn btn-primary" onclick="category.delete(${target.id})">削除</button>
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">キャンセル</button>`
			);
		}).on('hidden.bs.modal', function(e) {

		});

		$('#categoryModal').on('shown.bs.modal', function(e) {
			if (e.relatedTarget.dataset.category !== undefined) {
				let categoryData = JSON.parse(e.relatedTarget.dataset.category);
				$('#category_id').val(categoryData.id);
				$('#c_name').val(categoryData.name);
				$('#affiliate_id').val(categoryData.affiliate_id);
				$('#application_id').val(categoryData.application_id);
				$('#access_key').val(categoryData.access_key);
				$('#secret_key').val(categoryData.secret_key);
				$('#partner_tag').val(categoryData.partner_tag);
				$('#yahoo_id').val(categoryData.yahoo_id);
				// $('#fall_pro').val(categoryData.fall_pro);
				// $('#target_price').val(categoryData.target_price);
				// $('#web_hook').val(categoryData.web_hook);

				$('#button-container').html('<button type="button" class="btn btn-primary" onclick="category.edit()">更新</button>');
			} else {
				$('#category_id').val('');
				$('#c_name').val('');
				$('#affiliate_id').val('');
				$('#application_id').val('');
				$('#access_key').val('');
				$('#secret_key').val('');
				$('#partner_tag').val('');
				$('#yahoo_id').val('');
				// $('#fall_pro').val(30);
				// $('#target_price').val(3000);
				// $('#web_hook').val('');

				$('#button-container').html('<button type="button" class="btn btn-primary" onclick="category.add()">追加</button>');
			}
		}).on('hidden.bs.modal', function(e) {

		});

		const category = {
			add: function () {
				var categoryData = {
					name: $('#c_name').val(),
					affiliate_id: $('#affiliate_id').val(),
					application_id: $('#application_id').val(),
					access_key: $('#access_key').val(),
					secret_key: $('#secret_key').val(),
					partner_tag: $('#partner_tag').val(),
					yahoo_id: $('#yahoo_id').val(),
					// fall_pro: $('#fall_pro').val(),
					// target_price: $('#target_price').val(),
					// web_hook: $('#web_hook').val(),
				};

				$.ajax({
					url: "{{ route('add_category') }}",
					type: "post",
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
					},
					data: {
						postData: JSON.stringify(categoryData)
					},
					beforeSend: function (xhr, opts) {
						if ($('#c_name').val() == '') {
							toastr.error('カテゴリー名は必須です。');
							xhr.abort();
							return false;
						}
					},
					success: function (res) {
						$('#categoryModal').modal('hide');

						toastr.success('カテゴリーが正常に追加されました。');
						location.reload();

					}
				});
			},
			edit: function () {
				var categoryData = {
					id: $('#category_id').val(),
					name: $('#c_name').val(),
					affiliate_id: $('#affiliate_id').val(),
					application_id: $('#application_id').val(),
					access_key: $('#access_key').val(),
					secret_key: $('#secret_key').val(),
					partner_tag: $('#partner_tag').val(),
					yahoo_id: $('#yahoo_id').val(),
					// fall_pro: $('#fall_pro').val(),
					// target_price: $('#target_price').val(),
					// web_hook: $('#web_hook').val(),
				};

				$.ajax({
					url: "{{ route('edit_category') }}",
					type: "post",
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
					},
					data: {
						postData: JSON.stringify(categoryData)
					},
					beforeSend: function (xhr, opts) {
						if ($('#c_name').val() == '') {
							toastr.error('カテゴリー名は必須です。');
							xhr.abort();
							return false;
						}
					},
					success: function (res) {
						$('#categoryModal').modal('hide');

						toastr.success('カテゴリーが正常に更新されました。');
						location.reload();

					}
				});
			},
			delete: function (id) {
				$.ajax({
					url: "/category/delete/" + id,
					type: "get",
					success: function (res) {
						$('#confirmModal1').modal('hide');
						toastr.success('カテゴリーが正常に削除されました。');
						location.reload();
						$('#category' + res).remove();
					}
				});
			},
		};

	</script>
@endsection
