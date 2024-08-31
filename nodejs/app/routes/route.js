const express = require("express");
const router = express.Router();
const amazon = require("../controllers/amazon.controller.js");

router.post("/get_info", amazon.getInfo);

module.exports = router;
