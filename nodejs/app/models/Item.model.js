module.exports = (sequelize, Sequelize) => {
	const ItemList = sequelize.define("items", {
		user_id: {
			type: Sequelize.INTEGER
		},
		category_id: {
			type: Sequelize.INTEGER
		},
		asin: {
			type: Sequelize.STRING
		},
		name: {
			type: Sequelize.STRING
		},
		img_url: {
			type: Sequelize.STRING
		},
		am_price: {
			type: Sequelize.INTEGER
		},
		am_item_url: {
			type: Sequelize.STRING
		},
		register_price: {
			type: Sequelize.INTEGER
		},
		target_price: {
			type: Sequelize.INTEGER
		},
		jan: {
			type: Sequelize.STRING
		},
		ya_price: {
			type: Sequelize.INTEGER
		},
		ya_item_url: {
			type: Sequelize.STRING
		},
		ra_price: {
			type: Sequelize.INTEGER
		},
		ra_item_url: {
			type: Sequelize.STRING
		},
		status: {
			type: Sequelize.INTEGER
		},
		am_notified: {
			type: Sequelize.INTEGER
		},
		ya_notified: {
			type: Sequelize.INTEGER
		},
		ra_notified: {
			type: Sequelize.INTEGER
		},
	},
	{ 
		timestamps: false
	});
	return ItemList;
};