app.controller('course.renew', ['$scope', '$rootScope', '$http', '$state', '$timeout', '$uibModalInstance', 'course', function($scope, $rootScope, $http, $state, $timeout, $uibModalInstance, course){
	$scope.course = course;
	$scope.save = function () {
		$http.post('/api/course/renew', $scope.course).success(function (response) {
			if(response.code === '0000') {
				$uibModalInstance.close();
			}
			else {
				alert(response.errorMsg);
			}
		}).error(function () {
			alert('Network Error!');
		});
	}

	$scope.delete = function () {
		if(!confirm('无法恢复，确定删除？')) return;
		$http.post('/api/course/delete', {cid: $scope.course.cid}).success(function (response) {
			if(response.code === '0000') {
				$state.go('course.search');
				$uibModalInstance.dismiss();
			}
			else {
				alert(response.errorMsg);
			}
		});
	}
}]);
