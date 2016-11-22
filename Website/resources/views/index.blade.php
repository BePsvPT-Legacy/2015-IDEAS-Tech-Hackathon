<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1 ,user-scalable=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="msapplication-TileColor" content="#3372DF">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Material Design Lite</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.1/material.orange-light_blue.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.3.0/animate.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/main.css">
</head>

<body ng-app="hackathon" ng-controller="mainCon">
    <div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
        <header class="demo-header mdl-layout__header mdl-color--header mdl-color-text--white">
            <div class="mdl-layout__header-row">
                <span class="mdl-layout-title">臉書抽光光</span>
                <div class="mdl-layout-spacer"></div>
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--expandable">
                    <label class="mdl-button mdl-js-button mdl-button--icon" for="search" ng-click="t()">
                        <i class="material-icons">search</i>
                    </label>
                    <div class="mdl-textfield__expandable-holder">
                        <input class="mdl-textfield__input" type="text" id="search" />
                        <label class="mdl-textfield__label" for="search">Enter your query...</label>
                    </div>
                </div>
                <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" style="margin: 0.6em;margin-top:8px;overflow: visible;" id="info">
                    <i class="material-icons mdl-badge" data-badge="@{{open.length}}">announcement</i>
                </button>
                <div class="extra_panel" data-w="25%" data-h="291px">
                    <a class="info_wrap" ng-href="@{{a.href}}" ng-repeat="a in open" ng-init="setDeleteBtn()">
                        <div>
                            <img ng-src="@{{a.img}}">
                        </div>
                        <div>
                            <span class="label">@{{a.fans}}</span>
                            <label><i class="material-icons" style="transform:translateY(3px);font-size: 1.1em;margin-right:2px">redeem</i>@{{a.gift}}</label> 今天開獎喔
                        </div>
                        <button class="dBtn"><i class="material-icons" style="  font-size: 1.1em;color:  rgb(110, 109, 109);height: 23px;transform: translateY(2px);font-weight: 600;">clear</i>
                        </button>
                    </a>
                    <a class="info_wrap" style="display:none;" id="noninfo">
                        <h1><i class="material-icons" style="transform: translateY(3px) rotate(10deg);">error_outline</i>尚無新通知</h1>
                        <p>抽獎通知將在這裡顯示</p>
                    </a>
                </div>
            </div>
        </header>
        <div class="demo-drawer mdl-layout__drawer mdl-color--blue-grey-200 mdl-color-text--blue-grey-900" style="border: none;">
            <header class="demo-drawer-header">
                <img src="{{ ($guard->guest()) ? 'images/user.jpg' : $guard->user()->avatar }}" class="demo-avatar" style="transform: translateX(1em);">
                <div class="demo-avatar-dropdown">
                    <span style="transform: translateX(1em);">{{ ($guard->guest()) ? 'Guest' : $guard->user()->name }}</span>
                    <div class="mdl-layout-spacer"></div>
                </div>
            </header>
            <nav class="demo-navigation mdl-navigation mdl-color--blue-grey-50">
                <a class="mdl-navigation__link active" href="#/home/1"><i class="mdl-color-text--blue-grey-400 material-icons">redeem</i>抽獎資訊</a>
                <a class="mdl-navigation__link" href="#/track"><i class="mdl-color-text--blue-grey-400 material-icons">favorite_border</i>抽獎追蹤</a>
                <!--<a class="mdl-navigation__link" href="#/setting"><i class="mdl-color-text--blue-grey-400 material-icons">settings</i>設定</a>-->
                <a class="mdl-navigation__link" href="#about"><i class="mdl-color-text--blue-grey-400 material-icons">info_outline</i>關於</a>
            </nav>
        </div>
        <main class="mdl-layout__content mdl-color--grey-100">
            <div ng-view></div>
        </main>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.3/angular.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.3/angular-route.js" defer></script>
    <script src="https://storage.googleapis.com/code.getmdl.io/1.0.1/material.min.js" defer></script>
    <script src="js/main.js" defer></script>
    <script src="js/controllers/mainCon.js" defer></script>
    <script src="js/controllers/homeCon.js" defer></script>
    <script src="js/controllers/settingCon.js" defer></script>
    <script src="js/controllers/trackCon.js" defer></script>
    <script>
        window.open("#/home/1", "_self");

        function fadeout(a) {
            a.css({
                transition: "0.3s all",
                opacity: 0,
                transform: "translate(110%,0)"
            }), setTimeout(function () {
                a.css("display", "none")
            }, 300)
        }
        $(function () {
            $(".mdl-navigation__link").click(function () {
                $(".mdl-navigation__link").removeClass("active"), $(this).addClass("active")
            }), $("#info").click(function (a) {
                var t = 83 * $("i", $("#info")).attr("data-badge") + 5;
                5 == t && (t = 88, $("#noninfo").css("display", "block")), "0px" == $(".extra_panel").css("width") ? ($(".extra_panel").css({
                    padding: "20px 10px",
                    right: document.body.offsetWidth - a.pageX,
                    top: a.pageY,
                    width: "",
                    height: t + "px",
                    "max-height": .6 * document.body.offsetHeight,
                    "overflow-y": "auto"
                }), $(".extra_panel").addClass("open")) : ($(".extra_panel").css({
                    padding: "0px",
                    width: "0",
                    height: "0",
                    "overflow-y": "hidden"
                }), $(".extra_panel").removeClass("open")), a.stopPropagation()
            }), $("body").click(function () {
                $(".extra_panel").css({
                    padding: "0px",
                    width: "0",
                    height: "0",
                    "overflow-y": "hidden"
                }), $(".extra_panel").removeClass("open")
            })
        });
    </script>
</body>

</html>