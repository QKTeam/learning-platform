(function() {
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
	app.run(['$rootScope', '$http', '$timeout', '$state', function($rootScope, $http, $timeout, $state) {
		$rootScope.$state = $state;

		var fetchUserData = function () {
			$http.get('/api/user/data').success(function(response) {
				if(response['code'] === '0000') {
					$rootScope.user = response['response'];
				}
				$rootScope.$broadcast('userData:didRefresh');
				if($rootScope.user.signin) $rootScope.$broadcast('user:didSignIn');
			}).error(function () {
				alert('Network Error!');
			});
		};
		$rootScope.$on('refreshUserData', function () {
			$timeout(fetchUserData, 0);
		});

		$rootScope.teachers = [
			{uid: 0, username: '所有老师'},
			{uid: 1, username: 'AA'},
			{uid: 2, username: 'BB'},
			{uid: 3, username: 'CC'},
		];
		var fetchTeachersList = function () {
		}
		$timeout(fetchTeachersList, 0);

		$scope.users = [];
		var fetchUsersList = function () {
		}
		$timeout(fetchUsersList, 0);

		$rootScope.getUserBasicInfoByUid = function (uid) {
			for (var i = $rootScope.users.length - 1; i >= 0; i--) {
				if($rootScope.users[i].uid == uid) {
					return $rootScope.users[i];
				}
			}
			return false;
		}
	}]);
	app.controller('course.search', ['$scope', '$rootScope', '$http', '$state', function($scope, $rootScope, $http, $state){
	}]);
	app.controller('signin', ['$scope', '$rootScope', '$http', '$state', function($scope, $rootScope, $http, $state){
		$scope.user = {
			username: '',
			password: ''
		};
		$scope.login = function() {
			var user = angular.copy($scope.user);
			user.password = md5(user.password);
			$http.post('/api/user/signin', user).success(function (response) {
				if(response['code'] === '0000') {
					$state.go('index');
				}
				else {
					alert('账户与密码不匹配，请重新输入！');
				}
			}).error(function () {
				alert('Network Error!');
			});
		}
	}]);
	app.controller('signup', ['$scope', '$rootScope', '$http', '$state', function($scope, $rootScope, $http, $state){
	}]);
	app.controller('index', ['$scope', '$rootScope', '$http', '$timeout', function($scope, $rootScope, $http, $timeout){
	}]);
	app.controller('nav', ['$scope', '$state', function($scope, $state){
		$scope.searchKey = {
			ownerId: 0,
			name: ''
		}
		$scope.search = function () {
			$state.go('course.search', $scope.searchKey);
		}
	}]);
})()