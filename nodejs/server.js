const express = require("express");
const cors = require("cors");
const app = express();
const route = require("./app/routes");
const db = require("./app/models");
const amazon = require("./app/controllers/tracking");

var corsOptions = {
	origin: ['http://localhost:8000']
};

app.use(cors(corsOptions));
// parse requests of content-type - application/json
app.use(express.json());
// parse requests of content-type - application/x-www-form-urlencoded
app.use(express.urlencoded({ extended: true }));

app.get("/", (req, res) => {
	res.json({ message: "Welcome to bezkoder application"});
});
route(app);
const PORT = process.env.PORT || 32767;
app.listen(PORT, () => {
	console.log(`Server is running on port ${PORT}.`);
});

amazon.updateInfo();