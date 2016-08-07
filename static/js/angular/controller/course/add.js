app.controller('course.add', ['$scope', '$rootScope', '$http', '$state', '$uibModal', function($scope, $rootScope, $http, $state, $uibModal){
	$scope.course = {
		name: '',
		content: ''
	}
	$scope.add = function () {
		$http.post('/api/course/new', $scope.course).success(function (response) {
			if(response.code === '0000') {
				$state.go('course.show', response.response);
			}
			else {
				alert(response.errorMsg);
			}
		});
	}
}]);
