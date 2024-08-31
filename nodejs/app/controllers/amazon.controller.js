const axios = require("axios");
const amazonPaapi = require("amazon-paapi");
const { itemList, categoryList } = require("../models");

class GetItemInfo {
	constructor(category, code) {
		this.category = category;
		this.code = code;
	}

	async main() {
		let commonParameters = {
			AccessKey: this.category.access_key,
			SecretKey: this.category.secret_key,
			PartnerTag: this.category.partner_tag,
			PartnerType: "Associates",
			Marketplace: "www.amazon.co.jp",
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

		// Convert ASIN to JAN and store in db.
		await amazonPaapi
			.GetItems(commonParameters, requestParameters)
			.then((amazonData) => {
				// check if the response (amazonData) has errors and store in db
				if (amazonData.Errors !== undefined && amazonData.Errors.length > 0) {
					// console.log(x);
					var errors = amazonData.Errors;
					for (const e of errors) {
						var query = {
							category_id: this.category.id,
							user_id: this.category.user_id,
							name: '無効な ASIN コード',
							asin: e.Message.substr(11, 10),
						};

						itemList.create(query);
					}
				}

				// get JAN code from the response (amazonData) and store in db
				var items = amazonData.ItemsResult.Items;
				// console.log(items);
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
							query.register_price = price;
							query.target_price = Math.floor(price * (1 - this.category.am_fall_pro / 100));
						}

						if (i.DetailPageURL !== undefined && i.DetailPageURL !== '') {
							query.am_item_url = i.DetailPageURL;
						}
						if (i.Images !== undefined) {
							query.img_url = i.Images.Primary.Small.URL;
						}

						itemList.create(query);
					} catch (err) {
						console.log(
							"---------- forof item error ----------",
							err.message
						);

						// query.jan = `ASIN ${i.ASIN}に一致するJANコードは見つかりませんでした。`;
						// itemList.create(query);
					}
				}
			})
			.catch((err) => {
				console.log("---------- amazon data CATCH error ----------", err.message);
				for (const c of this.code) {
					let query = {};
					query.category_id = this.category.id;
					query.user_id = this.category.user_id;
					query.asin = c;
					itemList.create(query);
				}
			});
	}
}

const amazonInput = async (category, codeList) => {
	try {
		await itemList.destroy({where: {category_id: category.id}});
		// console.log(codeList);
		var index = 0;
		var len = codeList.length;

		var inputInterval = setInterval(() => {
			if (index < len) {
				let getItemInfo = new GetItemInfo(category, codeList.slice(index, (index + 10)));
				getItemInfo.main();
				index += 10;

				let query = {};
				query.is_reg = 1;
				query.len = len;
				query.reg_num = Math.min(len, index);

				categoryList.update(query, {where: {id: category.id}});
			} else {
				let query = {};
				query.is_reg = 0;
				query.round = 0;
				query.stop = 0;
				setTimeout(() => {
					categoryList.update(query, {where: {id: category.id}});
				}, 5000);
				clearInterval(inputInterval);
			}
		}, 11000);
	} catch (err) {
		console.log("Cannot input Rakuten, Yahoo information", err.message);
	}
};

exports.getInfo = (req, res) => {
	let reqData = JSON.parse(req.body.asin);

	categoryList
		.findByPk(reqData.category_id)
		.then((category) => {
			amazonInput(category, reqData.codes);
		})
		.catch((err) => {
			console.log("Cannot get user information.", err);
		});
};