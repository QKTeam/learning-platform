app.run(['$rootScope', '$http', '$timeout', '$state', function($rootScope, $http, $timeout, $state) {
	$rootScope.$state = $state;

	var fetchGlobalData = function () {
		$http.post('/api/site/globalData').success(function (response) {
			$rootScope.globalData = response.response;
		});
	}
	$timeout(fetchGlobalData, 0);

	var fetchSessionData = function () {
		$http.post('/api/site/sessionData').success(function(response) {
			// console.log(response);
			if(response.code === '0000') {
				$rootScope.sessionData = response.response;
			}
			$rootScope.$broadcast('sessionData:didRefresh');
			if($rootScope.sessionData.signin) $rootScope.$broadcast('user:didSignIn');
		});
	};
	$rootScope.$on('sessionData:willRefresh', function () {
		$timeout(fetchSessionData, 0);
	});
	$timeout(fetchSessionData, 0);

	$rootScope.teachers = [];
	var fetchTeachersList = function () {
		$http.post('/api/user/list', {roleId: 2}).success(function (response) {
			// console.log(response);
			$rootScope.teachers = response.response;
			$rootScope.teachers.splice(0, 0, {uid: 0, username: '所有老师'});
		});
	}
	$timeout(fetchTeachersList, 0);

	$rootScope.users = [];
	var fetchUsersList = function () {
		$http.post('/api/user/list').success(function (response) {
			// console.log(response);
			$rootScope.users = response.response;
		});
	}
	$timeout(fetchUsersList, 0);

	$rootScope.roles = [];
	var fetchRoleList = function () {
		$http.post('/api/role/list').success(function (response) {
			$rootScope.roles = response.response;
		});
	}
	$timeout(fetchRoleList, 0);

	$rootScope.getUserBasicInfoByUid = function (uid) {
		for (var i = $rootScope.users.length - 1; i >= 0; i--) {
			if($rootScope.users[i].uid == uid) {
				return $rootScope.users[i];
			}
		}
		return false;
	}
}]);
