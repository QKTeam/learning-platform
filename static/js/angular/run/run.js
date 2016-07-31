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

	$rootScope.users = [];
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
