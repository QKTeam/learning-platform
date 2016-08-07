app.controller('point.show', ['$scope', '$rootScope', '$http', '$state', '$timeout', '$uibModal', function($scope, $rootScope, $http, $state, $timeout, $uibModal){
	var fetchCourse = function () {
		$http.post('/api/course/show', {cid: $scope.point.courseId}).success(function (response) {
			if(response.code === '0000') {
				$scope.course = response.response;
			}
		});
	}
	var fetchPoint = function () {
		$http.post('/api/point/show', {pid: $state.params.pid}).success(function (response) {
			if(response.code === '0000') {
				response.response.importanceStr = '';
				for(var i = 0; i < response.response.importance; i++) {
					response.response.importanceStr = response.response.importanceStr + '!';
				}
				$scope.point = response.response;
				$timeout(fetchCourse, 0);
			}
		});
	}
	$timeout(fetchPoint, 0);

	$scope.modifyPoint = function () {
		$uibModal.open({
			templateUrl: '/template/point/renew.html',
			controller: 'point.renew',
			resolve: {
				point: [function () {
					return angular.copy($scope.point);
				}]
			}
		}).result.then(function () {
			// console.log('closed');
			$timeout(fetchPoint, 0);
		});
	}
}]);
