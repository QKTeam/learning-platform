app.controller('point.renew', ['$scope', '$rootScope', '$http', '$state', '$timeout', '$uibModalInstance', 'point', function($scope, $rootScope, $http, $state, $timeout, $uibModalInstance, point){
	$scope.point = point;
	$scope.save = function () {
		$http.post('/api/point/renew', $scope.point).success(function (response) {
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
		$http.post('/api/point/delete', {pid: $scope.point.pid}).success(function (response) {
			if(response.code === '0000') {
				$state.go('course.show', {cid: $scope.point.courseId});
				$uibModalInstance.dismiss();
			}
			else {
				alert(response.errorMsg);
			}
		});
	}
}]);
