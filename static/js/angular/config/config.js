var app = angular.module('learningPlatform', ['ngAnimate', 'ngRoute', 'ui.router', 'ui.bootstrap']);
app.config(['$urlRouterProvider', '$locationProvider', '$stateProvider', function ($urlRouterProvider, $locationProvider, $stateProvider) {
	$locationProvider.html5Mode(true);
	$stateProvider.
	state('index', {
		url: '/',
		templateUrl: '/template/index.html',
		controller: 'index',
		onEnter: ['$rootScope', function ($rootScope) {
			$rootScope.$broadcast('userData:willRefresh');
		}]
	}).
	state('course', {
		url: '/course',
		template: '<div ui-view></div>'
	}).
	state('course.search', {
		url: '/search/:ownerId/:name',
		templateUrl: '/template/course/search.html',
		controller: 'course.search'
	}).
	state('signin', {
		url: '/signin',
		templateUrl: '/template/user/signin.html',
		controller: 'signin'
	}).
	state('signup', {
		url: '/signup',
		templateUrl: '/template/user/signup.html',
		controller: 'signup'
	}).
	state('error', {
		url: '/error',
		templateUrl: '/template/error/frame.html'
	}).
	state('error.404', {
		url: '/404',
		templateUrl: '/template/error/404.html'
	});
	$urlRouterProvider.otherwise('/error/404');
}]);
