'use strict';
const Const = require("./config.js").constant;
var tools = require("./tools.js"),
url = require("url"),
request = require("request"),
mysql = require('mysql'),
step = require("step");

var lastScanTime = 0,
FBAppToken = "",
serToken = "",
postIdList_cache = new Array();

var connection = mysql.createConnection({
		host : Const.MYSQL_HOST,
		port : Const.MYSQL_PORT,
		user : Const.MYSQL_USER,
		password : Const.MYSQL_PWD,
		database : Const.MYSQL_DB
	});
connection.connect(function (err) {
	if (err) {
		console.error('error connecting: ' + err.stack);
		return;
	}
	console.log('connected as id ' + connection.threadId);
});

crawler();
setInterval(crawler, Const.SCANNING_INTERVAL);

function crawler() {
	step(function () {
		getLastScanTime(this.parallel());
		getFBAppToken(this.parallel());
		getToken(this.parallel());
	}, searchKeyword);
}

function getLastScanTime(callback) {
	connection.query('SELECT `content` FROM `configs` WHERE `name` = "last_scan_time"', function (err, result) {
		if (err) {
			console.error(err);
			return;
		}
		lastScanTime = Number(result[0].content);
		callback();
	});
}

function getFBAppToken(callback) {
	request({
		url : "https://graph.facebook.com/oauth/access_token?grant_type=client_credentials&client_id=" + Const.FB_CLIENT_ID + "&client_secret=" + Const.FB_CLIENT_SECRET,
		method : "GET"
	}, function (error, r, token) {
		token = tools.URL2JSON(token);
		if (error === null) {
			FBAppToken = token.access_token;
			callback();
		} else {
			console.error("Get FB APP token error!!\n" + error);
			return null;
		}
	});
}

function getToken(callback) {
	request({
		url : "http://api.ser.ideas.iii.org.tw/api/user/get_token",
		method : "POST",
		headers : {
			'content-type' : 'application/json'
		},
		form : {
			id : Const.CODE_ID,
			secret_key : Const.SECRET_KEY
		}
	}, function (error, r, tokenResult) {
		tokenResult = JSON.parse(tokenResult);
		if (error === null && tokenResult.message === "success") {
			serToken = tokenResult.result.token;
			callback();
		} else
			console.error("Get token error!!\n" + error);
	});
}

function searchKeyword(err) {
	if (err) {
		console.error(err);
		throw err;
	}
	request({
		url : "http://api.ser.ideas.iii.org.tw/api/keyword_search/facebook",
		method : "POST",
		form : {
			keyword : Const.KEYWORD,
			page : 9999,
			sort : "time_desc",
			token : serToken
		}
	}, function (error, r, totalResult) {
		totalResult = JSON.parse(totalResult);
		for (var i = 1; i <= totalResult.total / 100; i++) {
			request({
				url : "http://api.ser.ideas.iii.org.tw/api/keyword_search/facebook",
				method : "POST",
				form : {
					keyword : Const.KEYWORD,
					page : (function () {
						console.log(i);
						return i;
					})(),
					sort : "time_desc",
					token : serToken
				}
			}, function (error, r, searchResult) {
				searchResult = JSON.parse(searchResult);
				console.log(searchResult.result.length);
				if (error === null && searchResult.message === "success") {
					filterPage(searchResult.result);
				} else
					console.error("Get search result error!!\n" + error);
			});
		}
	});
}

function filterPage(result) {
	for (var i = result.length - 1; i >= 0; i--) {
		var pageId = result[i].page_id_name.split(" ")[0],
		postId = url.parse(result[i].url).pathname.split("/")[3],
		pageOrPersonId = url.parse(result[i].url).pathname.split("/")[1];
		if (pageId === pageOrPersonId) {
			result[i].pageId = pageId;
			result[i].poseId = postId;
			result[i].postName = result[i].page_id_name.split(" ")[1];
			checkPage(pageId, postId, result[i]);
		}
	}
}

function checkPage(pageId, postId, data) { // Check data of page is exist.
	var query = connection.query('SELECT `id` FROM facebook_pages WHERE `page_id` = ?', [pageId], function (err, result) {
			if (err) {
				console.error(err);
				return;
			}
			if (result[0] === undefined) {
				getPage(pageId, postId, data);
			} else {
				console.log("has");
				getPost(pageId, postId, result[0].id, data);
			}
		});
}

function getPage(pageId, postId, data) {
	if (postIdList_cache.indexOf(pageId) === -1) {
		postIdList_cache.push(pageId);
		request({
			url : "https://graph.facebook.com/v2.4/" + pageId + "?fields=name,cover{source},picture{url}&access_token=" + FBAppToken,
			method : "GET"
		}, function (error, r, page) {
			if (error) {
				console.error(error);
				return;
			}
			page = JSON.parse(page);
			var query = connection.query('INSERT INTO facebook_pages SET ?', {
					name : page.name,
					page_id : page.id,
					avatar_url : (page.picture ? page.picture.data.url : null),
					cover_url : (page.cover ? page.cover.source : null)
				}, function (err, result) {
					if (err) {
						console.error("import page error: " + err);
						console.log("import page: ");
						console.log(result);
						console.log("sql:");
						console.log(query.sql);
					} else getPost(page.id, postId, result.insertId, data);
				});
		});
	}
}
var deal = 0;
function getPost(pageId, postId, id, data) {
	request({
		url : "https://graph.facebook.com/v2.4/" + pageId + "_" + postId + "?fields=id,created_time,full_picture,story,message&access_token=" + FBAppToken,
		method : "GET"
	}, function (error, r, post) {
		if (error) {
			console.error("Get post error!!\n" + error);
		}
		post = JSON.parse(post);
		if (post.story !== undefined) // Is a page share another post of page.
			return;
		var query = connection.query('INSERT INTO lotteries SET ?', { // Save post
				facebook_page_id : id,
				content : post.message,
				article_url : data.url,
				cover_url : (post.full_picture ? post.full_picture : null),
				published_at : data.time,
				expired_at : "null"
			}, function (err, result) {
				console.log(deal++);
				if (err)
					console.error("\n\nerror: " + query.sql + "\n" + err);
				else
					console.log("Import success");
			});
	});
}
