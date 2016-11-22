app.directive('confirm', function () {
	return {
		restrict: "E",
		scope: {
			info: "=",
			onyes: "="
		},
		templateUrl: "js/directives/confirm.html",
		link: function (scope, element, attrs) {
			scope.close = function () {
				element.fadeOut(400, function () {
					$(".four.wide.column").css("z-index", "0");
				});
			};
		}
	};
});