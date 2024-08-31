let email = 'admin@gmail.com';async function init() {
    
			let janCode = document.getElementById("itm_cat").innerHTML.match(/\d{13}/)[0];
			let name = document.querySelector('p[class="elName"]').innerHTML;
			let currentPrice = document.getElementsByClassName("elPriceNumber")[0].innerHTML.replace(/,/g, "");
			let y_img_url = document.getElementsByClassName("elPanelImage")[0].src;
			let y_shop_list = document.querySelector('meta[property="og:url"]').content;
			
			let inject = 
			'<form target="_blank" method="get" action="https://keepaautobuy.xsrv.jp/mypage/individual">' + 
				'<div class="form-group">' +
					'<label for="titles"><{{ env('APP_NAME') }}>' + email + '</label>' + 
					'<div style="margin-top: 10px;"><label for="target-price">目標価格</label>' + 
					'<input type="number" class="form-control" id="target-price" name="target-price" placeholder="1000" value="" style="width: 150px !important;" />円になったら通知する</div>' + 
					'<input type="hidden" name="email" value="' + email + '" />' + 
					'<input type="hidden" name="itemName" value="' + name + '" />' + 
					'<input type="hidden" name="itemCode" value="' + janCode + '" />' + 
					'<input type="hidden" name="current-price" value="' + currentPrice + '" />' + 
					'<input type="hidden" name="img-url" value="' + y_img_url + '" />' + 
					'<input type="hidden" name="shop-url" value="' + y_shop_list + '" />' + 
					'<div style="margin-top: 10px;"><button type="submit" class="btn btn-block btn-primary" style="background-color: lightblue; width: 200px !important;">トラッキング登録</button></div>' + 
				'</div>' +
			'</form>';
			let target = document.getElementById("prcdsp");
			target.innerHTML += inject;
		}
		init();