/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var app = angular.module("hackathon", ['ngRoute']);


app.config(function ($routeProvider) {
    $routeProvider.when("/home/:id", {
            templateUrl: "views/home.html",
            controller: "homeCon"
        }).when("/track", {
            templateUrl: "views/track.html",
            controller: "trackCon"
        }).when("/setting", {
            templateUrl: "views/setting.html",
            controller: "settingCon"
        }).when("/about", {
            templateUrl: "views/about.html"
        })
        .otherwise({
            redirectTo: "/"
        });
});