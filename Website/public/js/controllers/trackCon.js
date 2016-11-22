app.controller('trackCon', ['$scope', '$routeParams', function ($scope, rp) {
    componentHandler.upgradeAllRegistered();


    $scope.activitys = [];
    /*  {
            title: "記憶卡",
            finaldate: "2015/7/23",
            share: 1,
            like: 0,
            commit: 0,
            img: "../images/welcome.jpg",
            isBook: true
  }, {
            title: "記憶卡",
            finaldate: "2015/7/23",
            share: true,
            like: false,
            commit: true,
            isBook: false
  }
 ];*/
    var subscribeIds;
    $.ajax({
        url: "https://hackathon.bepsvpt.net/api/lotteries/getSubscribes",
        type: "GET",
        success: function (msg) {
            subscribeIds = msg;
            getCardInfo();
            $scope.$apply();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $scope.alertMes("網路錯誤");
        }
    });

    function getCardInfo() {
        $.ajax({
            url: "https://hackathon.bepsvpt.net/api/lotteries/getSubscribesList",
            data: "page=" + rp.id,
            type: "GET",
            success: function (msg) {
                $("#load").css("display", "none");
                //console.log(msg.slice(166,msg.length)  );
                for (var a = 0; a < msg.length; a++) {
                    msg[a].photoUrl = (msg[a].cover_url) ? msg[a].cover_url : msg[a].facebook_page.avatar_url;
                }
                $scope.activitys = msg;
                $scope.$apply();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $scope.alertMes("網路錯誤");
            }
        });
    }

    $scope.setCard = function () {

        for (var i = 0; i < subscribeIds.length; i++) {
            if (subscribeIds[i].lottery_id == $(".mdl-card__menu button", $($(".welcome")[this.$index])).data("id")) {
                $(".fa-stack-2x", $($(".welcome")[this.$index])).removeClass("fa-bookmark-o");
                $(".fa-stack-2x", $($(".welcome")[this.$index])).addClass("fa-bookmark");
                break;
            } else {
                $(".fa-stack-2x", $($(".welcome")[this.$index])).removeClass("fa-bookmark");
                $(".fa-stack-2x", $($(".welcome")[this.$index])).addClass("fa-bookmark-o");
            }
        }
        $($(".welcome")[this.$index]).css("animation-delay", (this.$index) % 4 * 0.1 + "s");

        $("input", $(".welcome")[this.$index]).slice(0, 1).data("checked", (($scope.activitys[this.$index].lottery_method.share == 1) ? true : false));
        $("input", $(".welcome")[this.$index]).slice(1, 2).data("checked", (($scope.activitys[this.$index].lottery_method.grate == 1) ? true : false));
        $("input", $(".welcome")[this.$index]).slice(2, 3).data("checked", (($scope.activitys[this.$index].lottery_method.reply == 1) ? true : false));
        if (this.$last == true) {
            componentHandler.upgradeAllRegistered();
            $(".welcome input[type='checkbox']").each(function () {
                if ($(this).data("checked") == true) {
                    $(this).parent().addClass("is-checked");
                } else {
                    $(this).parent().removeClass("is-checked");
                }
            });

            $(".welcome input[type='checkbox']").click(function () {
                if ($(this).data("checked") == true) {
                    $(this).parent().addClass("is-checked");
                } else {
                    $(this).parent().removeClass("is-checked");
                }
            });
        }
    };

    $scope.subscribe = function () {
        //console.log($("button", $(event.target).parent().parent().parent()));
        //console.log($("button", $(event.target).parent().parent()).data("id"));
        var ev = event;
        $.ajax({
            url: "https://hackathon.bepsvpt.net/api/lotteries/subscribe",
            data: "id=" + $("button", $(event.target).parent().parent().parent()).attr("data-id"),
            type: "POST",
            success: function (msg) {
                if (msg.success == true) {
                    if ($(".fa-stack-2x", $(ev.target).parent().parent()).hasClass("fa-bookmark-o")) {
                        $(".fa-stack-2x", $(ev.target).parent().parent()).removeClass("fa-bookmark-o");
                        $(".fa-stack-2x", $(ev.target).parent().parent()).addClass("fa-bookmark");
                    } else {
                        $(".fa-stack-2x", $(ev.target).parent().parent()).removeClass("fa-bookmark");
                        $(".fa-stack-2x", $(ev.target).parent().parent()).addClass("fa-bookmark-o");
                    }
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $scope.alertMes("網路錯誤");
            }
        });
    };
}]);