app.controller('mainCon', ['$scope', function ($scope) {

	$scope.open = [
		    {
				gift: "記憶卡",
	fans: "表特彰師",
	img: "images/welcome.jpg",
	href: "https://www.facebook.com/BeautyNCUE?fref=ts"
			}, {
				gift: "硬碟",
				fans: "表特彰師",
				img: "images/welcome.jpg",
				href: "https://www.facebook.com/BeautyNCUE?fref=ts"
			}
		];
	$scope.t = function () {
		$("i", $("#info")).addClass("mdl-badge");
		$("#noninfo").css("display", "none");
		$scope.open = [{
				gift: "記憶卡",
				fans: "表特彰師",
				img: "images/welcome.jpg",
				href: "https://www.facebook.com/BeautyNCUE?fref=ts"
				}, {
					gift: "硬碟",
					fans: "表特彰師",
					img: "images/welcome.jpg",
					href: "https://www.facebook.com/BeautyNCUE?fref=ts"
				}, {
					gift: "GTX980",
					fans: "表特彰師",
					img: "images/welcome.jpg",
					href: "https://www.facebook.com/BeautyNCUE?fref=ts"
				}, {
					gift: "R9-390",
					fans: "表特彰師",
					img: "images/welcome.jpg",
					href: "https://www.facebook.com/BeautyNCUE?fref=ts"
				}
		];
		$("i", $("#info")).attr("data-badge", $scope.open.length);
		if ($scope.open.length == 0) $("i", $("#info")).removeClass("mdl-badge");
	};

	$scope.setDeleteBtn = function () {
		var t = $(".extra_panel a:nth-child(" + (this.$index + 1) + ")");
		t.data("id", this.$index);

		function deleteItem() {
			if (localStorage.getItem("deleteRec") == undefined) {
				var temp = [];
				temp.push($scope.open[t.data('id')]);
				localStorage.setItem("deleteRec", JSON.stringify(temp));
			} else {
				var temp = JSON.parse(localStorage.getItem("deleteRec"));
				temp.push($scope.open[t.data('id')]);
				localStorage.setItem("deleteRec", JSON.stringify(temp));
			}

			fadeout(t);
			setTimeout(function () {
				var boble = $("i", $("#info"));
				boble.attr("data-badge", parseInt(boble.attr("data-badge")) - 1);
				var hei = boble.attr("data-badge") * 83 + 5;
				$(".extra_panel").css("height", hei + "px");
				if (boble.attr("data-badge") == 0) {
					$("#noninfo").css("display", "block");
					$(".extra_panel").css("height", 88 + "px");
					$("i", $("#info")).removeClass("mdl-badge");
				}
			}, 300);
		}

		$(".dBtn", t).click(function (e) {
			deleteItem();
			e.preventDefault();
			e.stopPropagation();
		});
		var touX, touY;
		var move;
		t[0].addEventListener("touchstart", function (e) {
			touX = e.touches[0].clientX;
			touY = e.touches[0].clientY;
		});
		t[0].addEventListener("touchmove", function (e) {
			move = e.touches[0].clientX - touX;
			t.css("transform", "translateX(" + move + "px)");
		});
		t[0].addEventListener("touchend", function (e) {
			if (move > (t[0].offsetWidth / 2)) {
				deleteItem();
			} else {
				t.css("transform", "translateX(0px)");
				t.css("transition", "0.2s all");
				setTimeout(function () {
					t.css("transition", "");
				}, 200);
			}
		});

	};
}]);