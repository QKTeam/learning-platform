app.controller('admin.user', ['$scope', '$rootScope', '$http', '$state', '$timeout', function($scope, $rootScope, $http, $state, $timeout){
	$scope.users = [];
	var fetchUserList = function () {
		$http.post('/api/user/list', $scope.searchKey).success(function (response) {
			if(response.code === '0000') {
				$scope.users = response.response;
			}
		});
	}
	// $timeout(fetchUserList, 0);
	$scope.$watchCollection('searchKey', function () {
		$timeout(fetchUserList, 0);
	});

	$scope.clearSearchKey = function () {
		$scope.searchKey = null;
	}

	$scope.save = function (user) {
		$http.post('/api/user/renewAdmin', user).success(function (response) {
			if(response.code === '0000') {
				alert('Done!');
			}
			else {
				alert(response.errorMsg);
			}
		}).error(function () {
			alert('Network Error!');
		});
	}
}]);
