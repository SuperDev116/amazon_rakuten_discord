const axios = require("axios");
const amazonPaapi = require('amazon-paapi');
const { itemList, logList, categoryList } = require("../models");

exports.updateInfo = async () => {
	await categoryList
		.findAll()
		.then((res) => {
			for (let category of res) {
				amazonTracking(category);
				rakutenTracking(category);
				yahooTracking(category);
			}
		})
		.catch((err) => {
			console.log("Cannot access user data>>>>>>>>>>", err.message);
		});
};

amazonTracking = async (category) => {
	// console.log("-------------------->>>>>>>>>>>>>>>>>>>>>>>>>>>>>", category.id);
	await itemList.findAll({ where: { category_id: category.id } })
		.then(items => {
			var index = 0;
			var len = items.length;
			var asins = [];
			for (const i of items) {
				asins.push(i.asin);
			}

			let checkInterval = setInterval(() => {
				categoryList.findByPk(category.id)
					.then((data) => {
						let query = {};
						if (len == 0) {
							data.stop = 1;
						}
						if (data.stop == 0) {
							query.len = len;
							if (index < len) {
								let checkAmazonInfo = new CheckAamzonInfo(category, asins.slice(index, (index + 10)));
								checkAmazonInfo.main();
								index += 10;

								query.trk_num = index;
								query.round = data.round;
							} else {
								clearInterval(checkInterval);
								amazonTracking(category);
								index = 0;

								query.round = data.round + 1;
							}
						} else if (data.stop == 1) {
							index = 0;
							query.round = 0;
							query.trk_num = 0;
							clearInterval(checkInterval);
							amazonTracking(category);
						}
						categoryList.update(query, { where: { id: category.id } });
					});
			}, 20000);
		}).catch(err => {
			console.log('---------- itemlist error ----------', err.message);
		});
};

class CheckAamzonInfo {
	constructor(category, code) {
		this.category = category;
		this.code = code;
	}

	async main() {
		let commonParameters = {
			AccessKey: this.category.access_key,
			SecretKey: this.category.secret_key,
			PartnerTag: this.category.partner_tag,
			PartnerType: 'Associates',
			Marketplace: 'www.amazon.co.jp',
		};

		let requestParameters = {
			ItemIds: this.code,
			ItemIdType: 'ASIN',
			Condition: 'New',
			Resources: [
				'ItemInfo.ExternalIds',
				'Offers.Summaries.LowestPrice',
				'Images.Primary.Small',
				"ItemInfo.Title"
			],
		};

		await amazonPaapi.GetItems(commonParameters, requestParameters)
			.then(async (amazonData) => {
				if (amazonData.Errors !== undefined && amazonData.Errors.length > 0) {
					var errors = amazonData.Errors;
					for (const e of errors) {
						var query = {
							category_id: this.category.id,
							user_id: this.category.user_id,
							asin: e.Message.substr(11, 10),
							name: '無効な ASIN コード'
						};

						itemList.update(query, {
							where: {
								asin: e.Message.substr(11, 10),
								category_id: this.category.id
							}
						});
					}
				}

				var items = amazonData.ItemsResult.Items;
				for (const i of items) {
					try {
						var query = {};
						query.category_id = this.category.id;
						query.user_id = this.category.user_id;
						query.asin = i.ASIN;

						if (i.ItemInfo === undefined) {
							query.name = `ASIN ${i.ASIN}に一致する商品は見つかりませんでした。`;
						} else {
							query.name = i.ItemInfo.Title.DisplayValue;
							if (i.ItemInfo.ExternalIds !== undefined) {
								query.jan = i.ItemInfo.ExternalIds.EANs.DisplayValues[0];
							} else {
								// query.jan = `ASIN ${i.ASIN}に一致するJANコードは見つかりませんでした。`;
							}
						}

						let price = 0;
						if (i.Offers !== undefined) {
							if (i.Offers.Summaries[0].Condition.Value == 'New') {
								price = i.Offers.Summaries[0].LowestPrice.Amount;
							} else if (i.Offers.Summaries.length > 1 && i.Offers.Summaries[1].Condition.Value == 'New') {
								price = i.Offers.Summaries[1].LowestPrice.Amount;
							}
						}
						if (price != 0 && price !== undefined) {
							// Let's assume register_price as the price of an Amazon Product
							query.am_price = price;
						}

						if (i.DetailPageURL !== undefined && i.DetailPageURL !== '') {
							query.am_item_url = i.DetailPageURL;
						}
						if (i.Images !== undefined) {
							query.img_url = i.Images.Primary.Small.URL;
						}
						// console.log('---------------------------->>>>>>>>>>>>>>>>>>>>>>>>>>>>', query);

						await itemList.update(query, {
							where: {
								asin: i.ASIN,
								category_id: this.category.id
							}
						});

						await itemList.findOne({
							where: {
								asin: i.ASIN,
								category_id: this.category.id
							}
						})
							.then(itemRes => {
								
								if (query.am_price < itemRes.target_price && query.am_price > this.category.am_target_price) {
									if (itemRes.name.includes('中古')) return;
									if (itemRes.name.includes('レンタル')) return;
									if (itemRes.name.includes('アウトレット')) return;
									if (itemRes.name.includes('リサイクル')) return;
									if (itemRes.name.includes('再生品')) return;
									if (itemRes.name.includes('箱無し')) return;

									if (query.am_price < itemRes.register_price / 2) return;

									var name = "商品名:" + itemRes.name;
									var tar_price = "前回の価格:" + itemRes.register_price;
									var cur_price = "今回の価格:" + query.am_price;
									var productUrl = "URL:" + query.am_item_url;
									var category = "大カテゴリー名:" + this.category.name;
									var shop = "出品者:" + "サードパーティー";
									var asin = "ASIN:" + itemRes.asin;
									var keepaUrl = "https://keepa.com/#!product/5-" + itemRes.asin;
									var productImgUrl = "https://graph.keepa.com/pricehistory.png?key=6trubr9p3mrqrvecb6jihjq33mgiitmckbf3lj44e32equehfodic3kkf2atpf02&asin=" + itemRes.asin + "&domain=co.jp&salesrank=1";

									var axios = require('axios');
									var data = JSON.stringify({
										"content": tar_price + '\n' + cur_price + '\n' + productUrl + '\n' + category + '\n' /*+ ranking*/ + '\n' + shop + '\n' + asin + '\n' + keepaUrl + '\n' + productImgUrl
									});

									var config = {
										method: "post",
										maxBodyLength: Infinity,
										url: this.category.am_web_hook,
										headers: {
											"Content-Type": "application/json",
										},
										data: data,
									};

									var note = {
										user_id: this.category.user_id,
										category_id: this.category.name + "\n" + this.category.am_web_hook,
										asin: itemRes.asin,
										msg: name + "<br/>" + tar_price + "<br/>" + cur_price + "<br/>" + productUrl,
									};

									var updateQuery = {
										asin: itemRes.asin,
										category_id: this.category.id
									};

									axios(config)
										.then(function () {
											var query = {};
											query.am_notified = 1;
											itemList.update(query, { where: updateQuery });
											logList.create(note);
										})
										.catch(function (err) {
											console.log('cannot send msg to discord')
										});
								}
							});
						
						var postQuery = {};
						postQuery.register_price = price;
						postQuery.target_price = Math.floor(price * (1 - this.category.am_fall_pro / 100));
						await itemList.update(postQuery, {
							where: {
								asin: i.ASIN,
								category_id: this.category.id
							}
						});

					} catch (err) {
						console.log(
							"---------- forof item error ----------",
							err.message
						);

						// query.asin = i.ASIN;
						// query.jan = `ASIN ${i.ASIN}に一致するJANコードは見つかりませんでした。`;
						// itemList.update(query, {where: {
						// 	asin: i.ASIN,
						// 	category_id: this.category.id
						// }});
					}
				}
			}).catch(err => {
				console.log('amazon data err', err.message);
				for (const c of this.code) {
					let query = {};
					query.category_id = this.category.id;
					query.user_id = this.category.user_id;
					query.asin = c;
					itemList.update(query, {
						where: {
							asin: c,
							category_id: this.category.id
						}
					});
				}
			});
	}
}

rakutenTracking = async (category) => {
	await itemList
		.findAll({ where: { category_id: category.id } })
		.then((items) => {
			var index = 0;

			var len = items.length;
			let checkInterval = setInterval(() => {
				categoryList.findByPk(category.id)
					.then((data) => {
						let query = {};
						if (len == 0) {
							data.stop = 1;
						}
						if (data.stop == 0) {
							query.len = len;
							if (index < len) {
								let checkRakutenInfo = new CheckRakutenInfo(category, items[index]);
								checkRakutenInfo.main();
								index++;
							} else {
								clearInterval(checkInterval);
								rakutenTracking(category);
								index = 0;
							}
						} else if (data.stop == 1) {
							index = 0;
							query.round = 0;
							query.trk_num = 0;
							clearInterval(checkInterval);
							rakutenTracking(category);
						}
						categoryList.update(query, { where: { id: category.id } });
					});
			}, 2000);
		})
		.catch((err) => {
			console.log("rakuten tracking function error>>>>>>>>>>", err.message);
		});
};

class CheckRakutenInfo {
	constructor(category, item) {
		this.item = item;
		this.query = {};
		this.result = {};
		this.category = category;
	}

	async main() {

		this.query.user_id = this.category.user_id;
		this.query.category_id = this.category.id;
		var keyword_code = (this.item.jan != null) ? this.item.jan : this.item.asin;
		let url = `https://app.rakuten.co.jp/services/api/IchibaItem/Search/20220601?format=json&keyword=${keyword_code}&NGKeyword=%E4%B8%AD%E5%8F%A4%20%E3%82%A2%E3%82%A6%E3%83%88%E3%83%AC%E3%83%83%E3%83%88%20%E3%83%AC%E3%83%B3%E3%82%BF%E3%83%AB%20%E3%83%AA%E3%82%B5%E3%82%A4%E3%82%AF%E3%83%AB%20%E5%86%8D%E7%94%9F%E5%93%81%20%E7%AE%B1%E7%84%A1%E3%81%97&orFlag=1&sort=%2BitemPrice&minPrice=${this.category.am_target_price}&affiliateId=${this.category.affiliate_id}&applicationId=${this.category.application_id}`;

		await axios
			.get(url, {})
			.then(async (res) => {

				if (res.data.count > 0) {
					this.result = res.data.Items[0];

					this.query.ra_item_url = this.result.Item.affiliateUrl;
					// this.query.name = this.result.Item.itemName;
					// this.query.caption = this.result.Item.itemCaption;
					// this.query.shop_url = this.result.Item.shopAffiliateUrl;
					this.query.ra_price = Number(this.result.Item.itemPrice);
					// this.query.status = 1;
				} else {
					// this.query.name = "ASINに一致する商品は見つかりませんでした。";
					// this.query.status = 0;
				}

				itemList.update(this.query, {
					where:
					{
						asin: this.item.asin,
						category_id: this.category.id
					}
				});

				if (this.query.ra_price < (this.item.am_price * (100 - this.category.ra_fall_pro) / 100) && this.query.ra_price > this.category.ra_target_price) {

					if (this.query.ra_price < this.item.am_price / 2) return;

					var name = "商品名:" + this.item.name;
					var amazonPrice = "Amazon価格:" + this.item.am_price;
					var rakutenPrice = "Rakuten価格:" + this.query.ra_price;
					var amazonUrl = `Aamazon shopping URL: https://www.amazon.co.jp/dp/${this.item.asin}?tag=${this.category.partner_tag}&linkCode=ogi&th=1&psc=1`;
					var rakutenUrl = "Rakuten shopping URL:" + this.query.ra_item_url;
					var category = "大カテゴリー名:" + this.category.name;
					var asin = "ASIN:" + this.item.asin;
					var jan = "JAN:" + this.item.jan;
					var keepaUrl = "https://keepa.com/#!product/5-" + this.item.asin;
					var productImgUrl =
						`https://graph.keepa.com/pricehistory.png?key=6trubr9p3mrqrvecb6jihjq33mgiitmckbf3lj44e32equehfodic3kkf2atpf02&asin=${this.item.asin}&domain=co.jp&salesrank=1`;

					var axios = require("axios");
					var data = JSON.stringify({
						content:
							name +
							"\n" +
							amazonPrice +
							"\n" +
							rakutenPrice +
							"\n" +
							amazonUrl +
							"\n" +
							rakutenUrl +
							"\n" +
							category +
							"\n" +
							asin +
							"\n" +
							jan +
							"\n" +
							keepaUrl +
							"\n" +
							productImgUrl
					});

					var config = {
						method: "post",
						maxBodyLength: Infinity,
						url: this.category.ra_web_hook,
						headers: {
							"Content-Type": "application/json",
						},
						data: data,
					};

					var note = {
						user_id: this.category.user_id,
						category_id: this.category.name + "\n" + this.category.ra_web_hook,
						asin: this.item.asin,
						msg: name + "<br/>" + amazonPrice + "<br/>" + rakutenPrice + "<br/>" + amazonUrl + "<br/>" + rakutenUrl,
					};

					var updateQuery = {
						jan: this.item.jan,
						category_id: this.category.id
					};

					axios(config)
						.then(function () {
							var query = {};
							query.ra_notified = 1;
							itemList.update(query, { where: updateQuery });
							logList.create(note);
							console.log('!!!!!!!!!!!!!!!!!!!!!!!!!!!!!notification sent successfully!!!')
						})
						.catch(function (err) {
							console.log('cannot send msg to discord');
						});
				}

			})
			.catch((err) => {
				console.log("rakuten error");
			});
	}
}

yahooTracking = async (category) => {
	await itemList
		.findAll({ where: { category_id: category.id } })
		.then((items) => {
			var index = 0;

			var len = items.length;
			let checkInterval = setInterval(() => {
				categoryList.findByPk(category.id)
					.then((data) => {
						let query = {};
						if (len == 0) {
							data.stop = 1;
						}
						if (data.stop == 0) {
							query.len = len;
							if (index < len) {
								let checkYahooInfo = new CheckYahooInfo(category, items[index]);
								checkYahooInfo.main();
								index++;

								// query.trk_num = index;
								// query.round = data.round;
							} else {
								clearInterval(checkInterval);
								yahooTracking(category);
								index = 0;

								// query.round = data.round + 1;
							}
						} else if (data.stop == 1) {
							index = 0;
							// query.round = 0;
							// query.trk_num = 0;
							clearInterval(checkInterval);
							yahooTracking(category);
						}
						categoryList.update(query, { where: { id: category.id } });
					});
			}, 4000);
		})
		.catch((err) => {
			console.log("yahoo tracking function error>>>>>>>>>>", err.message);
		});
};

class CheckYahooInfo {
	constructor(category, item) {
		this.item = item;
		this.query = {};
		this.result = {};
		this.category = category;
	}

	async main() {

		this.query.user_id = this.category.user_id;
		this.query.category_id = this.category.id;

		if (this.item.jan == null) return;

		let url =
			`https://shopping.yahooapis.jp/ShoppingWebService/V3/itemSearch?appid=${this.category.yahoo_id}&jan_code=${this.item.jan}&image_size=76&results=1&price_from=${this.category.ya_target_price}&in_stock=true&sort=%2Bprice&condition=new`;
		// console.log(url);
		await axios
			.get(url, {})
			.then(async (res) => {
				if (res !== undefined && res.data.hits.length > 0) {
					this.result = res.data.hits[0];

					// this.query.img_url = this.result.image.small;
					// this.query.name = this.result.name;
					this.query.ya_price = Number(this.result.price);
					this.query.ya_item_url = this.result.url;
					// this.query.status = 1;
				} else {
					// this.query.name = `JAN ${this.item.jan}に一致する商品は見つかりませんでした。`;
					// this.query.status = 0;
				}

				itemList.update(this.query, {
					where: {
						asin: this.item.asin,
						category_id: this.category.id
					}
				});

				// if (this.query.ya_price < 10000) {
				// if (this.query.ya_price < (this.item.am_price * (100 - this.category.ya_fall_pro) / 100) && this.query.ya_price > this.category.ya_target_price && this.item.ya_notified == 0) {
				if (this.query.ya_price < (this.item.am_price * (100 - this.category.ya_fall_pro) / 100) && this.query.ya_price > this.category.ya_target_price) {
					if (this.item.name.includes('中古')) return;
					if (this.item.name.includes('レンタル')) return;
					if (this.item.name.includes('アウトレット')) return;
					if (this.item.name.includes('リサイクル')) return;
					if (this.item.name.includes('再生品')) return;
					if (this.item.name.includes('箱無し')) return;

					if (this.query.ya_price < this.item.am_price / 2) return;

					var name = "商品名:" + this.item.name;
					var amazonPrice = "Amazon価格:" + this.item.am_price;
					var yahooPrice = "Yahoo価格:" + this.query.ya_price;
					var amazonUrl = `Aamazon shopping URL: https://www.amazon.co.jp/dp/${this.item.asin}?tag=${this.category.partner_tag}&linkCode=ogi&th=1&psc=1`;
					var yahooUrl = `Yahoo shopping URL: ${this.query.ya_item_url}`;
					var category = "大カテゴリー名:" + this.category.name;
					var asin = "ASIN:" + this.item.asin;
					var jan = "JAN:" + this.item.jan;
					var keepaUrl = "https://keepa.com/#!product/5-" + this.item.asin;
					var productImgUrl =
						`https://graph.keepa.com/pricehistory.png?key=6trubr9p3mrqrvecb6jihjq33mgiitmckbf3lj44e32equehfodic3kkf2atpf02&asin=${this.item.asin}&domain=co.jp&salesrank=1`;

					var axios = require("axios");
					var data = JSON.stringify({
						content:
							name +
							"\n" +
							amazonPrice +
							"\n" +
							yahooPrice +
							"\n" +
							amazonUrl +
							"\n" +
							yahooUrl +
							"\n" +
							category +
							"\n" +
							asin +
							"\n" +
							jan +
							"\n" +
							keepaUrl +
							"\n" +
							productImgUrl
					});

					var config = {
						method: "post",
						maxBodyLength: Infinity,
						url: this.category.ya_web_hook,
						headers: {
							"Content-Type": "application/json",
						},
						data: data,
					};

					var note = {
						user_id: this.category.user_id,
						category_id: this.category.name + "\n" + this.category.ya_web_hook,
						asin: this.item.asin,
						msg: name + "</br>" + amazonPrice + "</br>" + yahooPrice + "</br>" + amazonUrl + "</br>" + yahooUrl,
					};

					var updateQuery = {
						asin: this.item.asin,
						category_id: this.category.id
					};

					axios(config)
						.then(function () {
							var query = {};
							query.ya_notified = 1;
							itemList.update(query, { where: updateQuery });
							logList.create(note);
							console.log('!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! notification sent successfully !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!');
						})
						.catch(function (err) {
							console.log(
								"????????????????????????????????????????????? cant notify to discord ?????????????????????????????????????????????",
								err.message
							);
						});
				}
			})
			.catch((err) => {
				console.log("yahoooooooooooooooooooooooooooooooooo update error>>>>>>>>>>", err.message);
			});
	}
}
