app.controller('course.new', ['$scope', '$rootScope', '$http', '$state', '$uibModalInstance', function($scope, $rootScope, $http, $state, $uibModalInstance){
	$scope.course = {
		name: '',
		content: ''
	}
	$scope.add = function () {
		$http.post('/api/course/new', $scope.course).success(function (response) {
			if(response.code === '0000') {
				$state.go('course.show', response.response);
				$uibModalInstance.dismiss();
			}
			else {
				alert(response.errorMsg);
			}
		});
	}
}]);
