app.controller('nav', ['$scope', '$state', function($scope, $state){
	$scope.searchKey = {
		ownerId: 0,
		name: ''
	}
	$scope.search = function () {
		$state.go('course.search', $scope.searchKey);
		$scope.searchKey.ownerId = 0;
		$scope.searchKey.name = '';
	}
}]);
