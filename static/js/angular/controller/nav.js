app.controller('nav', ['$scope', '$rootScope', '$state', '$http', function($scope, $rootScope, $state, $http){
	$scope.searchKey = {
		ownerId: 0,
		name: ''
	}
	$scope.search = function () {
		$state.go('course.search', $scope.searchKey);
		$scope.searchKey.ownerId = 0;
		$scope.searchKey.name = '';
	}
	$scope.signout = function () {
		$http.get('/api/user/signout').success(function (response) {
			$rootScope.$broadcast('sessionData:willRefresh');
		});
	}
}]);
