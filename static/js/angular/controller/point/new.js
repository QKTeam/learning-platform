app.controller('point.new', ['$scope', '$rootScope', '$http', '$state', '$uibModalInstance', 'courseId', function($scope, $rootScope, $http, $state, $uibModalInstance, courseId){
	$scope.point = {
		importance: '',
		name: '',
		content: '',
		courseId: courseId
	}
	console.log($scope.point);
	$scope.add = function () {
		$http.post('/api/point/new', $scope.point).success(function (response) {
			if(response.code === '0000') {
				$state.go('point.show', response.response);
				$uibModalInstance.dismiss();
			}
			else {
				alert(response.errorMsg);
			}
		});
	}
}]);
