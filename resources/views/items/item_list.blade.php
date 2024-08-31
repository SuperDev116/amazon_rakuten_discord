@extends("layouts.main")

@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" />
<link rel="stylesheet" href="{{asset('assets/css/datatables.css')}}">
<style>
	td {
		text-align: center !important;
		vertical-align: middle !important;
	}
	th {
		text-align: center !important;
		vertical-align: middle !important;
	}
</style>
@endsection

@section('content')
<div class="buy-now">
	<a href={{ route('add_item', $category->id) }} class="btn btn-danger btn-buy-now" style="bottom: 6rem;">{{ $category->name }}:カテゴリーへ</a>
</div>

<div class="content-wrapper">	
	<div class="container-xxl flex-grow-1 container-p-y">
		<div class="pagetitle" style="padding: 10px 30px 0px;">
			<nav>
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="/">{{ env('APP_NAME') }}</a></li>
					<li class="breadcrumb-item active">{{ $category->name }}</li>
				</ol>
			</nav>
		</div><!-- End Page Title -->
		<div class="card">
			<div class="card-body" style="overflow: auto;">
				<table class="table table-bordered table-hover datatable">
					<thead>
						<tr>
							<th>商品画像</th>
							<th style="width: 250px;">商品名</th>
							<th>ASIN</th>
							<th>JAN</th>
							<th>Amazon<br/>価格</th>
							<th>Yahoo<br/>価格</th>
							<th>Rakuten<br/>価格</th>
							<th>Keepa URL</th>
							<th>
								<span
									class="text-danger"
									data-condition="all"
									data-id="{{ $category->id }}" 
									data-bs-toggle="modal"
									data-bs-target="#confirmModal">
									<i class='bx bxs-trash'></i>
								</span>
								<span>
									<a href={{ route('csv', $category->id) }}><i class='bx bx-download text-primary'></i></a>
								</span>
							</th>
						</tr>
					</thead>
					<tbody>

					</tbody>
				</table>
			</div>
			<!-- /.card-body -->
		</div>
	</div>
</div>

<div class="modal fade" id="confirmModal" tabindex="-1" aria-modal="true" role="dialog">
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
	<script src="{{asset('assets/js/datatables.min.js')}}"></script>

	<script>

	$('#confirmModal').on('shown.bs.modal', function(e) {
		var target = e.relatedTarget.dataset;
		if (target.condition == 'one') {
			$('#btns').html(
				`<button type="button" class="btn btn-primary" onclick="deleteItem('one', ${target.id})">削除</button>
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">キャンセル</button>`
			);
		} else if (target.condition == 'all') {
			$('#btns').html(
				`<button type="button" class="btn btn-primary" onclick="deleteItem('all', ${target.id})">削除</button>
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">キャンセル</button>`
			);
		}
	}).on('hidden.bs.modal', function(e) {

	});

	const deleteItem = (num, id) => {
		$.ajax({
			url: '{{ route("delete_item") }}',
			type: 'post',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data: {
				condition: num,
				id: id
			},
			success: function(response) {
				toastr.success('データは正常に削除されました。');
				location.reload();
			}
		});
	};

    var datatable = $('.datatable').DataTable({
		processing: true,
		serverSide: true,
		autoConfig: true,
		pageLength: 10,
		ajax: {
			'url': "{{ route('item_datatable') }}",
			'data': function (d) {
				d.categoryId = <?php echo $category->id; ?>;
			},
		},
		columns: [{
				data: null,
				name: 'img_url',
				sortable: false,
				orderable: false,
				render:function(data, type ,row) {
					console.log(row);
					return (
						`<a href="${row.am_item_url}" target="_blank"><img src=${row.img_url} style="width: 64px; height: 64px;" /></a>`
					)
				}
			},
			{
				data: 'name',
				name: 'name',
				render:function(data, type ,row) {
					return (
						`<span title="Amazon Item Name">${row.name}</span>`
					)
				}
			},
			{
				data: 'asin',
				name: 'asin',
			},
			{
				data: 'jan',
				name: 'jan',
				render:function(data, type ,row) {
					return (
						`<span title="Yahoo URL">${row.jan == null ? 'ASIN ' + row.asin + 'に一致するJANコードは見つかりませんでした。' : row.jan}</span>`
					)
				}
			},
			{
				data: null,
				name: 'am_price',
				render:function(data, type ,row) {
					return (
						`<a href="https://www.amazon.co.jp/dp/${row.asin}?tag=<?php echo $category->partner_tag; ?>&linkCode=ogi&th=1&psc=1" target="_blank"><span title="Amazon URL">${row.am_price == 0 ? '取得中' : '￥' + row.am_price}</span></a>`
					)
				}
			},
			{
				data: null,
				name: 'ya_price',
				render:function(data, type ,row) {
					return (
						`<a href=${row.ya_item_url} target="_blank"><span title="Yahoo URL">${row.ya_price == 0 ? '取得中' : '￥' + row.ya_price}</span></a>`
					)
				}
			},
			{
				data: null,
				name: 'ra_price',
				render:function(data, type ,row) {
					return (
						`<a href=${row.ra_item_url} target="_blank"><span title="Rakuten URL">${row.ra_price == 0 ? '取得中' : '￥' + row.ra_price}</span></a>`
					)
				}
			},
			{
				data: null,
				name: null,
				render:function(data, type ,row) {
					return (
						`<a href='https://keepa.com/#!product/5-${row.asin}' target="_blank">
							<img style="width: 200px;" src='https://graph.keepa.com/pricehistory.png?asin=${row.asin}&domain=co.jp&salesrank=1' />
						</a>`
					)
				}
			},
			{
				data: null,
				name: 'id',
				sortable: false,
				orderable: false,
				render:function(data, type ,row) {
					return (
						`<span class="text-danger" data-condition="one" data-id="${row.id}" data-bs-toggle="modal" data-bs-target="#confirmModal"><i class='bx bxs-trash'></i></span>`
					)
				}
			},
		]
    });

	$(document).ready(function () {
		setInterval(() => {
			location.reload();
		}, 600 * 1000);
	});
	</script>
@endsection
