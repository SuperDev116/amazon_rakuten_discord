const dbConfig = require("../config/db.config.js");
const Sequelize = require("sequelize");
const sequelize = new Sequelize(dbConfig.DB, dbConfig.USER, dbConfig.PASSWORD, {
	host: dbConfig.HOST,
	dialect: dbConfig.dialect,
	operatorsAliases: false,
	pool: {
		max: dbConfig.pool.max,
		min: dbConfig.pool.min,
		acquire: dbConfig.pool.acquire,
		idle: dbConfig.pool.idle
	}
});
const db = {};
db.Sequelize = Sequelize;
db.sequelize = sequelize;

db.userList = require("./User.model.js")(sequelize, Sequelize);
db.itemList = require("./Item.model.js")(sequelize, Sequelize);
// db.am_itemList = require("./Amitems.model.js")(sequelize, Sequelize);
// db.ra_itemList = require("./Raitems.model.js")(sequelize, Sequelize);
// db.ya_itemList = require("./Yaitems.model.js")(sequelize, Sequelize);
db.logList = require("./MailLog.model.js")(sequelize, Sequelize);
db.categoryList = require("./Category.model.js")(sequelize, Sequelize);
module.exports = db;